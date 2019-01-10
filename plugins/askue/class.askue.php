<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.04.2018
 * Time: 12:09
 */

class askue
{
    //---------- Initializes WordPress hooks --------------------
    public static function init()
    {
        add_action('admin_menu', array( 'askue', 'askue_admin_actions' ));

        add_action('admin_init', array('askue', 'admin_init_func'));

        //wp_enqueue_style('askue_style', plugins_url('css/askue-style.css',__FILE__), array(), null, 'all');
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'admin_init_func' ));
        add_action( 'admin_head', array( __CLASS__, 'body_css' ) );

        //add_filter( 'nav_menu_css_class', array( __CLASS__,'be_menu_item_classes'), 10, 3 );
        //wp_enqueue_script('test_form_script', plugins_url('test_form_script.js',__FILE__), array(), null, 'all');
        //wp_register_script('donetype_script', plugins_url('donetype_script.js', __FILE__), array(), null, true);
    }


    // -------------------- action_hooks ------------------------

    // Добавим пункт меню АСКУЭ в панель wp-admin
    public static function askue_admin_actions() {
        add_menu_page("АСКУЭ", "АСКУЭ", "edit_pages", "askue_menu", array('askue', 'askue_onclick'), "", 2);

        //add_submenu_page( 'askue_menu', 'Тест формы', 'Тест формы', 'manage_options', 'test_form', array('askue', 'test_form_onclick') );
        add_submenu_page( null, 'Добавить счетчик', 'Добавить счетчик', 'manage_options', 'add_meter', array('askue', 'add_meter_onclick') );
        add_submenu_page( null, 'Добавить объект', 'Добавить объект', 'manage_options', 'add_energy_object', array('askue', 'add_energy_object_onclick') );
        add_submenu_page( null, 'Добавить тип счетчика', 'Добавить тип счетчика', 'manage_options', 'add_meter_type', array('askue', 'add_meter_type_onclick') );
        add_submenu_page( null, 'Добавить группу пользователей', 'Добавить группу пользователей', 'manage_options', 'add_user_group', array('askue', 'add_user_group_onclick') );
        add_submenu_page( null, 'Добавить пользователя', 'Добавить пользователя', 'manage_options', 'add_user', array('askue', 'add_user_onclick') );
        add_submenu_page( null, 'Показатели счетчика', 'Показатели счетчика', 'manage_options', 'meter_details', array('askue', 'meter_details_onclick') );
        add_submenu_page( null, 'Добавить показания счетчика', 'Добавить показания счетчика', 'manage_options', 'add_meter_value', array('askue', 'add_meter_value_onclick') );

        add_submenu_page( 'askue_menu', 'Пользователи', 'Пользователи', 'manage_options', 'accounts_manage', array('askue', 'accounts_management_onclick') );
    }

    public static function admin_init_func() {
        wp_enqueue_style('askue_style', plugins_url('css/askue-style.css',__FILE__), array(), null, 'all');

        wp_register_script('donetype_script', get_template_directory_uri(). '/assets/js/donetype_script.js', array(), null, true);
        wp_register_script('add_meter_ajax', plugins_url('assets/js/add_meter_ajax.js', __FILE__), array(), null, true);
        wp_register_script('add_energy_object_ajax', plugins_url('assets/js/add_energy_object_ajax.js', __FILE__), array(), null, true);
        wp_register_script('add_meter_type_ajax', plugins_url('assets/js/add_meter_type_ajax.js', __FILE__), array(), null, true);
        wp_register_script('add_user_group_ajax', plugins_url('assets/js/add_user_group_ajax.js', __FILE__), array(), null, true);
        wp_register_script('add_user_ajax', plugins_url('assets/js/add_user_ajax.js', __FILE__), array(), null, true);
        wp_register_script('delete_ajax', plugins_url('assets/js/delete_ajax.js', __FILE__), array(), null, true);

        wp_register_script('jquery_flot', plugins_url('assets/js/charts/jquery.flot.min.js', __FILE__), array(), null, true);
        wp_register_script('flot_stack', plugins_url('assets/js/charts/jquery.flot.stack.js', __FILE__), array(), null, true);
        wp_register_script('flot_categories', plugins_url('assets/js/charts/jquery.flot.categories.js', __FILE__), array(), null, true);
        wp_register_script('meter_chart', plugins_url('assets/js/charts/meter-chart.js', __FILE__), array(), null, true);

        wp_localize_script('donetype_script', 'myScript', array(
            'askue_plugin_url' => plugins_url(),
            'is_admin' => is_admin()
        ));

        wp_localize_script('delete_ajax', 'myScript', array(
            'askue_plugin_url' => plugins_url()
        ));
    }

    // -------------------- other functions ---------------------

    static function askue_onclick() {
        include("pages/askue_page.php");
    }

    static function add_meter_onclick() {
        include("pages/add_meter/add_meter_page.php");
    }

    static function add_energy_object_onclick() {
        include("pages/add_energy_object/add_energy_object_page.php");
    }

    static function add_meter_type_onclick() {
        include("pages/add_meter_type/add_meter_type_page.php");
    }

    static function accounts_management_onclick() {
        include("pages/accounts_manage/accounts_manage_page.php");
    }

    static function add_user_group_onclick() {
        include("pages/accounts_manage/add_group/add_group_page.php");
    }

    static function add_user_onclick() {
        include("pages/accounts_manage/add_user/add_user_page.php");
    }

    static function meter_details_onclick() {
        include("pages/meter_details/meter_details_page.php");
    }

    static function add_meter_value_onclick() {
        include("pages/meter_details/add_meter_value/add_meter_value_page.php");
    }

    static function body_css() {
        // This makes sure that the positioning is also good for right-to-left languages
        $x = is_rtl() ? 'left' : 'right';

        echo "
    <style type='text/css'>
        body {
            padding-right:20px;
        }
    </style>
        ";
    }

    /*public static function be_menu_item_classes( $classes, $item, $args ) {
        $classes[] = 'current-menu-item';
    }*/
}