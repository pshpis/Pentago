<?php
$endl = '<br/>';
$DOMAIN_PATH = 'http://localhost/pentago';
$ROOT_PATH = 'C:\xampp\htdocs\pentago';

function swap(&$x, &$y) {
    $tmp=$x;
    $x=$y;
    $y=$tmp;
}

function openDB(){
    $mysql = new mysqli("localhost", "root", "", "pentago");
    $mysql -> query("SET NAMES 'utf8'");
    return $mysql;
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

function get_default_log_chanel(){
    global $ROOT_PATH;
    $logger = new Logger('Game Logger');
    $logger->pushHandler(new StreamHandler($ROOT_PATH.'/logs/game.log', Logger::DEBUG));
    $logger->pushHandler(new FirePHPHandler());

    return $logger;
}

?>