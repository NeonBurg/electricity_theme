<? //global $wpdb; ?>

    <script type="text/javascript">
        jQuery("li.current-page-ancestor").addClass('current-menu-item');
    </script>

<table border="0" width="100%">
    <tbody>
    <tr>
        <td align="center">
            <div class="login_line"></div>
            <div class="login_header">
                <div class="login_title">Вход в личный кабинет</div>
            </div></td>
    </tr>

    <tr>
        <td align="center" style="font-family: Calibri;">
            <?php
            if(isset($_GET['err'])) {
            echo "
            <div style='padding-top:10px;'><font color='RED'>".$_GET['err']."</font></div>
            ";
            }
            else if(isset($_GET['registration_success'])) {
                echo "<font color='GREEN' style='padding-top:10px;'>".$_GET['registration_success']."</font>";
            }
            ?></td>
    </tr>
    </tbody>
</table>

<?php
    /*if(isset($_GET['err'])) {
        echo "err: ".$_GET['err'];
    }
    else {
        echo "empty error: ".$_GET['err'];
    }*/
?>

<form method="post" action="../../user_room/form_actions/authenticate.php">
    <table align="center" class="login_table">
        <tr align="center">
            <td width="19%">
                Логин:
            </td>
            <td align="center">
                <input type="text" name="login_input" class="user_room_input" autocomplete="off">
            </td>
        </tr>

        <tr><td><div style="height:10px;"></div></td></tr>

        <tr>
            <td align="center">
                Пароль:
            </td>
            <td align="center">
                <input type="password" name="pass_input" class="user_room_input">
            </td>
        </tr>
    </table>


    <table align="center" class="registration_line_table">
        <tr>
            <td>
                <a href="/user_room_page/registration/" class="registration_link">Регистрация нового пользователя</a>
            </td>
        </tr>
        <tr>
            <td align="right">
                <input type="submit" value="Вход" class="login_button login_button1">
            </td>
        </tr>
    </table>
</form>


<?php
    //echo "<br>SELECT: <br>";

    /*global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM Test_Table");

    if(count($result) == 0) echo "Empty result!";

    //print_r($result);

    /*foreach($result as $item) {
        echo $item->login . "<br>";
    }*/
?>