<?php
$dir = substr(__DIR__, 0, -10);

require $dir.'/vendor/autoload.php';
require_once $dir.'/model/default_model.php';
require_once $dir.'/component/default_component.php';
require_once $dir.'/model/Pentago_Session.php';
require_once $dir.'/model/Online_Player.php';


$worker = new Workerman\Worker("websocket://a0446139.xsph.ru:8090/controller/websocket_controller.php");
$worker -> count = 4;

$users_connections = array();

$worker -> onMessage = function ($connection, $data) use (&$game, &$current_user, &$users_connections, &$worker){
    $data = json_decode($data,true);
    if (is_null($data)) return;
    if (!$data) return;
    if (!isset($data["user_id"])) return;

    $user_id = $data["user_id"];
    if ($user_id === -1) return;
    $users_connections[$user_id] = $connection;

    if (isset($data['make_game']) && $data['make_game']){
        $start_time = $data['start_time'];
        Pentago_Session::$game_time = $start_time;
        $game = new Pentago_Session($user_id);
        $send_data = array(
            "players_count" => $game -> get_count(),
            "cond" => $game -> get_cond(),
            "rotate" => $game -> get_rotate(),
            "step_complete" => $game -> get_step_complete(),
            "rotate_complete" => $game ->  get_rotate_complete(),
        );

        if ($game -> get_count() === 2){
            $send_data["side"] = 1;
            $send_data["my_time"] = $game -> get_real_time($game -> get_user1_id());
            $send_data["opponent_id"] = Online_Player::get_player_by_id($game -> get_user2_id()) -> get_id();
            $send_data["opponent_name"] = Online_Player::get_player_by_id($game -> get_user2_id()) -> get_name();
            $send_data["opponent_rating"] = Online_Player::get_player_by_id($game -> get_user2_id()) -> get_rating();
            $send_data["opponent_time"] = $game -> get_real_time($game -> get_user2_id());
            $users_connections[$game -> get_user1_id()] -> send(json_encode($send_data));

            $send_data["side"] = 2;
            $send_data["my_time"] =  $game -> get_real_time($game -> get_user2_id());
            $send_data["opponent_id"] = Online_Player::get_player_by_id($game -> get_user1_id()) -> get_id();
            $send_data["opponent_name"] = Online_Player::get_player_by_id($game -> get_user1_id()) -> get_name();
            $send_data["opponent_rating"] = Online_Player::get_player_by_id($game -> get_user1_id()) -> get_rating();
            $send_data["opponent_time"] = $game -> get_real_time($game -> get_user1_id());
            $users_connections[$game -> get_user2_id()] -> send(json_encode($send_data));
        }

        return;
    }

    if (isset($data['end_search']) && $data['end_search']){
        $game -> delete_session();
        return;
    }

    if (isset($data['opponent_leave']) && $data['opponent_leave']){
        $game -> give_up($game -> get_opponent_id($user_id));

        if ($game -> get_winner() !== 0){
            $new_data = array(
                "winner" => $game -> get_winner()
            );

            $connection -> send(json_encode($new_data));

            $game -> end_session();
        }

        return;
    }

    if (isset($data['give_up']) && $data['give_up']){
        $opponent_connection = $users_connections[$game->get_opponent_id($user_id)];
        $game -> give_up($user_id);

        if ($game -> get_winner() !== 0){
            $new_data = array(
                "winner" => $game -> get_winner()
            );

            $opponent_connection -> send(json_encode($new_data));
            $connection -> send(json_encode($new_data));

            $game -> end_session();
        }

        return;
    }

    $step = $data['step'];
    $rotate_quarter = $data['rotate_quarter'];
    $rotate_dir = $data['rotate_dir'];

    if (!Pentago_Session::is_game_session($user_id)) return;
    $game = new Pentago_Session($user_id);

    if ($step !== -1){
        if ($game -> get_time($user_id) < 0){
            $game -> give_up($user_id);
            $game -> end_session();
        }
        if ($game -> make_step($step, $user_id)){
            $opponent_connection = $users_connections[$game->get_opponent_id($user_id)];
            $new_data = array(
                "step" => $step,
                "rotate_quarter" => -1,
                "rotate_dir" => -2,
            );

            $opponent_connection -> send(json_encode($new_data));

            if ($game -> get_winner() !== 0){
                $new_data = array(
                    "winner" => $game -> get_winner()
                );

                $opponent_connection -> send(json_encode($new_data));
                $connection -> send(json_encode($new_data));

                $game -> end_session();
            }
        }
    }

    if ($rotate_quarter !== -1 && $rotate_dir !== -2){
        if ($game -> get_time($user_id) < 0){
            $game -> give_up($user_id);
            $game -> end_session();
        }
        if ($game -> make_rotate($rotate_quarter, $rotate_dir, $user_id)){
            $opponent_connection = $users_connections[$game->get_opponent_id($user_id)];

            $new_data = array(
                "step" => -1,
                "rotate_quarter" => $rotate_quarter,
                "rotate_dir" => $rotate_dir,
                "opponent_time" => $game -> get_time($user_id),
            );

            $opponent_connection -> send(json_encode($new_data));
            $connection -> send(json_encode(array(
                "my_time" => $game -> get_time($user_id),
            )));

            if ($game -> get_winner() !== 0){
                $new_data = array(
                    "winner" => $game -> get_winner()
                );

                $opponent_connection -> send(json_encode($new_data));
                $connection -> send(json_encode($new_data));

                $game -> end_session();
            }
        }
    }
};

Workerman\Worker::runAll();