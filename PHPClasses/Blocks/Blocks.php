<?php

declare(strict_types=1);

namespace Trbsf\Blocks;



class Blocks
{

  private string $block_prefix;
  private array $blocks_to_enqueue;

  public function __construct()
  {
    $this->get_config_data();
    $this->register_dynamic_blocks();
    add_action('enqueue_block_editor_assets', array($this, 'setup_blocks_backend'));
    add_action('wp_enqueue_scripts', array($this, 'setup_blocks_frontend'));
  }

  private function register_dynamic_blocks() 
  {
    $all_blocks_dir_names = array_diff(scandir(TRBSF_BLOCKS_DIR), ['..', '.']);

    foreach ($all_blocks_dir_names as $key => $block_dir_name) {

      // TODO: Try do handle controller.php better
      // XXX: Needs to be here to be accessed from inside controller.php
      $block_prefix = $this->block_prefix;

      $block_model = json_decode(file_get_contents(TRBSF_BLOCKS_DIR . "/$block_dir_name/model.json"), true);
      $block_meta = $block_model['block_meta'];
      if (
        !array_key_exists("isJsRendered", $block_meta) || 
        (array_key_exists("isJsRendered", $block_meta) && $block_meta['isJsRendered'] === false)
      ) {
        require_once TRBSF_BLOCKS_DIR . "/$block_dir_name/controller.php";
      }
    }
  }

  public function setup_blocks_backend() {
    // Register block editor script for backend.
    wp_register_script(
      'trbsf_blocks-js', // Handle.
      TRBSF_PLUGIN_BASE_URI . 'dist/global_admin/blocks.min.js',
      array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
      null,
      true
    );

    // Register block editor styles for backend.
	  wp_register_style(
      'trbsf_blocks-editor-css', // Handle
      TRBSF_PLUGIN_BASE_URI . 'dist/global_admin/blocks-backend.css',
      array( 'wp-edit-blocks' ),
      null
    );

    // WP Localized globals. Use dynamic PHP stuff in JavaScript via `trBlocksGlobal` object.
	  wp_localize_script(
      'trbsf_blocks-js',
      'trBlocksGlobal',
      // TODO: Maybe dist, and copy images with gulp
      [
        'trbsfDirPath' => TRBSF_PLUGIN_BASE_URI . 'src/',
        'trbsfDirUrl'  => TRBSF_PLUGIN_BASE_URI . 'src/',
      ]
    );
    
    register_block_type(
      'tr/gutenberg-blocks', array(
        // Enqueue blocks.build.js in the editor only.
        'editor_script' => 'trbsf_blocks-js',
        // Enqueue blocks-admin.css in the editor only.
        'editor_style'  => 'trbsf_blocks-editor-css',
      )
    );
  }


  private function get_config_data() {
    // read and set block prefix
    $config_block_prefix = json_decode(file_get_contents(TRBSF_PLUGIN_BASE_DIR . "/src/core/config.json"), true)['BLOCK_PREFIX'];
    $this->block_prefix = $config_block_prefix;

    // read and set blocks to be enqueued
    $config_blocks_to_enqueue = json_decode(file_get_contents(TRBSF_PLUGIN_BASE_DIR . "/src/core/blocks_array.json"), true)['blocks_array'];
    $this->blocks_to_enqueue = $config_blocks_to_enqueue;
  }


  public function parse_blocks_recursive($blocks, $block_prefix, &$custom_blocks_names)
  {
    
    foreach ($blocks as $block) {
      if (!empty($block['blockName']) && str_starts_with($block['blockName'], "$block_prefix/")) {

        if (!in_array($block['blockName'], $custom_blocks_names)) {
          $custom_blocks_names[] = $block['blockName'];

          // if nested blocks, parse them as well
          if (!empty($block['innerBlocks'])) {
            $this->parse_blocks_recursive($block['innerBlocks'], $block_prefix, $custom_blocks_names);
          }
        }
      }
    }
  }


  public function file_exists_and_not_empty($file_path)
  {
    $file_size = false;
    if ( file_exists($file_path) ) {
      $file_size = filesize($file_path);
    }
    $is_empty = false;

    $is_js_file = str_ends_with($file_path, ".js");

    if ( $is_js_file && is_numeric( $file_size ) && $file_size === 15 ) {
      // file size 15 means that it is an "empty" file tnat compiler created
      // The contents are, and should not be enqueued: 
      // '(() => {
      // })();'
      $is_empty = true;
    }

    return (file_exists($file_path) && !$is_empty && is_numeric( $file_size ) && $file_size > 0);
  }


  // Conditionally loads CSS / JS only once 
  // if there is at least on instance of the block present
  public function setup_blocks_frontend()
  {
    if (!is_single() && !is_page()) return;

    if (empty($this->blocks_to_enqueue)) return; 

    global $post;


    if (empty($post->post_content)) return;
    if (!has_blocks($post->post_content)) return;



    $blocks = parse_blocks($post->post_content);

    if (empty($blocks)) return;

    $custom_blocks_names = [];

    // START:populate custom_blocks_names arr without duplicates
    // if there are repeated blocks, add only once 
    // so we don't enqueue duplicate CSS and JS
    $this->parse_blocks_recursive($blocks, $this->block_prefix, $custom_blocks_names);
    //END:populate custom_blocks_names arr without duplicates


    if (empty($custom_blocks_names)) return;
    // If page/post contains custom blocks

    $blocks_shared_css = [];
    $blocks_shared_js = [];

    function trbsf_should_consider_dep($deps_name, $deps_arr) {
      return (
        array_key_exists($deps_name, $deps_arr) 
        && 
        is_array($deps_arr[$deps_name]) 
        && 
        !empty($deps_arr[$deps_name])
      );
    }

    foreach ($custom_blocks_names as $custom_block_name) {
      $block_name_without_prefix = substr(
        $custom_block_name, 
        strlen($this->block_prefix) + 1
      );

      // Check if block has deps (CSS/JS) defined in model.json
      $block_model = json_decode(file_get_contents(TRBSF_BLOCKS_DIR . "/$block_name_without_prefix/model.json"), true);
      $block_meta = $block_model['block_meta'];
      // START:block_meta contains deps property
      if (array_key_exists("deps", $block_meta)) {
        $deps = $block_meta['deps'];
        
        //START:populate $blocks_shared_css array
        if (trbsf_should_consider_dep('css', $deps)) {
          foreach ($deps['css'] as $single_dep_css) {
            if (!in_array($single_dep_css, $blocks_shared_css)) {
              $blocks_shared_css[] = $single_dep_css;
            }
          }
        }
        //END:populate $blocks_shared_css array

        //START:populate $blocks_shared_js array
        if (trbsf_should_consider_dep('js', $deps)) {
          foreach ($deps['js'] as $single_js_css) {
            if (!in_array($single_js_css, $blocks_shared_js)) {
              $blocks_shared_js[] = $single_js_css;
            }
          }
        }
        //END:populate $blocks_shared_js array
        
      }
      // END:block_meta contains deps property
    }

    $shared_css_and_js_system_dir_path = TRBSF_PLUGIN_BASE_DIR . "/dist/blocks-shared";
    $shared_css_and_js_system_dir_uri  = TRBSF_PLUGIN_BASE_URI . "/dist/blocks-shared";

    //start:enqueue each shared CSS file
    if (!empty($blocks_shared_css)) {
      foreach ($blocks_shared_css as $shared_css_filename) {
        // if directory contains specified shared CSS file and it is not empty
        $custom_block_shared_css_path = "$shared_css_and_js_system_dir_path/$shared_css_filename.min.css";
        if ($this->file_exists_and_not_empty($custom_block_shared_css_path)) {
          wp_enqueue_style(
            "trbsf-block-shared-css--$shared_css_filename", 
            "$shared_css_and_js_system_dir_uri/$shared_css_filename.min.css"
          );
        }
      }
    }
    //end:enqueue each shared CSS file

    
    //start:enqueue each shared JS file
    if (!empty($blocks_shared_js)) {
      foreach ($blocks_shared_js as $shared_js_filename) {
        // if directory contains specified shared JS file and it is not empty
        $custom_block_shared_js_path = "$shared_css_and_js_system_dir_path/$shared_js_filename.min.js";
        if ($this->file_exists_and_not_empty($custom_block_shared_js_path)) {
          wp_enqueue_script(
            "trbsf-block-shared-js--$shared_js_filename",
            "$shared_css_and_js_system_dir_uri/$shared_js_filename.min.js",
            array(), 
            false, 
            true
          );
        }
      }
    }
    //end:enqueue each shared JS file


    // START:CHECK FOR AND ENQUEUE BLOCK SPECIFIC CSS AND JS
    $blocks_dist = TRBSF_PLUGIN_BASE_DIR . 'dist/block-specific';

    $block_css_filename = "frontend.min.css";
    $block_js_filename  = "frontend.min.js";


    $additional_critical_css = '';

    foreach ($custom_blocks_names as $custom_block_name) {

      $block_name_without_prefix = substr(
        $custom_block_name,
        strlen($this->block_prefix) + 1
      );

      $block_meta = json_decode(file_get_contents(TRBSF_BLOCKS_DIR . "/$block_name_without_prefix/model.json"), true)['block_meta'];
      $critical = !empty( $block_meta['critical'] );

      $custom_block_dir_path = "{$blocks_dist}/{$block_name_without_prefix}";
      $gutenberg_blocks_dist_uri = TRBSF_PLUGIN_BASE_URI . 'dist/block-specific';


      // if directory contains frontend.css and it is not empty
      $custom_block_css_path = "$custom_block_dir_path/$block_css_filename";

      if ($this->file_exists_and_not_empty($custom_block_css_path)) {

        if ( !$critical ) {
          wp_enqueue_style(
            "trbsf-{$block_name_without_prefix}-frontend-css",
            "$gutenberg_blocks_dist_uri/$block_name_without_prefix/$block_css_filename"
          );
        } else {
          $additional_critical_css .= file_get_contents($custom_block_css_path);
        }

      }
      //end:enqueue block specific CSS




      // if directory contains frontend.js and it is not empty
      $custom_block_js_path = "$custom_block_dir_path/$block_js_filename";


      if ($this->file_exists_and_not_empty($custom_block_js_path)) {
        wp_enqueue_script(
          "trbsf-{$block_name_without_prefix}-frontend-js",
          "$gutenberg_blocks_dist_uri/$block_name_without_prefix/$block_js_filename",
          array(),
          false,
          true
        );
      }
      //end:enqueue block specific JS

    }

    if ( !empty( $additional_critical_css ) ) {
      $print_additional_critical_css = function() use ( $additional_critical_css ) {
      $critical_block_style  = '<style ';
      $critical_block_style .=   'id="tr-block-css--critical"';
      $critical_block_style .= '>';
      $critical_block_style .=   $additional_critical_css;
      $critical_block_style .= '</style>';
      echo $critical_block_style;
    };
    add_action( 'wp_head', $print_additional_critical_css, 200 );
    }
    // END:CHECK FOR AND ENQUEUE BLOCK SPECIFIC CSS AND JS


  }
}