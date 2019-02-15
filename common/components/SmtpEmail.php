<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 04/03/16
 * Time: 10:20
 */
namespace common\components;
use yii\base\Component;

class SmtpEmail extends Component {

	const SMTP_SERVER = "smtp-pulse.com"; //SMTP сервер: smtp-pulse.com
	const SMTP_PORT = 2525; //Порт: 	2525 (SSL порт: 465)
	const SMTP_LOGIN = "tacticgame.es@gmail.com";//Логин: 	tacticgame.es@gmail.com
	const SMTP_PASS = "mo59niFYNoHP";//Пароль
	const SMTP_SENDER = "tacticgame.es@gmail.com";//Адрес отправителя: 	tacticgame.es@gmail.com
	const SMTP_DEBUG = true;
	const SMTP_CHARSET = 'utf-8';
	const SMTP_FROM = "Tacticgame.es";
	private $configMe = Array();




	public function Send($mail_to, $subject, $message, $headers='') {

		$this->configMe['smtp_username'] = self::SMTP_LOGIN;  //Смените на имя своего почтового ящика.
		$this->configMe['smtp_port']     = self::SMTP_PORT; // Порт работы. Не меняйте, если не уверены.
		$this->configMe['smtp_host']     = self::SMTP_SERVER;  //сервер для отправки почты(для наших клиентов менять не требуется)
		$this->configMe['smtp_password'] = self::SMTP_PASS;  //Измените пароль
		$this->configMe['smtp_debug']    = self::SMTP_DEBUG;  //Если Вы хотите видеть сообщения ошибок, укажите true вместо false
		$this->configMe['smtp_charset']  = self::SMTP_CHARSET;   //кодировка сообщений. (или UTF-8, итд)

		$this->configMe['smtp_from']     = self::SMTP_FROM; //Ваше имя - или имя Вашего сайта. Будет показывать при прочтении в поле "От кого"


		$SEND =   "Date: ".date("D, d M Y H:i:s") . " UT\r\n";

		$SEND .=   'Subject: =?'.$this->configMe['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";

		if ($headers) $SEND .= $headers."\r\n\r\n";

		else

		{

			$SEND .= "Reply-To: ".$this->configMe['smtp_username']."\r\n";

			$SEND .= "MIME-Version: 1.0\r\n";

			$SEND .= "Content-Type: text/html; charset=\"".$this->configMe['smtp_charset']."\"\r\n";

			$SEND .= "Content-Transfer-Encoding: 8bit\r\n";

			$SEND .= "From: \"".$this->configMe['smtp_from']."\" <".$this->configMe['smtp_username'].">\r\n";

			$SEND .= "To: $mail_to <$mail_to>\r\n";

			$SEND .= "X-Priority: 3\r\n\r\n";

		}

		$SEND .=  $message."\r\n";



		if( !$socket = fsockopen($this->configMe['smtp_host'], $this->configMe['smtp_port'], $errno, $errstr, 30) ) {

			if ($this->configMe['smtp_debug']) {

				$c=$errno."&lt;br&gt;".$errstr;

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			return false;

		}



		if (!$this->server_parse($socket, "220", __LINE__)) return false;



		fputs($socket, "HELO " . $this->configMe['smtp_host'] . "\r\n");

		if (!$this->server_parse($socket, "250", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Не могу отправить HELO!';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, "AUTH LOGIN\r\n");

		if (!$this->server_parse($socket, "334", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Не могу найти ответ на запрос авторизаци.';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, base64_encode($this->configMe['smtp_username']) . "\r\n");

		if (!$this->server_parse($socket, "334", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Логин авторизации не был принят сервером!';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, base64_encode($this->configMe['smtp_password']) . "\r\n");

		if (!$this->server_parse($socket, "235", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Пароль не был принят сервером как верный! Ошибка авторизации!';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, "MAIL FROM: <".$this->configMe['smtp_username'].">\r\n");

		if (!$this->server_parse($socket, "250", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Не могу отправить комманду MAIL FROM:';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");



		if (!$this->server_parse($socket, "250", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Не могу отправить комманду RCPT TO:';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, "DATA\r\n");



		if (!$this->server_parse($socket, "354", __LINE__)) {

			if ($this->configMe['smtp_debug']){

				$c='Не могу отправить комманду DATA';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, $SEND."\r\n.\r\n");



		if (!$this->server_parse($socket, "250", __LINE__)) {

			if ($this->configMe['smtp_debug']) {

				$c='Не смог отправить тело письма. Письмо не было отправленно!';

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			fclose($socket);

			return false;

		}

		fputs($socket, "QUIT\r\n");

		fclose($socket);

		return TRUE;

	}

	private function server_parse($socket, $response, $line = __LINE__) {

		$server_response="";
		while (substr($server_response, 3, 1) != ' ') {

			if (!($server_response = fgets($socket, 256))) {

				if ($this->configMe['smtp_debug']){

					$c="<p>Проблемы с отправкой почты!</p>$response<br>$line<br>";

					$path='error.txt'; //путь к файлу

					$fp=fopen($path,'w+');

					fwrite($fp,$c);

					fclose($fp);

				}

				return false;

			}

		}

		if (!(substr($server_response, 0, 3) == $response)) {

			if ($this->configMe['smtp_debug']){

				$c="<p>Проблемы с отправкой почты!</p>$response<br>$line<br>";

				$path='error.txt'; //путь к файлу

				$fp=fopen($path,'w+');

				fwrite($fp,$c);

				fclose($fp);

			}

			return false;

		}

		return true;

	}

	//Yii::$app->SmtpSendEmail->TestSendMail();
	public function TestSendMail(){
		//$charset = 'utf-8';
		//$subject = "Номер";
		//$replyto = $mail;
		//$headers = "To: onlineautocredits.ru $to\r\n".
		//            "From:  <allright1487@gmail.com>\r\n".
		//              "Reply-To: $replyto\r\n".
		//			  "Content-Type: text/html; charset=\"$charset\"\r\n";
		$this->Send('info@tacticgame.com', 'Test Mail From Tatcicgame.es', 'Hello I am test Message!', $headers='');
	}

}