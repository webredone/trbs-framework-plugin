<?php
$block_name = basename(__DIR__);
// $block_prefix is defined inside init.php

register_block_type("$block_prefix/$block_name", array(
  'render_callback' => function($attrs, $content) {
    


    // START:Add or modify $attrs[] params here
    // ...
    // END:Add or modify $attrs[] params here
    $html_str = LATTE->renderToString(dirname( __FILE__ ) . '/view.latte', $attrs);
    return $html_str;
  },
  'attributes' => json_decode(file_get_contents(dirname( __FILE__ ) . "/model.json"), true)['attributes']
));
