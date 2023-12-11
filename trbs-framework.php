<?php

/**
 * Plugin Name:         TR-Blocks Standalone by WebRedone
 * Plugin URI:          // TODO: Link to GH Repo https://nikolaivanovwd.com/plugins/niwd-ski-api-blocks
 * Description:         // TODO: Write a better description
 * Author:              WebRedone
 * Author URI:          https://webredone.com
 * 
 * Version:             1.0.0
 * License:             MIT
 * Text Domain:         trbs
 */


 // Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

define('TRBSF_PLUGIN_BASE_DIR',  plugin_dir_path(__FILE__));
define('TRBSF_PLUGIN_BASE_URI',  plugins_url() . '/trbs-framework/');
define('TRBSF_BLOCKS_DIR',  TRBSF_PLUGIN_BASE_DIR . '/src/blocks/');



require_once TRBSF_PLUGIN_BASE_DIR . "vendor/autoload.php";


use Trbsf\Main\Main;


if ( !function_exists( 'TrbsfInit' ) ) {
  function TrbsfInit()
  {
    $latte = new \Latte\Engine;
    $latte->setTempDirectory( TRBSF_PLUGIN_BASE_DIR . '/views/cache' );
    define('LATTE', $latte);

    if ( isset( $_ENV[ 'TRACY_DEBUGGER' ] ) && "true" === $_ENV[ 'TRACY_DEBUGGER' ] ) {
      // TODO: Fix Tracy not loading
      \Tracy\Debugger::enable();
    }

    require_once TRBSF_PLUGIN_BASE_DIR . 'src/core/trbsf-helper-fns.php';

    $trbsf_instance = new Main();
    return $trbsf_instance;
  }
  add_action( 'plugins_loaded', 'TrbsfInit' );
}