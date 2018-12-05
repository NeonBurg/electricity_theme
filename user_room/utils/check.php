<?php

    // ------------- Проверка авторизации --------------
    function checkAuth($conn) {
       $err = array();
		
		if($conn) {
			if(isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {

				$user_id = trim($_COOKIE['id']);
					
				$result = $conn->get_row("SELECT id, session_hash, ip FROM user_room_accounts WHERE id = '".$user_id."'");

				if($result) {
					$s_user_id = $result->id;
					$s_hash = $result->session_hash;
					$s_ip = $result->ip;

					if($s_user_id) {
						if($s_user_id != $_COOKIE['id'] or $s_hash != $_COOKIE['hash'] or long2ip($s_ip) != get_ip()) {
							//$err[] = "Ошибка авторизации1: s_user_id = " . $s_user_id . " |  _COOKIE['id'] = ". $_COOKIE['id'] . " | s_hash = " . $s_hash . " | _COOKIE['hash'] = " . $_COOKIE['hash'] . " | long2ip(s_ip) = " . long2ip($s_ip) . " | get_ip = " . get_ip();
							return false;
						}
						else {
							// Успешная авторизация
							return true;
						}
					}
					else {
						//$err[] = "Ошибка авторизации2";
						return false;
					}
				}
				else {
					//$err[] = "Ошибка SQL";
					return false;
				}
			}
			else {
				//$err[] = "Включите cookies"; // Включите cookies
				return false;
			}
		}
        else {
            //$err[] = "Пустой объект mysqli";
			return false;
        }
        //return $err;
    }


?>