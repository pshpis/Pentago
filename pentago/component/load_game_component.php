<?php
require_once '../model/default_model.php';
require_once '../model/Pentago_Session.php';

$game = new Pentago_Session($current_user -> id);

while ($game -> get_count() != 2){
    $game -> data_update();
}

//echo $game->get_count();