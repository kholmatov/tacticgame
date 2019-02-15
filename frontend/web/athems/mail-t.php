<?php
$result = mail('shohin002@mail.ru', 'subject', 'message');

if($result)
{
	echo 'OK';
}
else
{
	echo 'Error!';
}
?>
