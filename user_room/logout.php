<?php
    setcookie('hash', "", time()-3600, "/", false);
    setcookie('id', "", time()-3600, "/", false);
    setcookie('login', "", time()-3600, "/", false);

    header('location: /user_room_page/auth/');
?>