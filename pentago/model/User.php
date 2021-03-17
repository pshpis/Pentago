<?php
$dir = substr(__DIR__, 0, -5);
require_once $dir.'/model/default_model.php';

class User{
    protected int $id;
    protected string $password_hash;
    protected string $name;
    protected string $mail;
    protected bool $is_activate;
    protected string $activation_key;

    public static string $table_name = "users";

    public static function is_name_vacant($name): bool{
        $mysql = openDB();
        $user_row = $mysql -> query("SELECT * FROM `".self::$table_name."` WHERE `name` = '".$name."'");
        $mysql -> close();

        if ($user_row === false) return true;
        $user_row = $user_row -> fetch_assoc();
        if (is_null($user_row)) return true;
        return false;
    }

    public static function is_mail_vacant($mail): bool{
        $mysql = openDB();
        $user_row = $mysql -> query("SELECT * FROM `".self::$table_name."` WHERE `mail` = '".$mail."'");
        $mysql -> close();

        if ($user_row === false) return true;
        $user_row = $user_row -> fetch_assoc();
        if (is_null($user_row)) return true;
        return false;
    }

    /**
     * @return string
     */
    public function get_activation_key(): string
    {
        return $this->activation_key;
    }

    /**
     * @param string $activation_key
     */
    public function set_activation_key(string $activation_key): void
    {
        $this->activation_key = $activation_key;

        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `activation_key` = '".$activation_key."' WHERE `id` = '".$this->id."'");
        $mysql ->  close();
    }

    /**
     * @return bool
     */
    public function is_activate(): bool
    {
        return $this->is_activate;
    }

    /**
     * @param bool $is_activate
     */
    public function set_is_activate(bool $is_activate): void
    {
        $this->is_activate = $is_activate;

        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `is_activate` = '".$is_activate."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @return string
     */
    public function get_mail(): string
    {
        return $this->mail;
    }

    /**
     * @return int
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function set_id(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return false|string|null
     */
    public function get_password_hash()
    {
        return $this->password_hash;
    }

    /**
     * @param false|string|null $password_hash
     */
    public function set_password_hash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `password` = '".$this->password_hash."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    /**
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function set_name(string $name): void
    {
        $this->name = $name;
        $mysql = openDB();
        $mysql -> query("UPDATE `".self::$table_name."` SET `name` = '".$this->name."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }

    public function add_to_db() : array{
        $mysql = openDB();
        $mysql -> query("INSERT INTO `".self::$table_name."` (`id`, `name`, `password`, `mail`, `activation_key`) 
                                VALUES (NULL, '".$this -> name."', '".$this->password_hash."', '".$this->mail."', '".$this->activation_key."')");
        $user_row = $mysql -> query("SELECT * FROM `".self::$table_name."` WHERE `name` = '".$this -> name."'");
        $user_row = $user_row -> fetch_assoc();
        $this -> id = $user_row['id'];
        $mysql -> close();
        return $user_row;
    }

    public function __construct(string $name = 'defaultdefaultdefaultdefault', string $password = 'defaultdefaultdefaultdefault',string $mail, bool $is_in_db = true){
        if (func_num_args() < 2){
            $this->id = -1;
            $this->is_activate = false;
            $this->activation_key = '';
            $this->password_hash = '';
            $this->name = '';
            $this->mail = '';
            return;
        }

        $this -> name = $name;
        $this -> password_hash = password_hash($password, PASSWORD_BCRYPT);
        $this->mail = $mail;
        $this->activation_key = password_hash($name, PASSWORD_BCRYPT);
        $this->is_activate = false;

        if (!$is_in_db){
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
                $this->activation_key = (string)$user_row['activation_key'];
                $this->is_activate = (bool)$user_row['is_activate'];
                $this -> mail = $user_row['mail'];

                $this -> set_cookie($password);
                $mysql -> close();
            }
        }
    }

    public function check_in_db(string $password) : bool {
        $mysql = openDB();
        $user_row = $mysql -> query("SELECT * FROM `".self::$table_name."` WHERE `name` = '" .$this -> name. "'");
        if (is_null($user_row)) return false;
        $user_row = $user_row -> fetch_assoc();
        if (is_null($user_row)) return false;
        $mysql -> close();
        return password_verify($password, (string)$user_row['password']);
    }

    public function set_cookie($password) : void {
        setcookie('username', $this -> name, '', '/');
        setcookie('password', $password, '', '/');
    }

    public function delete_cookie() : void {
        setcookie('username', $this -> name, time() - 3600, '/');
        setcookie('password', $this -> password_hash, time() - 3600, '/');
    }
}