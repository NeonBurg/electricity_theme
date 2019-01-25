<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.04.2018
 * Time: 9:55
 */

require_once( ABSPATH . '/user_room/utils/encrypt.php' );
require_once( ABSPATH . '/user_room/utils/check.php' );

global $wpdb;

if(!$wpdb) {
    //echo "!wpdb";
    header("location:" . site_url('/user-room/auth/?err=Отсутсвует соединение с базой данных'));
}
else {
    $access_level = checkAuth($wpdb);
    define('ACCESS_LEVEL', $access_level);
    if($access_level == -1) {
        header(("location: " . site_url('/user-room/auth/')));
    }
}



/*else {
    $check_auth = checkAuth($wpdb);
    if(count($check_auth) != 0) {
        //foreach($check_auth as $err) {
        //    echo "err = ". $err . " | ";
        //}
        header("location: /user_room_page/auth/?err=".$check_auth[0]);
    }
}*/

//else if(checkAuth($wpdb) == false) {
//    header("location: /user_room_page/auth/?err=Необходима авторизация");
//}

?>