<?php
$endl = '<br/>';
$domain = 'localhost/pentago/';

function openDB(){
    $mysql = new mysqli("localhost", "a0446139_pentago", "root", "a0446139_pentago");
    $mysql -> query("SET NAMES 'utf8'");
    return $mysql;
}

?>