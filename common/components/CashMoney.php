<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 15/06/15
 * Time: 02:16
 */

namespace common\components;

use PayPal\Exception\PayPalConfigurationException;
use yii\base\Component;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\CreditCard;
use PayPal\Exception\PayPalConnectionException;

class CashMoney extends Component {
	public $client_id;
	public $client_secret;
	private $apiContext; // paypal's API context

	// override Yii's object init()
	function init() {
		$this->apiContext = new ApiContext(
			new OAuthTokenCredential($this->client_id, $this->client_secret)
	);
		//print_r($this->apiContext);
	}

	public function getContext() {
		return $this->apiContext;
	}


	public function MakePayments(){ // or whatever yours is called
		$card = new CreditCard;
		$card->setType('visa')
		->setNumber('4111111111111111')
		->setExpireMonth('06')
		->setExpireYear('2018')
		->setCvv2('782')
		->setFirstName('Richie')
		->setLastName('Richardson');

		try {
		$card->create($this->apiContext);
			// ...and for debugging purposes
		echo '<pre>';
		var_dump('Success scenario');
		echo $card;
		} catch (PayPalConfigurationException $e) {
			echo '<pre>';
			var_dump('Failure scenario');
			echo "Error (File: ".$e->getFile().", line ".
				$e->getLine()."): ".$e->getMessage();
		}

	}
}
