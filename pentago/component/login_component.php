<?php
require_once '../model/default_model.php';
require_once '../model/Online_Player.php';
require_once 'default_component.php';

if (isset($_POST["login_submit"])){
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];
    $current_user = new Online_Player($username, $password, '', true);
    if ($current_user -> get_id() === -1){
        $current_user = false;
//        header("Location: ../login/");
    }
}

?>