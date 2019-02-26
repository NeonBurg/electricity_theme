<?php
    if($_SERVER['CONTEXT_DOCUMENT_ROOT']) $path = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
    else $path = $_SERVER['DOCUMENT_ROOT'];

    require_once( $path . '/wp-load.php');
    require_once( $path . '/user_room/utils/encrypt.php' );
    require_once( $path . '/user_room/utils/errorMessage.php' );

    $login = trim($_POST['login_input']);
    $password = trim($_POST['pass_input']);

    $err = array();

    global $wpdb;

    if($wpdb) {
        // Выбираем пользователя из таблицы БД по логину

        $result = $wpdb->get_row($wpdb->prepare("SELECT id, login, password_hash FROM user_room_accounts WHERE login = %s", $login));

        if($result) {
            $s_user_id = $result->id;
            $s_login = $result->login;
            $s_password = $result->password_hash;
            if($s_user_id) {
                //echo "<p>user_id: ".$s_user_id." | login: ".$s_login." | password: ".$s_password."</p>";

                $password_hash = encryptIt($password, $login);

                if($password_hash != $s_password) {
                    //echo "<p>password_hash: ".$password_hash."</p>";
                    //echo "<p>pass: ".$password." | password_hash2: ".encryptIt($password, $login)."</p>";
                    $err[] = "Неверный пароль";
                }
            }
            else {
                $err[] = "Неверный логин";
            }

            // Если ошибок нет
            if(count($err) == 0) {
                $usr_ip = ip2long(get_ip());
                $usr_hash = md5(generateCode(10)); // Хэш идентификатор сессии

                // Ставим куки
                setcookie("id", $s_user_id, time()+60*60*24*30, "/", false);
                setcookie("login", $login, time()+60*60*24*30, "/", false);
                setcookie("hash", $usr_hash, time()+60*60*24*30, "/", false);

                // Записываем в БД новый хеш авторизации и IP
				$fields_array = array("hash" => $usr_hash,
									  "ip" => $usr_ip);
				
				//$strUpdate = $DB->PrepareUpdate("b_user_room_accounts", $fields_array, "form");
				//$strSql = "UPDATE b_user_room_accounts SET ".$strUpdate." WHERE user_id=".$s_user_id;
				//$DB->Query($strSql);
                //$sqlUpdate = $wpdb->prepare("UPDATE user_room_accounts SET (session_hash, ip) VALUES(%s, %s)", $usr_hash, $usr_ip);
                //$wpdb->query($sqlUpdate);

                $update = $wpdb->update('user_room_accounts', array('session_hash'=>$usr_hash, 'ip'=>$usr_ip), array('id'=>$s_user_id));

                /*if($update) {
                    header("location: /user_room_page/");
                }
                else {
                    $err[] = "Ошибка UPDATE user_room_accounts";
                    header("location: /user_room_page/auth/?login=".$login."&err=".$err[0]);
                }*/

                $result = $wpdb->get_row($wpdb->prepare("SELECT session_hash FROM user_room_accounts WHERE id = %d", $s_user_id));
                if($result->session_hash) {
                    header("location: ".site_url('/user-room/'));
                }
                else {
                    $err[] = "Ошибка UPDATE user_room_accounts";
                    header("location: ".site_url('/user-room/auth/?login='.$login.'&err='.$err[0]));
                }

                //echo "auth success";
                //header("location: /user_room_page/");
            }
            // Есть ошибки:
            else {
                //echo "s_user_id = " . $s_user_id . " | s_login = " . $s_login;
                header("location: ".site_url('/user-room/auth/?login='.$login.'&err='.$err[0]));
            }
        }
        else {
            $err[] = "Неверный логин";
            header("location: ".site_url('/user-room/auth/?login='.$login.'&err='.$err[0]));
        }
    }
    else {
        header("location: ".site_url('/user-room/auth/?login='.$login.'&err="Отсутсвует соединение с базой данных"'));
    }
?>