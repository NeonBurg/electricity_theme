<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Elegant
 * @version 1.0
 */


?><!DOCTYPE html>

<?php
/*global $post;
if(isset($post) && $post->post_type == 'page' && ($post->ID == '10' || $post->ID == '57' || $post->ID == '55')) {
    global $wpdb;
    include( $_SERVER['DOCUMENT_ROOT'] . '/user_room/header_settings.php' );
}*/
?>

<html <?php language_attributes();?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title>
        <?php bloginfo('name');
        if(wp_title()) wp_title();
        ?>
    </title>

    <?php wp_head();?>
</head>

<body>

<div class="page">
		<div class="header-banner">
			<div class="header-block-info">
				<a href="/"><div class="header-logo"></div></a>
				<div class="header-description"><?php echo get_bloginfo('description'); ?></div>
			</div>
		</div>

<div class="menu-container">
    <div style="width:980px; display:block; margin:auto;">
<?php
    wp_nav_menu( array('theme_location' => 'my-custom-menu',
                        'container_class' => 'custom-menu-container',
                        'menu_class' => 'custom-menu-class'));
?>
    </div>
</div>

