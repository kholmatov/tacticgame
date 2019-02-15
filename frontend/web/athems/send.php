<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 02/06/15
 * Time: 15:35
 */

$now="12334";
$quest_title="Quest Title";
$email="test@mail.com";
$phone="89250807089";

$emailHtml = "
            <table width='800' border='1' bordercolor='#B6B6B6' align='center' cellspacing='0' cellpadding='0' style='border:1px solid #B6B6B6; border-collapse:collapse; background-color:#FFF; margin-top:15px; margin-bottom:10px;'>
            <tr><td colspan='3' style='text-align:center; background-color:#e7deab; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0d0d0d; font-weight:bold; padding:15px;'>Тестировка :&nbsp;&nbsp;&nbsp;[&nbsp;".$now."&nbsp;]</td>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>Quest:</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$quest_title."</td>
            </tr>

            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>Email:</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$email."</td>
            </tr>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>Phone:</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$phone."</td>
            </tr>
            </table>
         ";


//        Yii::$app->mailer->compose()
//            ->setFrom('taticgame@yandex.ru')
//            ->setTo('tacticgame@yandex.ru')
//            ->setSubject('Message subject')
//            ->setTextBody('Plain text content')
//            ->setHtmlBody('<b>HTML content</b>')
//            ->send();

// пример использования
//require_once "SendMailSmtpClass.php"; // подключаем класс

//$mailSMTP = new SendMailSmtpClass('tacticgame.es@gmail.com', 'Alan870629', 'ssl://smtp.gmail.com', 'Evgeniy', 465); // создаем экземпляр класса
// $mailSMTP = new SendMailSmtpClass('логин', 'пароль', 'хост', 'имя отправителя');
//$mailSMTP = new SendMailSmtpClass('taticgame@yandex.ru', 'Alan870629', 'ssl://smtp.yandex.ru', 'Evgeniy', 465);
// $mailSMTP = new SendMailSmtpClass('логин', 'пароль', 'хост', 'имя отправителя');

// заголовок письма
$to = "taticgame@yandex.ru";
$headers     = "MIME-Version: 1.0\r\n";
$headers    .= "Content-type: text/html; charset=utf-8\r\n";
$headers    .= "From: <taticgame@yandex.ru>\r\n";

if(mail($to,'test',$emailHtml,$headers))
    echo "Email Send Ok";
else
    echo "Email Error";

