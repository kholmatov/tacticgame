<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 15/06/15
 * Time: 03:19
 */
/**
 * File Paypal.php.
 *
 * @author Marcio Camello <marciocamello@outlook.com>
 * @see https://github.com/paypal/rest-api-sdk-php/blob/master/sample/
 * @see https://developer.paypal.com/webapps/developer/applications/accounts
 */
namespace common\components;

define('PP_CONFIG_PATH', __DIR__);
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;

class Paypal extends Component
{
	//region Mode (production/development)
	const MODE_SANDBOX = 'sandbox';
	const MODE_LIVE    = 'live';
	//endregion
	//region Log levels
	/*
	 * Logging level can be one of FINE, INFO, WARN or ERROR.
	 * Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR.
	 */
	const LOG_LEVEL_FINE  = 'FINE';
	const LOG_LEVEL_INFO  = 'INFO';
	const LOG_LEVEL_WARN  = 'WARN';
	const LOG_LEVEL_ERROR = 'ERROR';
	//endregion
	//region API settings
	public $clientId;
	public $clientSecret;
	public $isProduction = false;
	public $currency = 'EUR';
	public $config = [];
	/** @var ApiContext */
	private $_apiContext = null;
	/**
	 * @setConfig
	 * _apiContext in init() method
	 */
	public function init()
	{
		$this->setConfig();
	}
	/**
	 * @inheritdoc
	 */
	private function setConfig()
	{
		// ### Api context
		// Use an ApiContext object to authenticate
		// API calls. The clientId and clientSecret for the
		// OAuthTokenCredential class can be retrieved from
		// developer.paypal.com
		$this->_apiContext = new ApiContext(
			new OAuthTokenCredential(
				$this->clientId,
				$this->clientSecret
			)
		);
		// #### SDK configuration
		// Comment this line out and uncomment the PP_CONFIG_PATH
		// 'define' block if you want to use static file
		// based configuration
		$this->_apiContext->setConfig(ArrayHelper::merge(
				[
					'mode'                      => self::MODE_SANDBOX, // development (sandbox) or production (live) mode
					'http.ConnectionTimeOut'    => 30,
					'http.Retry'                => 1,
					'log.LogEnabled'            => YII_DEBUG ? 1 : 0,
					'log.FileName'              => Yii::getAlias('@runtime/logs/paypal.log'),
					'log.LogLevel'              => self::LOG_LEVEL_FINE,
					'validation.level'          => 'log',
					'cache.enabled'             => 'true'
				],$this->config)
		);
		// Set file name of the log if present
		if (isset($this->config['log.FileName'])
			&& isset($this->config['log.LogEnabled'])
			&& ((bool)$this->config['log.LogEnabled'] == true)
		) {
			$logFileName = \Yii::getAlias($this->config['log.FileName']);
			if ($logFileName) {
				if (!file_exists($logFileName)) {
					if (!touch($logFileName)) {
						throw new ErrorException('Can\'t create paypal.log file at: ' . $logFileName);
					}
				}
			}
			$this->config['log.FileName'] = $logFileName;
		}
		return $this->_apiContext;
	}
	//Demo
	public function payDemo()
	{
		/*
		$addr = new Address();
		$addr->setLine1('52 N Main ST');
		$addr->setCity('Johnstown');
		$addr->setCountryCode('US');
		$addr->setPostalCode('43210');
		$addr->setState('OH');
		*/
		$card = new CreditCard();
		$card->setType("visa")
		->setNumber("4417119669820331")
		->setExpireMonth("11")
		->setExpireYear("2019")
		->setCvv2("012")
		->setFirstName("Joe")
		->setLastName("Shopper");

		//$card->setBillingAddress($addr);

		$fi = new FundingInstrument();
		$fi->setCreditCard($card);

		$payer = new Payer();
		$payer->setPaymentMethod('credit_card');
		$payer->setFundingInstruments(array($fi));

		$amountDetails = new Details();
		$amountDetails->setSubtotal('6');
		$amountDetails->setTax('1');
		//$amountDetails->setShipping('1');

		$amount = new Amount();
		$amount->setCurrency('USD');
		$amount->setTotal('7');
		$amount->setDetails($amountDetails);

		$transaction = new Transaction();
		$transaction->setAmount($amount);
		$transaction->setDescription('This is the payment transaction description.');

		$payment = new Payment();
		$payment->setIntent('sale');
		$payment->setPayer($payer);
		$payment->setTransactions(array($transaction));

		try {
			$payment->create($this->_apiContext);
			echo $payment;
		}
		catch (PayPalConnectionException $ex) {
			// This will print the detailed information on the exception.
			//REALLY HELPFUL FOR DEBUGGING
			echo $ex->getData();
		}

		//return $payment->create($this->_apiContext);
	}

	public function makePayDemo(){
		$creditCard = new CreditCard();
		$creditCard->setType("visa")
			->setNumber("4417119669820331")
			->setExpireMonth("11")
			->setExpireYear("2019")
			->setCvv2("012")
			->setFirstName("Joe")
			->setLastName("Shopper");
		try {
			$creditCard->create($this->_apiContext);
			echo $creditCard;
		}
		catch (PayPalConnectionException $ex) {
			// This will print the detailed information on the exception.
			//REALLY HELPFUL FOR DEBUGGING
			echo $ex->getData();
		}
	}


	//create PayPal payment method
	public function create_paypal_payment($total, $currency, $desc, $my_items, $redirect_url, $cancel_url){
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($redirect_url);
		$redirectUrls->setCancelUrl($cancel_url);

		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$amount = new Amount();
		$amount->setCurrency($currency);
		$amount->setTotal($total);

		$items = new ItemList();
		$items->setItems($my_items);

		$transaction = new Transaction();
		$transaction->setAmount($amount);
		$transaction->setDescription($desc);
		$transaction->setItemList($items);

		$payment = new Payment();
		$payment->setRedirectUrls($redirectUrls);
		$payment->setIntent("sale");
		$payment->setPayer($payer);
		$payment->setTransactions(array($transaction));

		$payment->create($this->_apiContext);

		return $payment;
	}

//executes PayPal payment
	public function execute_payment($payment_id, $payer_id){
		$payment = Payment::get($payment_id, $this->_apiContext);
		$payment_execution = new PaymentExecution();
		$payment_execution->setPayerId($payer_id);
		$payment = $payment->execute($payment_execution, $this->_apiContext);
		return $payment;
	}


//pay with credit card
	public function pay_direct_with_credit_card($credit_card_params, $currency, $amount_total, $my_items, $payment_desc) {

		$card = new CreditCard();
		$card->setType($credit_card_params['type']);
		$card->setNumber($credit_card_params['number']);
		$card->setExpireMonth($credit_card_params['expire_month']);
		$card->setExpireYear($credit_card_params['expire_year']);
		$card->setCvv2($credit_card_params['cvv2']);
		$card->setFirstName($credit_card_params['first_name']);
		$card->setLastName($credit_card_params['last_name']);

		$funding_instrument = new FundingInstrument();
		$funding_instrument->setCreditCard($card);

		$payer = new Payer();
		$payer->setPaymentMethod("credit_card");
		$payer->setFundingInstruments(array($funding_instrument));

		$amount = new Amount();
		$amount->setCurrency($currency);
		$amount->setTotal($amount_total);

		$items = new ItemList();
		$items->setItems($my_items);

		$transaction = new Transaction();
		$transaction->setAmount($amount);
		$transaction->setDescription("creating a direct payment with credit card");
		$transaction->setItemList($items);

		$payment = new Payment();
		$payment->setIntent("sale");
		$payment->setPayer($payer);
		$payment->setTransactions(array($transaction));

		$payment->create($this->_apiContext);


		return $payment;
	}
}