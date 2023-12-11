<?php

declare(strict_types=1);

namespace Trbsf\Main;

use Trbsf\Blocks\Blocks;



class Main
{
  public function __construct()
  {
    $this->init_dot_env();
    new Blocks();
    // Attach global JS vars for the AJAX purposes
    add_action('wp_head', array( $this, 'register_js_global_vars_frontend' ));
  }

  private function init_dot_env()
  {
    $dotenv = \Dotenv\Dotenv::createUnsafeImmutable( TRBSF_PLUGIN_BASE_DIR );
    $dotenv->load();
  }


  // TODO: Maybe refactor with wp localize script
  public function register_js_global_vars_frontend()
  {
?>
    <script type="text/javascript" id="trbsf_js_globals_fe">
      window.trbsf_theme_url = '<?php echo get_bloginfo( "template_url" ); ?>'
      window.tr_site_url     = '<?php echo esc_url( home_url( '/' ) ); ?>'
      window.tr_rest_url     = '<?php echo rest_url( '/wp/v2/' ); ?>'
    </script>
  <?php
  }
}