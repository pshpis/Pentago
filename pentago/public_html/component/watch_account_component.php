<?php
require_once '../model/Online_Player.php';

$watch_user = $current_user;
if (isset($_GET['id']) && (int)$_GET['id'] && (int)$_GET['id'] > 0){
    $id = (int)$_GET['id'];
    $watch_user = Online_Player::get_player_by_id($id);
    if (!$watch_user) $watch_user = $current_user;
}