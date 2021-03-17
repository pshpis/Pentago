<?php
$dir = substr(__DIR__, 0, -5);
// path without model in the end

require_once $dir.'/model/default_model.php';
require_once $dir.'/model/Online_Player.php';
require $dir.'/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

// create a log channel
$logger = new Logger('Game Logger');
$logger->pushHandler(new StreamHandler($dir.'/logs/game.log', Logger::DEBUG));
$logger->pushHandler(new FirePHPHandler());

class Online_Game_Session
{
    protected int $id;
    protected int $user1_id;
    protected int $user2_id;
    protected int $count;
    protected int $auto_winner;
    protected int $time1;
    protected int $time2;
    protected int $last_time;
    protected int $start_time;
    protected bool $game_session_finish = false;

    static public int $game_time = 120;

    public static function is_game_session(int $user_id): bool {
        $mysql = openDB();
        $game_row = $mysql -> query("SELECT * FROM `games` WHERE `user1_id` = '".$user_id."' OR `user2_id` = '".$user_id."'");
        $game_row = $game_row -> fetch_assoc();
        $mysql -> close();
        return !is_null($game_row);
    }

    /**
     * @return bool
     */
    public function is_game_session_finish(): bool
    {
        return $this->game_session_finish;
    }

    //getters begin

    /**
     * @return int|mixed
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return int|mixed
     */
    public function get_user1_id()
    {
        return $this->user1_id;
    }

    public function get_opponent_id($user_id): int{
        if ($this -> user1_id == $user_id) return $this->user2_id;
        if ($this->user2_id == $user_id) return $this->user1_id;
        return -1;
    }

    /**
     * @return int|mixed
     */
    public function get_user2_id()
    {
        return $this->user2_id;
    }

    /**
     * @return int|mixed
     */
    public function get_count()
    {
        return $this->count;
    }

    /**
     * @return int|mixed
     */
    public function get_auto_winner()
    {
        return $this->auto_winner;
    }

    public function get_side(int $user_id): int{
        if ($this->user1_id == $user_id) return 1;
        if ($this->user2_id == $user_id) return 2;
    }

    /**
     * @return int
     */
    public function get_time1(): int
    {
        return $this->time1;
    }

    /**
     * @return int
     */
    public function get_last_time(): int
    {
        return $this->last_time;
    }

    /**
     * @return int
     */
    public function get_time2(): int
    {
        return $this->time2;
    }

    /**
     * @return int
     */
    public function get_start_time(): int
    {
        return $this->start_time;
    }



    //getters end

    //setters begin

    /**
     * @param int|mixed $auto_winner
     */
    public function set_auto_winner($auto_winner): void
    {
        if ($auto_winner > 2) return;
        $this->auto_winner = $auto_winner;

        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `auto_winner` = '".$auto_winner."' WHERE `id` = '".$this->id."'");
        $mysql -> close();

    }

    /**
     * @param int|mixed $count
     */
    public function set_count(int $count): void
    {
        if ($count < 0 || $count > 2) return;

        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `count` = '".$count."' WHERE `id` = '".$this->id."'");
        $mysql -> close();

        $this->count = $count;
    }

    public function set_players_time(int $time): void{
        if ($time <= 0) return;

        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `time1` = '".$time."', `time2` = '".$time."'  WHERE `id` = '".$this->id."'");
        $mysql -> close();

        $this->time1 = $time;
        $this->time2 = $time;
    }

    /**
     * @param int|mixed $time1
     */
    public function set_time1($time1): void
    {
        $this->time1 = $time1;
        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `time1` = '".$time1."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @param int|mixed $time2
     */
    public function set_time2($time2): void
    {
        $this->time2 = $time2;
        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `time2` = '".$time2."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @param int $last_time
     */
    public function set_last_time(int $last_time): void
    {
        $this->last_time = $last_time;

        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `last_time` = '".$last_time."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @param int $start_time
     */
    public function set_start_time(int $start_time): void
    {
        $this->start_time = $start_time;
        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `start_time` = '".$start_time."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    //setters end

    public function get_time(int $user_id){
        if ($this->user1_id === $user_id) return $this->time1;
        if ($this->user2_id === $user_id) return $this->time2;
    }

    public function __construct(int $user_id){
        $this->start_time = self::$game_time;
        $mysql = openDB();

        $game_row = $mysql -> query("SELECT * FROM `games` WHERE `user1_id` = '".$user_id."' OR `user2_id` = '".$user_id."'");
        $game_row = $game_row -> fetch_assoc();

        if (!is_null($game_row)){ // player has already find game
            $this -> user1_id = $game_row['user1_id'];
            $this -> user2_id = $game_row['user2_id'];
            $this -> id = $game_row['id'];
            $this -> count = $game_row['count'];
            $this->auto_winner = $game_row['auto_winner'];

            $this->time1 = $game_row['time1'];
            $this->time2 = $game_row['time2'];
            $this->last_time = $game_row['last_time'];
            $this->start_time = $game_row['start_time'];

            $mysql -> close();
            return;
        }

        $game_row = $mysql -> query("SELECT * FROM `games` WHERE `count` = 1 AND `start_time` = '".$this->start_time."'");
        $game_row = $game_row -> fetch_assoc();

        if (is_null($game_row)){ // there is no opponent
            $success = $mysql -> query("INSERT INTO `games` (`user1_id`, `user2_id`, `count`, `auto_winner`, `start_time`)
                                                VALUES ('".$user_id."', -1, 1, -1, '".$this->start_time."')");

            $this->user1_id = $user_id;
            $this->user2_id = -1;
            $this->count = 1;
            $this -> auto_winner = -1;

            $game_row = $mysql -> query("SELECT * FROM `games` WHERE `user1_id` = '".$user_id."'");
            $game_row = $game_row -> fetch_assoc();
            $this->id = $game_row['id'];

            $this -> set_players_time(self::$game_time);
        }
        else { // game is full
            $mysql -> query("UPDATE `games` SET `user2_id`='$user_id', `count`='2' WHERE `id` = '".$game_row['id']."'");

            $this->user1_id = $game_row['user1_id'];
            $this->user2_id = $user_id;
            $this->count = 2;
            $this->id = $game_row['id'];
            $this->auto_winner = -1;
            $this -> time2 = $game_row['time2'];
            $this->time1 = $game_row['time1'];

            $this -> set_last_time(time());
        }

        $mysql -> close();
    }

    public function data_update(): void
    {
        $mysql = openDB();
        $game_row = $mysql->query("SELECT * FROM `games` WHERE `id` = '" . $this->id . "'");
        $game_row = $game_row->fetch_assoc();
        if (!is_null($game_row)) {
            $this->user1_id = $game_row['user1_id'];
            $this->user2_id = $game_row['user2_id'];
            $this->count = $game_row['count'];
            $this->auto_winner = $game_row['auto_winner'];
        }
        $mysql->close();
    }

    public function give_up(int $user_id): void{
        if ($this->user1_id == $user_id) $this->set_auto_winner(2);
        if ($this->user2_id == $user_id) $this->set_auto_winner(1);
    }

    function update_player_statistic($game_result){
        $rating1 = Online_Player::get_player_by_id($this->user1_id) -> get_rating();
        $rating2 = Online_Player::get_player_by_id($this->user2_id) -> get_rating();

        $wait_result1 = 1 / (1 + 10 ** (($rating2 - $rating1)/400));
        $k = 40;
        if ($rating1 > 2300) $k = 20;
        if ($rating1 > 2400) $k = 10;
        $new_rating1 = floor($rating1 + $k * ($game_result - $wait_result1));

        $game_result = 1 - $game_result;

        $wait_result2 = 1 / (1 + 10 ** (($rating1 - $rating2)/400));
        $k = 40;
        if ($rating2 > 2300) $k = 20;
        if ($rating2 > 2400) $k = 10;
        $new_rating2 = floor($rating2 + $k * ($game_result - $wait_result2));

        Online_Player::get_player_by_id($this->user1_id) -> set_rating($new_rating1);
        Online_Player::get_player_by_id($this->user2_id) -> set_rating($new_rating2);
    }

    public function end_session(): void {
//        if ($this -> get_winner() <= 0 && $this->auto_winner == -1) return;
//        if ($this->auto_winner != -1){
//            $this -> set_count(1);
//        }
//        $this -> set_count($this->count - 1);
        if ($this->game_session_finish) return;
        $this -> set_count(0);
        if ($this->get_count() == 0){
            $winner = $this->get_winner();
            $user1 = Online_Player::get_player_by_id($this->user1_id);
            $user2 = Online_Player::get_player_by_id($this->user2_id);

            if ($winner == 1){
                $user1 -> set_won_games($user1 -> get_won_games() + 1);
                $user2 -> set_lose_games($user2 -> get_lose_games() + 1);
                $this->update_player_statistic(1);
            }
            if ($winner == 2){
                $user2 -> set_won_games($user2 -> get_won_games() + 1);
                $user1 -> set_lose_games($user1 -> get_lose_games() + 1);
                $this->update_player_statistic(0);
            }
            if ($winner == 3){
                $user1 -> set_draw_games($user1 -> get_draw_games() + 1);
                $user2 -> set_draw_games($user2 -> get_draw_games() + 1);
                $this->update_player_statistic(0.5);
            }

            $this->delete_session();
            $this -> game_session_finish = true;
        }
//        $this->delete_session();
    }

    public function delete_session(): void{
        $mysql = openDB();
        $mysql -> query("DELETE FROM `games` WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }
}