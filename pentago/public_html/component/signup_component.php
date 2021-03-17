<?php
require_once '../component/default_component.php';
require_once '../model/Online_Player.php';

require_once '../phpmailer/PHPMailer.php';
require_once '../phpmailer/SMTP.php';
require_once '../phpmailer/Exception.php';

if (isset($_POST['signup_submit'])){
    $new_user_name = $_POST['signup_username'];
    $new_user_password = $_POST['signup_password'];
    $new_user_mail = $_POST['signup_mail'];

    if (Online_Player::is_mail_vacant($new_user_mail) && Online_Player::is_name_vacant($new_user_name)){
        $current_user = new Online_Player($new_user_name, $new_user_password, $new_user_mail, false);
        $current_user -> set_is_activate(true);

        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail -> isSMTP();
        $mail -> CharSet = 'UTF-8';
        $mail->SMTPAuth=true;

        $mail->Host='smtp.mail.ru';
        $mail->Username='play_pentago@mail.ru';
        $mail->Password='Mama2002Mama2002';
        $mail->SMTPSecure='ssl';
        $mail->Port=465;

        $mail->setFrom('play_pentago@mail.ru');
        $mail->addAddress($new_user_mail);
        $mail -> isHTML(true);
        $mail->Subject = 'Pentago Sign Up';
//        $mail->Body = 'You have signed up in Pentago Play. Please go here to activate your account. <br> <a href="'.$path.'/activate?key='.$current_user->get_activation_key().'">ACTIVATE!</a><br>';
        $mail->Body = 'Thanks for registering on pentago';
        $mail->AltBody='';
        $mail->isHTML(true);

        $mail -> send();
    }
    else {
        echo 'aaaaaaaaaaa';
        header("Location: signup?error");
    }


}

?>