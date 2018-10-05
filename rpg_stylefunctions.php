<?php

add_action( 'wp_print_styles', 'deregister_bbpress_styles', 15 );
function deregister_bbpress_styles() {
    wp_deregister_style( 'bbp-default' );
}


?>