<?php
$dir = substr(__DIR__, 0, -5);
require_once $dir.'/model/default_model.php';

class Matrix_Point
{
    public int $row_index;
    public int $column_index;

    public function __construct($row_index, $column_index)
    {
        if ($row_index < 0 || $column_index < 0) return;
        $this->row_index = $row_index;
        $this->column_index = $column_index;
    }
}


class Matrix
{
    private int $row_count;
    private int $column_count;
    public array $table;

    public function __construct()
    {
        $arg_count = func_num_args();
        $arg_array = func_get_args();
        if ($arg_count === 1) {
            $this->table = $arg_array[0];
            $this->row_count = count($this->table);
            $this->column_count = count($this->table[0]);
        }
        if ($arg_count === 2) {
            $this->row_count = $arg_array[0];
            $this->column_count = $arg_array[1];
        }
    }

    public function get_value(Matrix_Point $point)
    {
        return $this->table[$point->row_index][$point->column_index];
    }

    public function get_table(){
        return $this->table;
    }

    public function get_sub_matrix(Matrix_Point $point1, Matrix_Point $point2)
    {
        $point1->row_index = min($point1->row_index, $point2->row_index);
        $point1->column_index = min($point1->column_index, $point2->column_index);

        $point2->row_index = max($point1->row_index, $point2->row_index);
        $point2->column_index = max($point1->column_index, $point2->column_index);

        $result_table = [];
        for ($i = $point1->row_index; $i <= $point2->row_index; $i++) {
            $row = [];
            for ($j = $point1->column_index; $j <= $point2->column_index; $j++) {
                $row[] = $this->table[$i][$j];
            }
            $result_table[] = $row;
        }

        return new Matrix($result_table);
    }

    public function set_sub_matrix(Matrix_Point $point1, Matrix_Point $point2, Matrix $sub_matrix): void
    {
        $point1->row_index = min($point1->row_index, $point2->row_index);
        $point1->column_index = min($point1->column_index, $point2->column_index);

        $point2->row_index = max($point1->row_index, $point2->row_index);
        $point2->column_index = max($point1->column_index, $point2->column_index);

        for ($i = $point1->row_index; $i <= $point2->row_index; $i++) {
            for ($j = $point1->column_index; $j <= $point2->column_index; $j++) {
                $this->table[$i][$j] = $sub_matrix->get_value(new Matrix_Point($i - $point1->row_index, $j - $point1->column_index));
            }
        }
    }

    public function make_transposed()
    {
        $new_table = [];
        for ($i = 0; $i < $this->column_count; $i++) {
            $row = [];
            for ($j = 0; $j < $this->row_count; $j++) {
                $row[] = $this->table[$j][$i];
            }
            $new_table[] = $row;
        }

        $this->table = $new_table;
    }

    public function reverse_column(){
        $new_table = [];
        for ($i = 0; $i < $this->row_count; $i ++){
            $row = [];
            for ($j = 0; $j < $this->column_count; $j ++){
                $row[] = $this->table[$i][$this->column_count - $j - 1];
            }
            $new_table[] = $row;
        }
        $this->table = $new_table;
    }

    public function rotate_right(){
        $this->make_transposed();
        $this->reverse_column();
    }

    public function rotate_left(){
        for ($i = 0; $i < 3; $i ++) $this->rotate_right();
    }

    public function watch(){
        for  ($i = 0; $i < $this->row_count; $i ++){
            for ($j = 0; $j < $this->column_count; $j ++){
                echo $this->table[$i][$j].' ';
            }
            echo '<br>';
        }
    }

    public function rotate_right_sub_matrix(Matrix_Point $point1, Matrix_Point $point2){
        $sub_matrix = $this->get_sub_matrix($point1, $point2);
        $sub_matrix -> rotate_right();
        $this->set_sub_matrix($point1, $point2, $sub_matrix);
    }

    public function rotate_left_sub_matrix(Matrix_Point $point1, Matrix_Point $point2){
        $sub_matrix = $this->get_sub_matrix($point1, $point2);
        $sub_matrix -> rotate_left();
        $this->set_sub_matrix($point1, $point2, $sub_matrix);
    }

    public function get_table_str(){
        $str = '';
        for ($i = 0; $i < $this->row_count; $i ++){
            for ($j = 0; $j < $this->column_count; $j ++){
                $str .= (string)$this->table[$i][$j];
            }
        }
        return $str;
    }

}