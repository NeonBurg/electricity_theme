<?php

function wpb_custom_new_menu() {
    register_nav_menu('my-custom-menu',__( 'My Custom Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );

function wpb_left_menu() {
    register_nav_menu('custom-left-menu', __('Left Custom Menu'));
}
add_action('init', 'wpb_left_menu');


add_theme_support( 'post-thumbnails' );

remove_filter( 'the_content', 'wpautop' );


function themeslug_enqueue_style() {
    wp_enqueue_style( 'style', get_stylesheet_uri(), false);

    //wp_deregister_script('jquery');
    //wp_enqueue_script('jqeury', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', array(), null, 'all');
    wp_enqueue_script('jqeury', get_template_directory_uri(). '/assets/js/jquery-3.3.1.min.js', array(), null, 'all');
    wp_enqueue_script('hold_menu', get_template_directory_uri(). '/assets/js/hold_menu.js', array('jquery'), null, 'all');

    wp_register_script('donetype_script', get_template_directory_uri(). '/assets/js/donetype_script.js', array(), null, true);
    wp_register_script('registration_ajax', get_template_directory_uri(). '/assets/js/registration_ajax.js', array(), null, true);
}

add_action('wp_enqueue_scripts', 'themeslug_enqueue_style');

?>