<?php

function wpb_custom_new_menu() {
    register_nav_menu('my-custom-menu',__( 'My Custom Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );

function wpb_left_menu() {
    register_nav_menu('custom-left-admin-menu', __('Left Custom Admin Menu'));
}
add_action('init', 'wpb_left_menu');

function wpb_left_user_menu() {
    register_nav_menu('custom-left-user-menu', __('Left Custom User Menu'));
}
add_action('init', 'wpb_left_user_menu');


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

function be_menu_item_classes( $classes, $item) {
    $current_url = current_url();

    if (strstr($current_url, $item->url) && strstr($current_url, '/user-room/')) {
        if (strstr($item->url, '/accounts-management/') || strstr($item->url, '/meters-management/')) {
            $classes[] = 'current-menu-item';
        }
    }

    return array_unique( $classes );
}

add_filter( 'nav_menu_css_class', 'be_menu_item_classes', 10, 2 );

function current_url() {

    // Protocol
    $url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https://' : 'http://';
    // Server
    $url .= $_SERVER['SERVER_NAME'];
    // Port
    $url .= ( '80' == $_SERVER['SERVER_PORT'] ) ? '' : ':' . $_SERVER['SERVER_PORT'];
    // URI
    $url .= $_SERVER['REQUEST_URI'];

    return trailingslashit( $url );
}

?>