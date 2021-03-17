<?php
$dir = substr(__DIR__, 0, -5);
require_once $dir.'/model/default_model.php';
require_once $dir.'/model/User.php';

class Online_Player extends User{
    private int $won_games;
    private int $lose_games;
    private int $draw_games;
    private int $rating;

    static function get_player_by_id($id){
        $mysql = openDB();
        $user_row = $mysql -> query("SELECT * FROM `users` WHERE `id` = '".$id."'");
        if (is_null($user_row) || !$user_row){
            $mysql -> close();
            return false;
        }
        $user_row = $user_row -> fetch_assoc();
        if (is_null($user_row) || !$user_row){
            $mysql -> close();
            return false;
        }
        $mysql -> close();


        $user = new Online_Player();
        $user -> id = $user_row['id'];
        $user -> mail = $user_row['mail'];
        $user -> password_hash = $user_row['password'];
        $user -> name = $user_row['name'];
        $user -> is_activate = $user_row['is_activate'];
        $user -> activation_key = $user_row['activation_key'];
        $user -> lose_games = $user_row['lose_games'];
        $user -> won_games = $user_row['won_games'];
        $user -> draw_games = $user_row['draw_games'];
        $user -> rating = $user_row['rating'];


        return $user;
    }

    /**
     * @param int $lose_games
     */
    public function set_lose_games(int $lose_games): void
    {
        $this->lose_games = $lose_games;
        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `lose_games` = '".$lose_games."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @param int $won_games
     */
    public function set_won_games(int $won_games): void
    {
        $this->won_games = $won_games;
        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `won_games` = '".$won_games."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @param int $draw_games
     */
    public function set_draw_games(int $draw_games): void
    {
        $this->draw_games = $draw_games;

        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `draw_games` = '".$draw_games."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @param int $rating
     */
    public function set_rating(int $rating): void
    {
        $this->rating = $rating;
        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `rating` = '".$rating."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @return int
     */
    public function get_lose_games(): int
    {
        return $this->lose_games;
    }

    /**
     * @return int
     */
    public function get_won_games(): int
    {
        return $this->won_games;
    }

    /**
     * @return int
     */
    public function get_draw_games(): int
    {
        return $this->draw_games;
    }

    /**
     * @return int
     */
    public function get_rating(): int
    {
        return $this->rating;
    }

    public function get_games_count() : int {
        return $this -> won_games + $this->draw_games + $this->lose_games;
    }

    public function add_to_db() : array{
        $mysql = openDB();
        $mysql -> query("INSERT INTO `".self::$table_name."` (`id`, `name`, `password`, `won_games`, `lose_games`, `draw_games`, `mail`, `activation_key`) 
                                VALUES (NULL, '".$this -> name."', '".$this->password_hash."', '".$this->won_games."', '".$this->lose_games."', '".$this->draw_games."', '".$this->mail."', '".$this->activation_key."')");
        $user_row = $mysql -> query("SELECT * FROM `".self::$table_name."` WHERE `name` = '".$this -> name."'");
        $user_row = $user_row -> fetch_assoc();
        $this -> id = $user_row['id'];
        $mysql -> close();
        return $user_row;
    }

    public function __construct(string $name = 'defaultdefaultdefaultdefault', string $password = 'defaultdefaultdefaultdefault', string $mail = 'defaultdefaultdefaultdefault', bool $is_in_db = true){
        if (func_num_args() < 2){
            $this->id = -1;
            $this->password_hash = '';
            $this->is_activate = false;
            $this->activation_key = '';
            $this->name = '';
            $this->mail = '';
            $this->won_games = 0;
            $this->lose_games = 0;
            $this->draw_games = 0;
            $this->rating = 800;
            return;
        }

        $this -> name = $name;
        $this -> password_hash = password_hash($password, PASSWORD_BCRYPT);
        $this -> mail = $mail;
        $this->activation_key = password_hash($name, PASSWORD_BCRYPT);

        if (!$is_in_db){
            $this->won_games = 0;
            $this->lose_games = 0;
            $this->draw_games = 0;
            $this -> rating = 800;
            $this->add_to_db();

            $this -> set_cookie($password);
        }
        else {
            if (!$this->check_in_db($password)) $this->id = -1;
            else {
                $mysql = openDB();
                $user_row = $mysql -> query("SELECT * FROM `".self::$table_name."` WHERE `name` = '".$this -> name."'");
                $user_row = $user_row -> fetch_assoc();

                $this -> id = (int)$user_row['id'];
                $this->mail = (string)$user_row['mail'];
                $this -> won_games = (int) $user_row['won_games'];
                $this -> lose_games = (int) $user_row['lose_games'];
                $this->activation_key = (string) $user_row['activation_key'];
                $this -> is_activate = (bool)$user_row['is_activate'];
                $this->draw_games = (int) $user_row['draw_games'];
                $this->rating = (int)$user_row['rating'];

                $this -> set_cookie($password);
                $mysql -> close();
            }
        }
    }

}

function get_player_from_cookie(){
    if (!isset($_COOKIE['username']) || !isset($_COOKIE['password'])) return false;
    $name = $_COOKIE['username'];
    $password = $_COOKIE['password'];

    $user = new Online_Player($name, $password, '', true);
    if ($user -> get_id() === -1) return false;
    else return $user;
}



