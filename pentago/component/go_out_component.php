<?php
if (isset($_POST['go_out'])){
    $current_user -> delete_cookie();
    $rnd = time();
    header("Location: {$_SERVER[ ' SCRIPT_NAME']}?$rnd" ) ;
}
