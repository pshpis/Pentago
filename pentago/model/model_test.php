<?php
require_once '../component/default_component.php';
require_once '../model/Online_Player.php';
require_once '../model/Pentago_Session.php';
require_once '../component/auto_login_component.php';
//
//$game = new Pentago_Session($current_user->get_id());
//
//$game -> update_player_statistic(0.5);

echo '<pre>';

//var_dump(Online_Player::get_player_by_id(27));
//
//$user1 = Online_Player::get_player_by_id(27);
//$user2 = Online_Player::get_player_by_id(28);
//
//$winner = 1;
//
//if ($winner == 1){
//    $user1 -> set_won_games($user1 -> get_won_games() + 1);
//    $user2 -> set_lose_games($user2 -> get_lose_games() + 1);
////    $this->update_player_statistic(1);
//}
//if ($winner == 2){
//    $user2 -> set_won_games($user2 -> get_won_games() + 1);
//    $user1 -> set_lose_games($user1 -> get_lose_games() + 1);
////    $this->update_player_statistic(0);
//}
//if ($winner == 3){
//    $user1 -> set_draw_games($user1 -> get_draw_games() + 1);
//    $user2 -> set_draw_games($user2 -> get_draw_games() + 1);
////    $this->update_player_statistic(0.5);
//}


require_once '../phpmailer/PHPMailer.php';
require_once '../phpmailer/SMTP.php';
require_once '../phpmailer/Exception.php';
//require 'vendor/autoload.php';
//$mail = new \PHPMailer\PHPMailer\PHPMailer();
//$mail -> isSMTP();
//$mail -> CharSet = 'UTF-8';
//$mail->SMTPAuth=true;
//
//$mail->Host='smtp.mail.ru';
//$mail->Username='p.shpis@inbox.ru';
//$mail->Password='Mama2002Mama2002';
//$mail->SMTPSecure='ssl';
//$mail->Port=465;
//
//$mail->setFrom('p.shpis@inbox.ru');
//$mail->addAddress('p.shpis@inbox.ru');
//$mail->Subject = 'Заявка';
//$mail->Body = 'Спасибо, что ты работаешь';
//$mail->AltBody='';
//$mail->isHTML(true);
//
//$mail -> send();

 var_dump(User::is_name_vacant('pshpis'));
echo '</pre>';