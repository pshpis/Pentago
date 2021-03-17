<?php
$dir = substr(__DIR__, 0, -5);
//path without model in the end

require_once $dir.'/model/default_model.php';
require_once $dir.'/model/Online_Player.php';
require_once $dir.'/model/Online_Game_Session.php';
require_once $dir.'/model/Matrix.php';

class Pentago_Session extends Online_Game_Session{
    protected $cond; // conditional of the desk 36 symbol
    protected $rotate; // string of 4 symbols. watching rotate of every quarter

    protected $step_complete; // step is moving figure
    protected $rotate_complete; // rotate is changing rotation quarter

    //getters start

    /**
     * @return mixed|string
     */
    public function get_cond()
    {
        return $this->cond;
    }

    /**
     * @return string
     */
    public function get_rotate(): string
    {
        return $this->rotate;
    }

    /**
     * @return int
     */
    public function get_rotate_complete(): int
    {
        return $this->rotate_complete;
    }

    /**
     * @return int
     */
    public function get_step_complete(): int
    {
        return $this->step_complete;
    }


    public function get_full_steps(): int{
        return min($this->step_complete, $this->rotate_complete);
    }

    public function get_real_time($user_id): int{
        if ($this->user1_id === $user_id){
            if ($this->get_full_steps() % 2 == 0) return $this->get_time1() - (time() - $this->last_time);
            else return $this->get_time1();
        }
        else {
            if ($this->get_full_steps() % 2 == 1) return $this->get_time2() - (time() - $this->last_time);
            else return $this->get_time2();
        }
    }
    //getters end

    //setters start

    /**
     * @param mixed|string $rotate
     */
    public function set_rotate($rotate): void
    {
        $this->rotate = $rotate;

        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `rotate` = '".$rotate."' WHERE `id` = '".$this->id."'");
        $mysql -> close();
    }
    //setters end

    /**
     * @param mixed|string $cond
     */
    protected function set_cond($cond): void
    {
        if (strlen($cond) != 36) return;
        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `cond`='".$cond."' WHERE `id` = '".$this->id."'");
        $this->cond = $cond;
        $mysql -> close();
    }

    /**
     * @param int|mixed $step_complete
     */
    public function set_step_complete($step_complete): void
    {
        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `step_complete`='".$step_complete."' WHERE `id` = '".$this->id."'");
        $mysql -> close();

        $this->step_complete = $step_complete;
    }

    public function set_rotate_complete($rotate_complete): void
    {
        $mysql = openDB();
        $mysql -> query("UPDATE `games` SET `rotate_complete`='".$rotate_complete."' WHERE `id` = '".$this->id."'");
        $mysql -> close();

        $this->rotate_complete = $rotate_complete;
    }

    //setters end



    function __construct(int $user_id){
        parent::__construct($user_id);
        $mysql = openDB();

        $game_row = $mysql -> query("SELECT * FROM `games` WHERE `id` = '".$this -> id."'");
        $game_row = $game_row -> fetch_assoc();

        $this -> cond = $game_row['cond'];
        $this->rotate = $game_row['rotate'];

        $this->step_complete = $game_row['step_complete'];
        $this->rotate_complete = $game_row['rotate_complete'];

        $mysql -> close();
    }

    public function make_step($pos, $user_id): bool{
        $side = $this->get_side($user_id);
        if ($this->cond[$pos] !== '0') return false;
        if ($this->step_complete !== $this->rotate_complete) return false;
        if ($this->step_complete % 2 + 1 === $side){
            $this->cond[$pos] = $side;
            $this->set_cond($this->cond);
            $this->set_step_complete($this->step_complete + 1);
            return true;
        }
        else return false;
    }

    //dir = 1 right rotate
    //dir = -1 left rotate
    public function make_rotate(int $quarter, int $dir, int $user_id): bool{
        if ($this->rotate_complete === $this->step_complete) return false;
        $side = $this->get_side($user_id);
        if ($this->rotate_complete % 2 + 1 !== $side) return false;

        $cur_rotate = (int)$this->rotate[$quarter] + $dir;
        if ($cur_rotate == 4) $cur_rotate = 0;
        if ($cur_rotate == -1) $cur_rotate = 3;

        $this->rotate[$quarter] = $cur_rotate;
        $this->set_rotate($this->rotate);
        $this->set_rotate_complete($this->rotate_complete + 1);

        $delta_time = time() - $this->last_time;
        if ($user_id === $this->user1_id){
            $this->set_time1($this->time1 - $delta_time);
        }
        if ($user_id === $this->user2_id){
            $this->set_time2($this->time2 - $delta_time);
        }
        $this->set_last_time(time());
        return true;
    }

    /*
     * returns value:
     * 1 - 1st player is winner
     * 2 - 2nd player is winner
     * 3 - draw position
     */
    public function get_winner(): int{
        if ($this->auto_winner != -1) return $this->auto_winner;

        $table = $this->get_table();

        $winner = 0;
        $count_win1_combination = 0;
        $count_win2_combination = 0;


        //checking horizontal
        $cur_line1 = 0;
        $cur_line2 = 0;
        $max_line1 = 0;
        $max_line2 = 0;

        $is_full = true;

        for ($i = 0; $i < 6; $i ++){
            for ($j = 0; $j < 6; $j ++){
                if ($table[$i][$j] == 0) $is_full = false;
                if ($table[$i][$j] == 1){
                    $cur_line1 ++;
                    $max_line1 = max($max_line1, $cur_line1);
                }
                else {
                    $cur_line1 = 0;
                }

                if ($table[$i][$j] == 2){
                    $cur_line2 ++;
                    $max_line2 = max($max_line2, $cur_line2);
                }
                else {
                    $cur_line2 = 0;
                }

            }

            if ($max_line1 >= 5){
                $winner = 1;
                $count_win1_combination ++;
            }
            if ($max_line2 >= 5){
                $winner = 2;
                $count_win2_combination++;
            }

            $cur_line1 = 0;
            $cur_line2 = 0;
            $max_line1 = 0;
            $max_line2 = 0;
        }

        //checking vertical

        $cur_line1 = 0;
        $cur_line2 = 0;
        $max_line1 = 0;
        $max_line2 = 0;

        for ($j = 0; $j < 6; $j ++){
            for ($i = 0; $i < 6; $i ++){
                if ($table[$i][$j] == 1){
                    $cur_line1 ++;
                    $max_line1 = max($max_line1, $cur_line1);
                }
                else {
                    $cur_line1 = 0;
                }

                if ($table[$i][$j] == 2){
                    $cur_line2 ++;
                    $max_line2 = max($max_line2, $cur_line2);
                }
                else {
                    $cur_line2 = 0;
                }
            }

            if ($max_line1 >= 5){
                $winner = 1;
                $count_win1_combination ++;
            }
            if ($max_line2 >= 5){
                $winner = 2;
                $count_win2_combination ++;
            }
            $cur_line1 = 0;
            $cur_line2 = 0;
            $max_line1 = 0;
            $max_line2 = 0;
        }

        //checking main diagonal and parallel
        $cur_line1 = 0;
        $cur_line2 = 0;
        $max_line1 = 0;
        $max_line2 = 0;

        for ($j = -1; $j < 2; $j ++){
            $row = 0;
            $col = $j;

            if ($j == -1) {
                $col = 0;
                $row = 1;
            }

            while ($row < 6 && $col < 6 && $row >= 0 && $col >= 0){
                if ($table[$row][$col] == 1){
                    $cur_line1 ++;
                    $max_line1 = max($max_line1, $cur_line1);
                }
                else $cur_line1 = 0;

                if ($table[$row][$col] == 2){
                    $cur_line2 ++;
                    $max_line2 = max($max_line2, $cur_line2);
                }
                else $cur_line2 = 0;


                if ($max_line1 >= 5) {
                    $winner = 1;
                    $count_win1_combination ++;
                }
                if ($max_line2 >= 5) {
                    $winner = 2;
                    $count_win2_combination ++;
                }

                $row ++;
                $col ++;
            }
            $cur_line1 = 0;
            $cur_line2 = 0;
            $max_line1 = 0;
            $max_line2 = 0;
        }

        // checking secondary diagonal and parallel

        for ($i = 4; $i <= 6; $i ++){
            $row = 0;
            $col = $i;
            if ($i == 6){
                $row = 1;
                $col = 5;
            }

            while ($row < 6 && $col < 6 && $row >= 0 && $col >= 0){
                if ($table[$row][$col] == 1){
                    $cur_line1 ++;
                    $max_line1 = max($max_line1, $cur_line1);
                }
                else $cur_line1 = 0;

                if ($table[$row][$col] == 2){
                    $cur_line2 ++;
                    $max_line2 = max($max_line2, $cur_line2);
                }
                else $cur_line2 = 0;


                if ($max_line1 >= 5){
                    $count_win1_combination ++;
                    $winner = 1;
                }
                if ($max_line2 >= 5){
                    $count_win2_combination ++;
                    $winner = 2;
                }

                $row ++;
                $col --;
            }
        }

        if ($is_full && $winner === 0) return 3;
        if ($count_win1_combination > 0 && $count_win2_combination > 0) return 3;

        return $winner;
    }

    private function get_table(): Array{
        $table = [];
        for ($i = 0; $i < 6; $i ++){
            $row = [];
            for ($j = 0; $j < 6; $j ++){
                $row[] = (int)$this->cond[$i * 6 + $j];
            }
            $table[] = $row;
        }

        $matrix = new Matrix($table);
        for ($i = 0; $i < 4; $i ++){
            $left_corner = new Matrix_Point(0, 0);
            $right_corner = new Matrix_Point(2, 2);
            if ($i == 1){
                $left_corner = new Matrix_Point(0, 3);
                $right_corner = new Matrix_Point(2, 5);
            }
            if ($i == 2){
                $left_corner = new Matrix_Point(3, 0);
                $right_corner = new Matrix_Point(5, 2);
            }
            if ($i == 3){
                $left_corner = new Matrix_Point(3, 3);
                $right_corner = new Matrix_Point(5, 5);
            }

            for ($j  = 0; $j < (int)$this->rotate[$i]; $j ++){
                $matrix ->rotate_right_sub_matrix($left_corner, $right_corner);
            }
        }

        return $matrix -> get_table();
    }

    //return value is special class value with functions to easy rotate
    private function get_matrix(){
        return new Matrix($this->get_table());
    }
}
