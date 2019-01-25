<?php
if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

    setcookie('hash', "", time()-3600, "/", false);
    setcookie('id', "", time()-3600, "/", false);
    setcookie('login', "", time()-3600, "/", false);

    header('location: '.site_url('/user-room/auth/'));
?>