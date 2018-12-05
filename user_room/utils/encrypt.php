<?php

	//Hash функция для кода активации email
    function encryptEmail($email) {
        $code = md5($email.time());
        return $code;
    }


	// Hash функция для пароля
	function encryptIt($pass, $login) {
		$pSalt = md5(md5($login));
		$options = array(
			'cost' => 12,
			'salt' => $pSalt,
		);
		$qEncoded = password_hash($pass, PASSWORD_BCRYPT, $options);
		return( $qEncoded );
	}

	// Получим ip пользователя
    function get_ip()
    {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
            {
                $ip=$_SERVER['HTTP_CLIENT_IP'];
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                $ip=$_SERVER['REMOTE_ADDR'];
            }
            return $ip;
    }

    // Генерируем случайную строку
    function generateCode($length=6) {

            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

            $code = "";

            $clen = strlen($chars) - 1;
            while (strlen($code) < $length) {

                    $code .= $chars[mt_rand(0,$clen)];
            }

            return $code;
    }

    function decrypt( $q ) {
        $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
        $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
        return( $qDecoded );
    }

    function redirect($url)
    {
        $string = '<script type="text/javascript">';
        $string .= 'window.location = "' . $url . '"';
        $string .= '</script>';

        echo $string;
    }

?>