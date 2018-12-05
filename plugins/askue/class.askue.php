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

        wp_enqueue_style('askue_style', plugins_url('css/style.css',__FILE__), array(), null, 'all');
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

        add_submenu_page( 'askue_menu', 'Пользователи', 'Пользователи', 'manage_options', 'accounts_manage', array('askue', 'accounts_management_onclick') );
    }

    public static function admin_init_func() {
        wp_register_script('donetype_script', get_template_directory_uri(). '/assets/js/donetype_script.js', array(), null, true);
        wp_register_script('add_meter_ajax', plugins_url('assets/js/add_meter_ajax.js', __FILE__), array(), null, true);
        wp_register_script('add_user_group_ajax', plugins_url('assets/js/add_user_group_ajax.js', __FILE__), array(), null, true);
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

}