<?php
require_once 'Matrix.php';

$table = array(array(1, 2, 3), array(4, 5, 6), array(7, 8, 9));
echo '<pre>'; var_dump($table); echo '</pre>';

$matrix = new Matrix($table);

$matrix -> watch();
echo '<br>';

$left_corner = new Matrix_Point(0, 0);
$right_corner = new Matrix_Point(1, 1);

$matrix -> rotate_right_sub_matrix($left_corner, $right_corner);
$matrix -> watch();
$matrix -> rotate_left_sub_matrix($left_corner, $right_corner);
$matrix -> watch();