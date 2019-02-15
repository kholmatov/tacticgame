<?php
define('CLIENT_ID', 'AVBar8zAox3b8HQzQU82DPG524jVvWKhaq04iczwoHx53Mt0atuaM9eAeRUpcLfVPkZQbbMbuXDTTnkY'); //your PayPal client ID
define('CLIENT_SECRET', 'EHOFOY3mZMmxqKeq4gLWsxdlG11GlFgJkcVJgP0lNTAs-1RCNnlmx20DpISvj1h6D5k5k1Su2pr9IUui'); //PayPal Secret
define('RETURN_URL', 'http://cdn.tacticgame.es/paypal-rest/order_process.php'); //return URL where PayPal redirects user
define('CANCEL_URL', 'http://cdn.tacticgame.es/paypal-rest/payment_cancel.html'); //cancel URL
define('PP_CURRENCY', 'USD'); //Currency code
define('PP_MODE', 'sandbox'); //sandbox or live (sandbox requires testing credentials)
//define('PP_CONFIG_PATH', ''); //PayPal config path (sdk_config.ini)

//Enter MySQL details
$db_host 		= "localhost";
$db_username 	= "root";
$db_password 	= "...";
$db_name 		= "paypal";

//Open mySQL connection
$mysqli = new mysqli( $db_host, $db_username, $db_password, $db_name);
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

?>