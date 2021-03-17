<?php

require_once '../model/default_model.php';
require_once '../model/Online_Player.php';
$current_user = false;
if (!isset($_POST['signup_submit']) && !isset($_POST["login_submit"])){
    $current_user = get_player_from_cookie();
//    if ($current_user === false) header("Location: login_form.php");
}

?>