<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require '/usr/share/nginx/html/tacticgame.es/frontend/web/athems/PHPMailer/PHPMailerAutoload.php';

//$mail = new PHPMailer;
//$mail->isSMTP();
//// 0 = off (for production use)
//// 1 = client messages
//// 2 = client and server messages
//$mail->SMTPDebug = 2;
//$mail->Debugoutput = 'html';
//$mail->Host = 'smtp.gmail.com';
//$mail->Port = 587;
//$mail->SMTPSecure = 'tls';
//$mail->SMTPAuth = true;
//$mail->Username = "tacticgame.es@gmail.com";
//$mail->Password = "Alan870629";
//$mail->setFrom('tacticgame.es@gmail.com', 'First Last');
////$mail->addReplyTo('tacticgame@yandex.ru', 'First Last');
//$mail->addAddress('shohin002@mail.ru', 'John Doe');
//
//$mail->Subject = 'PHPMailer GMail SMTP test';
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//$mail->AltBody = 'This is a plain-text message body';
//$mail->addAttachment('images/phpmailer_mini.png');
//if (!$mail->send()) {
//    echo "Mailer Error: " . $mail->ErrorInfo;
//} else {
//    echo "Message sent!";
//}


$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->Username = 'tacticgame.es@gmail.com';
$mail->Password = 'Alan870629';
$mail->SMTPAuth = true;

$mail->From = 'root@tacticgame.es';
$mail->FromName = 'Mohammad Masoudian';
$mail->AddAddress('ekholmatov@gmail.com');
$mail->AddReplyTo('ekhomatov@gmail.com', 'Information');

$mail->IsHTML(true);
$mail->Subject    = "PHPMailer Test Subject via Sendmail, basic";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
$mail->Body    = "Hello";

if(!$mail->Send())
{
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
    echo "Message sent!";
}
