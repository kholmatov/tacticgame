<?php
//start session in all pages
if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
//if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above

$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'tacticgame.es.sandbox_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword 		= 'LUBQAYHFJ8F3R8W4'; //Paypal API password
$PayPalApiSignature 	= 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-A.0Ut27zZBOs-wEmdACPsBNAtBZ5'; //Paypal API Signature
$PayPalCurrencyCode 	= 'EUR'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://tacticgame.es/paypal/process.php'; //Point to process.php page
$PayPalCancelURL 		= 'http://tacticgame.es/paypal/cancel_url.php'; //Cancel URL if user clicks cancel
?>