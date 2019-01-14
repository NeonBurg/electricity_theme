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

    static function install() {
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
        global $wpdb;

// Create ASKUE tables
        $sql_create_tables = array();
        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS Concentrators (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  address varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS Customers (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  surname varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  patronymic varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  account_id int(10) NOT NULL,
  phone varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  group_id int(10) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS EnergyObjects (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  address varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  customer_id int(10) NOT NULL,
  energyObject_id int(10) DEFAULT NULL,
  meter_id int(10) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS MeterTypes (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  type varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS Meters (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  num int(10) DEFAULT NULL,
  table_name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  energyObject_id int(10) NOT NULL,
  meterType_id int(10) NOT NULL,
  concentrator_id int(10) DEFAULT NULL,
  network_address int(10) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS UserGroups (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  access_level int(10) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$sql_create_tables[] = "INSERT INTO UserGroups(name, access_level) VALUES('Клиенты', 1), ('Операторы', 2), ('Администраторы', 3)";

        $sql_create_tables[] = "CREATE TABLE IF NOT EXISTS user_room_accounts (
  id int(11) NOT NULL AUTO_INCREMENT,
  login varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  password_hash varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  session_hash varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  ip mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        $sql_create_tables[] = "ALTER TABLE Customers
  ADD UNIQUE KEY account_id (account_id),
  ADD KEY FKCustomers292522 (account_id),
  ADD KEY fk_group_id (group_id);";

        $sql_create_tables[] = "ALTER TABLE EnergyObjects
  ADD KEY FKEnergyObje977167 (customer_id),
  ADD KEY energyObject_id (energyObject_id);";

        $sql_create_tables[] = "ALTER TABLE Meters
  ADD KEY FKMeters906536 (energyObject_id),
  ADD KEY FKMeters262490 (concentrator_id);";

// --------------------- Создаем таблицы ------------------------------
        foreach($sql_create_tables as $sql_create_table) {
            $wpdb->query($sql_create_table);
        }
// --------------------------------------------------------------------

//echo "Ошибка: " .$wpdb->last_error."<br>";
//echo "last_query: " .$wpdb->last_query."<br>";

        //$sql_check_root_pages_exist = "SELECT post_name FROM wp_posts WHERE post_name = 'user-room'";
        //$result = $wpdb->get_row($sql_check_root_pages_exist);

        $user_room_id = -1;
        $meters_management_id = -1;
        $accounts_management_id = -1;
        $meter_details_id = -1;

        self::insertPage('Личный кабинет', 'user-room', 0, $wpdb);

        $result = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_name = 'user-room'");
        if($result) {
            $user_room_id = $result->ID;
        }

        if($user_room_id !== -1) {
            self::insertPage('Управление счетчиками', 'meters-management', $user_room_id, $wpdb);
            self::insertPage('Управление пользователями', 'accounts-management', $user_room_id, $wpdb);

            $result = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_name = 'user-room'");
            if($result) {
                $user_room_id = $result->ID;
            }

            $result = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_name = 'meters-management'");
            if($result) {
                $meters_management_id = $result->ID;
            }

            $result = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_name = 'accounts-management'");
            if($result) {
                $accounts_management_id = $result->ID;
            }

            $debug_message = "user_room_id = ".$user_room_id. " | meters_management_id = " . $meters_management_id . " | accounts_management_id = " .$accounts_management_id;

            //$wpdb->query("CREATE TABLE IF NOT EXISTS Debug (id int(10) NOT NULL AUTO_INCREMENT, message TEXT, PRIMARY KEY(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
            //$wpdb->query($wpdb->prepare("INSERT INTO Debug(message) VALUES (%s)", $debug_message));

            if($meters_management_id !== -1) {
                self::insertPage('Показатели счетчика', 'meter-details', $meters_management_id, $wpdb);

                $result = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_name = 'meter-details'");
                if($result) {
                    $meter_details_id = $result->ID;
                }

                if($accounts_management_id !== -1 && $meter_details_id !== -1) {

                    self::insertPage('Авторизация', 'auth', $user_room_id, $wpdb);
                    self::insertPage('Регистрация', 'registration', $user_room_id, $wpdb);
                    self::insertPage('Добавление типа счетчиков', 'add-meter-type', $meters_management_id, $wpdb);
                    self::insertPage('Добавление счетчика', 'add-meter', $meters_management_id, $wpdb);
                    self::insertPage('Добавление энергетического объекта', 'add-energy-object', $meters_management_id, $wpdb);
                    self::insertPage('Добавление новой группы', 'add-group', $accounts_management_id, $wpdb);
                    self::insertPage('Добавление нового пользователя', 'add-user', $accounts_management_id, $wpdb);
                    self::insertPage('Добавить показания счетчика', 'add-meter-value', $meter_details_id, $wpdb);

                }

            }
        }
    }

    static function insertPage($page_title, $page_name, $page_parent, $wpdb) {
        $sql_insert_page = "INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count) VALUES
(1, '2018-08-30 12:32:02', '2018-08-30 12:32:02', '', '".$page_title."', '', 'publish', 'closed', 'closed', '', '".$page_name."', '', '', '2018-11-26 13:01:33', '2018-11-26 13:01:33', '', ".$page_parent.", '', 0, 'page', '', 0)";
        $wpdb->query($sql_insert_page);
    }
}