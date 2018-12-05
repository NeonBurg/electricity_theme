<?php
    session_start();

    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/user_room/utils/errorMessage.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/user_room/utils/encrypt.php' );

    $login = trim($_POST["login_input"]);
    $password = trim($_POST["pass_input"]);
    $password_repeat = trim($_POST["pass_repeat_input"]);
    $email = trim($_POST["email_input"]);

    $user_hash = $_COOKIE['hash2'];
    if(!isset($user_hash)) {
        $user_hash = md5(generateCode(10));
        setcookie("hash2", $user_hash, time()+60*60*24*30, '/', false);
    }

    $err = array();

    /* --------------------========= Проверка логина ==========-------------------- */
    // Проверка правильности логина
    if(!preg_match("/^[a-zA-Z0-9]+$/",$login))
    {
        $err[ErrorMessage::$INCORRECT_LOGIN] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    // Проверяем длину логина
    if(strlen($login) < 3 or strlen($login) > 30)
    {
        $err[ErrorMessage::$INCORRECT_LOGIN] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    //echo "<p>login: ".$login."</p>";

    // Проверяем уникальность логина в БД
    global $wpdb;
    if($wpdb) {

        $result = $wpdb->get_results("SELECT login FROM user_room_accounts WHERE login = '".$login."'");

        if($result)
        {
            $err[ErrorMessage::$INCORRECT_LOGIN] = "Пользователь с таким логином уже существует";
        }
    }
    else {
        $err[ErrorMessage::$NOT_CONNECTED] = "Отсутсвует соединение с базой данных";
    }

    /* --------------------========= Проверка пароля ==========-------------------- */
    if(strlen($password) == 0) {
            $err[ErrorMessage::$INCORRECT_PASS] = "Поле с паролем не должно быть пустым";
    }
    else if(strlen($password) < 6) {
        $err[ErrorMessage::$INCORRECT_PASS] = "Длина пароля должна быть больше 6 символов";
    }

    if(strpos($password, 0x20) == true) {
            $err[ErrorMessage::$INCORRECT_PASS] = "Пароль не должен содержать пробелы";
        }

    if($password != $password_repeat) {
        $err[ErrorMessage::$INCORRECT_REPEAT_PASS] = "Пароли не совпадают";
    }

    /* --------------------========= Проверка email адреса ==========-------------------- */
    /*if(strlen($email) == 0) {
        $err[ErrorMessage::$EMAIL_WRONG] = "Поле с e-mail адресом не должно быть пустым";
    }*/
    if(strlen($email) != 0) {
        $s_email_result= $wpdb->get_results("SELECT email FROM user_room_accounts WHERE email = '".$email."'");

        if($s_email_result) {
            $err[ErrorMessage::$EMAIL_WRONG] = "Пользователь с таким e-mail адресом уже зарегистрирован";
        }
    }


	/* -------==== Проверка соглашения на обработку персональных данных ====-------- */
	$agreementCheck = $_POST["agreement"];
	if($agreementCheck == false) {
		$err[ErrorMessage::$PERSONAL_AGREEMENT] = "Вы не дали согласие на обработку персональных данных";
	}
	

    // Если ошибок нет, создаем нового пользователя
    if(count($err) == 0) {

        $password_hash = encryptIt($password, $login); // Создаем хэш пароля

        // Создаем пользователя в БД:
        // Добавляем запись о пользователе в таблицу 'Users'
        //$result = $wpdb ->get_results("INSERT INTO user_room_accounts(login, password_hash, session_hash) VALUES('".$login."', '".$password_hash."', null)");
        $sql = $wpdb->prepare("INSERT INTO user_room_accounts(login, password_hash, session_hash) VALUES(%s, %s, %s)", $login, $password_hash, null);
        $wpdb->query($sql);

        $result = $wpdb->get_results("SELECT login FROM user_room_accounts WHERE login = '".$login."'");

        if($result) {
            //$user_id_select = $DB->Query("SELECT user_id FROM b_user_room_accounts WHERE login = '".$login."'");
            //$user_id_res = $user_id_select->Fetch();

            header('location: /user-room/auth/?login='.$login.'&registration_success=Успешная регистрация');
        }
		else {
			echo "Ошибка mysql";
		}
    }
    // Вывод ошибок:
    else {
        $_SESSION[$user_hash] = $err;

        //foreach($err as $er) {
        //    echo "er = " . $er;
        //}

        //if(isset($_SESSION[$user_hash])) echo "<p>Сессия не пустая</p>";
        header('location: /user-room/registration/?login='.$login.'&email='.$email.'&registration_success=false');
    }
?>