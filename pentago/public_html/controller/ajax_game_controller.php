<?php
require_once '../model/default_model.php';
require_once '../model/Online_Player.php';
require_once '../model/Pentago_Session.php';
require_once '../component/default_component.php';
require_once '../component/auto_login_component.php';

if (isset($_GET['get_id'])){
    echo $current_user -> get_id();
}

$is_game = Pentago_Session::is_game_session($current_user -> get_id());

if ($is_game) $game = new Pentago_Session($current_user->get_id());
else $game = false;

//var_dump($is_game);

if ($game !== false) ajax_ans();

function ajax_ans(){
    global $game;
    global $current_user;

    $global_mode = false;

    if (isset($_GET['global_mode'])) $global_mode = true;
    if (isset($_GET['get_side'])){
        echo $game -> get_side($current_user -> get_id());
        if ($global_mode) echo "$";
    }

    if (isset($_GET['get_cond'])){
        echo $game -> get_cond();
        if ($global_mode) echo "$";
    }

    if (isset($_GET['make_step'])){
        $pos = $_GET['pos'];
        if ($pos >= 0) $game -> make_step($pos, $current_user -> get_id());
    }

    if (isset($_GET['get_winner'])){
        echo $game -> get_winner();
        if ($global_mode) echo "$";
    }

    if (isset($_GET['finish_game'])){
        $game -> end_session();
    }

    if (isset($_GET['opponent_name'])){
        $opponent_id = $game -> get_user1_id();
        if ($opponent_id === $current_user -> get_id()) $opponent_id = $game ->get_user2_id();

        echo Online_Player::get_player_by_id($opponent_id) -> get_name();
        if ($global_mode) echo "$";
    }

    if (isset($_GET['my_name'])){
        $opponent_id = $game -> get_user1_id();
        if ($opponent_id !== $current_user -> get_id()) $opponent_id = $game ->get_user2_id();

        echo Online_Player::get_player_by_id($opponent_id) -> get_name();
        if ($global_mode) echo "$";
    }

    if (isset($_GET['give_up'])){
        $game -> give_up($current_user -> get_id());
    }

    if (isset($_GET['go_out'])){
        if ($game -> get_count() === 1) $game -> delete_session();
    }

    if (isset($_GET['rotate'])){
        $quarter = $_GET['quarter'];
        $dir = $_GET['dir'];
        $game -> make_rotate($quarter, $dir, $current_user -> get_id());
    }

    if (isset($_GET['get_rotate'])){
        echo $game -> get_rotate();
        if ($global_mode) echo "$";
    }

    if (isset($_GET['get_rotate_complete'])){
        echo $game -> get_rotate_complete();
        if ($global_mode) echo "$";
    }
}
