<?php
    /**
     * @package askue
     */
    /*
     Plugin Name: ASKUE admin menu items
     Plugin URI: http://www.google.com
     Description: Пункты меню wp-admin для настройки системы АСКУЭ
     Author: Nick
     Version: 1.0
     Author URI: http://empty.com
     */

define( 'ASKUE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( ASKUE_PLUGIN_DIR . 'class.askue.php' );

add_action('init', array('askue', 'init'));


?>