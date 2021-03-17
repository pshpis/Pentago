<?php
require_once '../model/default_model.php';
require_once '../model/User.php';
$activation_key = $_GET['key'];
$mysql = openDB();
$mysql -> query("UPDATE `".User::$table_name."` SET `is_activate` = '1' WHERE `activation_key` = '".$activation_key."'");
$mysql -> close();