<?php
$endl = '<br/>';
$DOMAIN_PATH = 'http://a0446139.xsph.ru';
$ROOT_PATH = '/home/a0446139/domains/a0446139.xsph.ru/public_html';

function swap(&$x, &$y) {
    $tmp=$x;
    $x=$y;
    $y=$tmp;
}

function openDB(){
    $mysql = new mysqli("localhost", "a0446139_pentago", "idiczuihik", "a0446139_pentago");
    $mysql -> query("SET NAMES 'utf8'");
    return $mysql;
}

?>