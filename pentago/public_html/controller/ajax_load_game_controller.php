<?php
require_once '../model/default_model.php';
require_once '../model/Pentago_Session.php';
require_once '../component/auto_login_component.php';

ajax_ans();

function ajax_ans(){
    global $current_user;
    if ($current_user === false){
        return;
    }
    if (isset($_GET['get_count'])){
        $game = new Pentago_Session($current_user -> get_id());
        echo $game -> get_count();
    }
}
