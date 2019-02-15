<?php

namespace frontend\controllers;

use Yii;
use backend\models\Transaction;
use backend\models\Sections;
use backend\models\SectionsSearch;
use backend\models\Grafic;
use backend\models\Order;
use backend\models\Certificatorder;
use backend\models\Couponcode;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use kholmatov\lacaixa\RedsysWDG;

//use kartik\mpdf\Pdf;

/**
 * QuestController implements the CRUD actions for Quest model.
 */
class QuestController extends Controller
{
	public $currency='EUR';
    public $currentUrl = '';
    public $return_url = '';
    public $cancel_url = '';
    public $notifc_url = '';
    public $notifc_url_demo = '';

//    function __construct()
//    {
//        //$this->_init();
//    }

    public function init()
    {
        parent::init();
        $this->currentUrl = Yii::$app->request->serverName;
        $this->return_url = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/quest/yes';
        $this->cancel_url = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/quest/cancel';
        $this->notifc_url = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/quest/notification';
        $this->notifc_url_demo = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/quest/notificationdemo';
    }

	public function behaviors()
    {
	    //\Yii::$app->language = 'en-Eng';
	    return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionView($id)
    {
        if(Yii::$app->request->get('yes')) $dev=1; else $dev=0;
        //print_r(Yii::app()->request->getUrl());
        $address = Sections::getAddress($id);
        $model = $this->findModel($id);
        if(Yii::$app->language=='es-ES' || Yii::$app->language=='es'):
            $address[0]['title'] = $address[0]['title_es'];
            $address[0]['address'] = $address[0]['address_es'];

            $model->title = $model->title_es;
            $model->shorttext = $model->shorttext_es;
            $model->duration = $model->duration_es;
        endif;


        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'yyyy-MM-dd');
        //$nowtime = Yii::$app->formatter->asDatetime($time,'yyyy-MM-dd HH:mm:s');
        //Строга берем 14 дней
        $end = date('Y-m-d', strtotime($now.' + 30 day'));
        $graficData = Grafic::find()->asArray()->
        where('active = :active AND date >= :now AND date <= :end AND section_item_id = :sectionid',
            ['active'=>1,'now'=>$now, 'end'=>$end, 'sectionid'=>$id])
            ->orderBy('date asc')
            ->all();

        //Берем закази на данный периуд
        $Order = Grafic::getGraficOrder($now,$end,$id);

        $orderArray=Array();
        if(isset($Order)){
            foreach($Order as $item){
                if($item['status']==1)
                    $orderArray[$item['date']][$item['time']] = Array('status' => $item['status']);
            }
        }

        $tariff_check = 0;
        $tariff_item = Array();
        if ($model->tariff){
            $tariff_array = explode(';',$model->tariff);

            foreach($tariff_array as $rows){
                $row = explode('-',$rows);
                $tariff_item[] = Array(
                    'users'=>$row[0],
                    'cost'=>$row[1],
                    'total'=>$row[2]
                );
            }
            $tariff_check = 1;
            //print_r($tariff_item);
        }



        $dataItems = Array();
        foreach($graficData as $item){
            $arrTimePrice=explode(';',$item['timearray']);
            foreach($arrTimePrice as $rows){
                $row = explode('|',$rows);
                //Get time $row[0] || Get price $row[1]
                $status=0;
                if(isset($orderArray[$item['date']][$row[0]])){
                    $status=$orderArray[$item['date']][$row[0]]['status'];
                }

                $diff = $this->diffDateTime($item['date'],$row[0]);
                if($diff<0) $status=1;

                $sale=0;
                if($row[1] < 0) {
                    $row[1] = str_replace("-", "", $row[1]);
                    $sale = 1;
                }

                //Yii::$app->formatter->locale = 'ru-RU';
                $dataItems[$item['date']][$row[0]]=Array(
                    'id' => $item['id'],
                    'section_item_id' => $item['section_item_id'],
                    'date'=>$item['date'],
                    'full'=>Yii::$app->formatter->asDate($item['date'],'full'),
                    'short'=>Yii::$app->formatter->asDate($item['date'],'php:j F'),
                    'time'=>$row[0],
                    'price'=>$row[1],
                    'currency'=>'€',
                    'status'=>$status,
                    'sale'=>$sale
                );
            }
            //echo  \Yii::t('app', 'Today is {0, date}', $item['date']);
            $dataItems[$item['date']]['full'] = Yii::$app->formatter->asDate($item['date'],'full');
            //$dataItems[$item['date']]['mydate']=$item['date'];
        }



        return $this->render('view', [
            'model' => $model,
            'address' => $address,
            'dataitems' => $dataItems,
            'tariff'=> $tariff_item,
            'dev'=>$dev
        ]);
    }

    public function actionOrders(){

        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        //Yii::$app->response->format = Response::FORMAT_JSON;
        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $post = Yii::$app->request->post();

//       $post = array(
//            'client_email'=>'asus-ns55@mail.ru',
//            'client_phone'=>'99837474',
//            'client_section'=>'2',
//            'client_time'=>'12:00',
//            'client_date'=>'2017-06-13 00:00:00',
//            'client_price'=>'80',
//            'quest_title'=>'CHERNÓBIL',
//            'quest_user'=>'2',
//            'quest_gift'=>'4715CW7P3',
//            'quest_total'=>'125',
//            'quest_pcode'=>'1639UP2K8R26',
//            'quest_lang'=>'esp',
//            'payment_type'=>'gift',
//            'credit_type'=>'',
//            'credit_number'=>'',
//            'credit_csc'=>'',
//            'credit_name'=>'',
//            'credit_month'=>'MM',
//            'credit_year'=>'YY',
//            'id_source'=>'order'
//        );


        $date = $post['client_date'];
        $time = $post['client_time'];
        $amount = $post['quest_total'];//$post['client_price'];
        $phone = $post['client_phone'];
        $email = $post['client_email'];
        $quest_title = $post['quest_title'];
        $quest_user = $post['quest_user'];
        $quest_gift = $post['quest_gift']?$post['quest_gift']:'';
        $quest_lang = $post['quest_lang'];
        $code = strtoupper($quest_lang.$this->generateRandomString(2)).$quest_user;

        $model= new Order;
        $model->sections_id = $post['client_section'];
        $model->date = $date;
        $model->time = $time;
        $model->amount = $amount;
        $model->phone = $phone;
        $model->email = $email;
        $model->createdate = $now;
        $model->language = $quest_lang;
	    $model->code = $code;
        $model->payment_type = $post["payment_type"];

        if($post['quest_pcode']){
            $cCode = Couponcode::checkCode($post['quest_pcode']);
            if (count($cCode) > 0) {
                $sArr = explode(',', $cCode['section_id']);
                if(in_array($post['client_section'], $sArr) && $cCode['count'] > $cCode['cnt']){
                    $dis_amount = $amount - $amount/100 * $cCode['discount'];
                    $model->coupon_code = $cCode['code'];
                    $model->amount = $dis_amount;
                    $model->comment = 'Old Price:'.$amount.' (Discount:'.$cCode['discount'].')';
                    $amount = $dis_amount;
                    if($cCode['count']==($cCode['cnt']+1)) {
                        $cuopon = Couponcode::findOne($cCode['id']);
                        $cuopon->status = 1;
                        $cuopon->update();
                    }
                 }
            }
        }

        $giftCheck = 0;
        if($post["payment_type"] == "gift"){
            $certO = Certificatorder::getCertification($quest_gift);
            if(isset($certO['code'])) {
                $str_arr = str_split($quest_gift);
                $str_arr = array_reverse($str_arr);
                $int_gift = "";
                for($i = 0; $i < count($str_arr); $i++){
                    if(is_numeric($str_arr[$i])) {
                        $int_gift .= $str_arr[$i];
                    }else{
                        break;
                    }
                }
                $int_gift = intval(strrev($int_gift));
                if($int_gift >= intval($quest_user)){
                    $model->certificate = $quest_gift;
                    $giftCheck = 1;
                }
            }
        }

        if($post["payment_type"] == "none" || $post["payment_type"] == "lacaixa" || $giftCheck){
            $model->status = 1;
        }

        if($model->save()){

            if($post["payment_type"] == "none"):
                $this->goMail($code,$quest_title,$date,$time,$amount,$email,$phone);

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                   $this->sendEmailToClient($code);
                }

                $json['url'] = 'http://'.Yii::$app->request->serverName.'/'.Yii::$app->language.'/quest/booking?code='.$code;
                echo json_encode($json);
                exit();

            elseif($post["payment_type"] == "lacaixa"):

                if(Yii::$app->language=='es-ES' || Yii::$app->language=='es'):
                    $language="";
                else:
                    $language="002";
                endif;
                $date = Yii::$app->formatter->asDate($date,'full');
                $pDesc = $quest_title.' '.$date.', '.$time;
                //$amount=1;
                //echo $amount;
                //echo number_format($amount, 2, ',', '');
                echo RedsysWDG::getFormDataJson($code,$amount*100,$language,$pDesc,$this->return_url, $this->cancel_url,$this->notifc_url);
                exit();
            elseif($post["payment_type"] == "lacaixademo"):
                if(Yii::$app->language=='es-ES' || Yii::$app->language=='es'):
                    $language="";
                else:
                    $language="002";
                endif;
                $date = Yii::$app->formatter->asDate($date,'full');
                $pDesc = $quest_title.' '.$date.', '.$time;
                //$amount=1;
                //echo $amount;
                //echo number_format($amount, 2, ',', '');
                echo RedsysWDG::getFormDataJsonDemo($code,$amount*100,$language,$pDesc,$this->return_url, $this->cancel_url,$this->notifc_url_demo);
                exit();
                //$code,$amount.'00',$language, $quest_title,$date $time
            elseif(!$giftCheck):
                $json['url'] = "";
                echo json_encode($json);
                exit();
            elseif($giftCheck):
                 $this->UpdateStatus($code);
                 if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->sendEmailToClient($code);
                 }
                $json['url'] = 'http://'.Yii::$app->request->serverName.'/quest/ticket?code='.$code;
                echo json_encode($json);
                exit();
            elseif($post["payment_type"] == "paypal" || $post["payment_type"] == "credit_card"):

                $items[] = array(
                    'name' => $quest_title,
                    'quantity' => 1,
                    'price' => $amount,
                    'sku' => $code,
                    'currency' => $this->currency
                );

                $session = Yii::$app->session;
                $session->open();
                $session["items"] = $items;
                $session["items_total"]=$amount;

                //try a payment request
                try{
                    ######## if payment method is PayPal ##############
                    if($post["payment_type"] == "paypal"){
                        //if payment method was PayPal, we need to redirect user to PayPal approval URL
                       $result = Yii::$app->paypal->create_paypal_payment($amount, $this->currency, '', $items, $this->return_url, $this->cancel_url);
                        //print_r($result);
                        if($result->state == "created" && $result->payer->payment_method == "paypal"){
                            $session["payment_id"] = $result->id; //set payment id for later use, we need this to execute payment
                            unset($session["items"]); //unset item session, not required anymore.
                            unset($session["items_total"]); //unset items_total session, not required anymore.
                            $json['url'] = $result->links[1]->href;

                            echo json_encode($json);

                            //header("location: ". $result->links[1]->href); //after success redirect user to approval URL
                            exit();
                        }

                    }

                    ######## if payment method is Credit Card ##############
                    if($post["payment_type"] == "credit_card"){

                        $name = (isset($post["credit_name"]))? $post["credit_name"] : die("Name Empty");
                        $name = explode(" ",$name); $cc_first_name = $name[0]; $cc_last_name=$name[1];
                        $cc_card_type 		= (isset($post["credit_type"]))? strtolower($post["credit_type"]) : die("Credit Card type Empty");
                        $cc_card_number 	= (isset($post["credit_number"]))? $post["credit_number"] : die("Credit Card Number Empty");
                        $cc_card_month 		= (isset($post["credit_month"]))? $post["credit_month"] : die("Expire Month Empty");
                        $cc_card_year 		= (isset($post["credit_year"]))? $post["credit_year"] : die("Expire Year Empty");
                        $cc_card_cvv2 		= (isset($post["credit_csc"]))? $post["credit_csc"] : die("CVV month empty");


                        $credit_card = array(
                            'type'=> $cc_card_type,
                            'number' => str_replace(" ","",$cc_card_number),
                            'expire_month'=>$cc_card_month,
                            'expire_year'=>'20'.$cc_card_year,
                            'cvv2'=>$cc_card_cvv2,
                            'first_name'=>$cc_first_name,
                            'last_name'=>$cc_last_name
                        );

                        //pay directly using credit card information.
                        $result = Yii::$app->paypal->pay_direct_with_credit_card($credit_card,$this->currency , $amount, $items, '') ;

                        //If credit card payment is succesful, get results
                        if($result->state == "approved" && $result->payer->payment_method == "credit_card"){
                            unset($session["items"]); //unset item session, not required anymore.
                            unset($session["items_total"]); //unset items_total session, not required anymore.

                            $this->SaveTransaction(
                                $result->transactions[0]->item_list->items[0]->sku,
                                $result->transactions[0]->related_resources[0]->sale->id,
                                $result->transactions[0]->related_resources[0]->sale->amount->currency,
                                $result->transactions[0]->related_resources[0]->sale->amount->total,
                                $result->payer->payment_method,
                                $result->transactions[0]->related_resources[0]->sale->state);

                            $this->UpdateStatus($result->transactions[0]->item_list->items[0]->sku);
    //                        $session["results"]  = array(
    //                            'state' => $result->state,
    //                            'code' => $result->transactions[0]->item_list->items[0]->sku
    //                        );
                            $json['url'] = 'http://tacticgame.es/quest/ticket?code='.$result->transactions[0]->item_list->items[0]->sku;
                            echo json_encode($json);
                            exit();
                            //return $this->redirect(array('quest/ticket','code'=>$result->transactions[0]->item_list->items[0]->sku));
                        }
                    }

                //$this->goMail($quest_title,$date,$time,$amount,$email,$phone);
                }catch(PPConnectionException $ex) {
                    echo parseApiError($ex->getData());
                }catch (Exception $ex){
                    echo $ex->getMessage();
                }

         endif;
       }

    }

    public function actionPromocode(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        $request = Yii::$app->request;
        if ($request->isPost) {
            $post = $request->post();
            //$post['code'] = '115785QBIGFW';
            //$post['id'] = '1';
            $cCode = Couponcode::checkCode($post['code']);
            if (count($cCode) > 0) {
                $sArr = explode(',', $cCode['section_id']);
                if (in_array($post['id'], $sArr) && $cCode['count'] > $cCode['cnt']) {
                    $json['status'] = 1;
                    $json['procent'] = $cCode['discount'];
                    echo json_encode($json);
                    exit();
                }
            }
            $json['status'] = 0;
            echo json_encode($json);
            exit();
        }
        return $this->goHome();
    }

    public function actionNotification(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        $request = Yii::$app->request;
        if ($request->isGet)  {
            /* the request method is GET */
            $get = $request->get();
        }
        if ($request->isPost) {
            /* the request method is POST */
            $get = $request->post();
        }

        $file = \Yii::$app->basePath.'/web/upload/logquest.txt';
        $current = file_get_contents($file);
        $current .= "\n==========Quest==============\n";
        //$current .= Yii::$app->formatter->asDate($myDatetime,'php:Y-m-d H:i:s');


        if(isset($get) && isset($get['Ds_SignatureVersion']) && isset($get['Ds_MerchantParameters']) && isset($get['Ds_Signature'])):
            $rs = RedsysWDG::checkData($get['Ds_SignatureVersion'],$get['Ds_MerchantParameters'],$get['Ds_Signature']);
            if($rs){
                $rsParam = RedsysWDG::decodeData($get['Ds_MerchantParameters']);
                $myParam = json_decode($rsParam,true);
                $myDate = rawurldecode($myParam['Ds_Date']); $myHour = rawurldecode($myParam['Ds_Hour']); $myDatetime = $myDate.' '.$myHour;
                $myDatetime = str_replace('/', '-', $myDatetime);
                $transaction_time = Yii::$app->formatter->asDate($myDatetime,'php:Y-m-d H:i:s');//date('Y-d-m H:i:s',strtotime($myDatetime));
                $current .= $transaction_time."\n";
                $code = $myParam['Ds_Order'];
                $transaction_amount = $myParam['Ds_Amount']/100;//substr($myParam['Ds_Amount'], 0, -2);
                $transaction_currency = $myParam['Ds_Currency'];
                $transaction_id = $myParam['Ds_AuthorisationCode'];
                if($myParam['Ds_Response']=='0000'||$myParam['Ds_Response']=='0099'||$myParam['Ds_Response']=='0900') {
                    $transaction_state="completed";
                    $this->UpdateStatus($code);
                    $row = Order::getOrders($code);
                    if(isset($row)&&$row != ""){

                        if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                            $this->sendEmailToClient($code);
                        }

                        $goEmailResult = $this->goMail($code,$row['title'],$row['date'],$row['time'],$row['amount'],$row['email'],$row['phone']);
                    }
                }else{
                    $transaction_state="none";
                }

                $transaction_method='lacaixa';
                $rsTransaction = Transaction::find()->asArray()->where(['code' =>$code,'transaction_id'=>$transaction_id])->one();
                if(!isset($rsTransaction['code']) && $rsTransaction['code']!=$code)
                    $this->SaveTransaction($code,$transaction_id,$transaction_currency,$transaction_amount,$transaction_method,$transaction_state,$transaction_time);
            }
        endif;

        $current .= var_export($get, true);//var_dump($get);
        $current .= "\n====================================\n";
        file_put_contents($file, $current);
        exit();
    }

    public function actionNotificationdemo(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        $request = Yii::$app->request;
        if ($request->isGet)  {
            /* the request method is GET */
            $get = $request->get();
        }
        if ($request->isPost) {
            /* the request method is POST */
            $get = $request->post();
        }

        $file = \Yii::$app->basePath.'/web/upload/logquest.txt';
        $current = file_get_contents($file);
        $current .= "\n==========Quest DEMO==============\n";
        //$current .= Yii::$app->formatter->asDate($myDatetime,'php:Y-m-d H:i:s');
        $current .= var_export($get, true);//var_dump($get);
        $current .= "\n====================================\n";
        file_put_contents($file, $current);

        if(isset($get) && isset($get['Ds_SignatureVersion']) && isset($get['Ds_MerchantParameters']) && isset($get['Ds_Signature'])):
            $rs = RedsysWDG::checkDataDemo($get['Ds_SignatureVersion'],$get['Ds_MerchantParameters'],$get['Ds_Signature']);
            if($rs){
                $rsParam = RedsysWDG::decodeData($get['Ds_MerchantParameters']);
                $myParam = json_decode($rsParam,true);
                $myDate = rawurldecode($myParam['Ds_Date']); $myHour = rawurldecode($myParam['Ds_Hour']); $myDatetime = $myDate.' '.$myHour;
                $myDatetime = str_replace('/', '-', $myDatetime);
                $transaction_time = Yii::$app->formatter->asDate($myDatetime,'php:Y-m-d H:i:s');//date('Y-d-m H:i:s',strtotime($myDatetime));
                $current .= $transaction_time."\n";
                $code = $myParam['Ds_Order'];
                $transaction_amount = $myParam['Ds_Amount']/100;//substr($myParam['Ds_Amount'], 0, -2);
                $transaction_currency = $myParam['Ds_Currency'];
                $transaction_id = $myParam['Ds_AuthorisationCode'];
                if($myParam['Ds_Response']=='0000'||$myParam['Ds_Response']=='0099'||$myParam['Ds_Response']=='0900') {
                    $transaction_state="completed";
                    $this->UpdateStatus($code);
                    $row = Order::getOrders($code);
                    if(isset($row)&&$row != ""){

                        if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                            $this->sendEmailToClient($code);
                        }

                        $goEmailResult = $this->goMail($code,$row['title'],$row['date'],$row['time'],$row['amount'],$row['email'],$row['phone']);
                    }
                }else{
                    $transaction_state="none";
                }

                $transaction_method='lacaixa';
                $rsTransaction = Transaction::find()->asArray()->where(['code' =>$code,'transaction_id'=>$transaction_id])->one();
                if(!isset($rsTransaction['code']) && $rsTransaction['code']!=$code)
                    $this->SaveTransaction($code,$transaction_id,$transaction_currency,$transaction_amount,$transaction_method,$transaction_state,$transaction_time);
            }
        endif;

//        $current .= var_export($get, true);//var_dump($get);
//        $current .= "\n====================================\n";
//        file_put_contents($file, $current);
        exit();
    }

    public function actionAcmp(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        $request = Yii::$app->request;
        if ($request->isGet)  {
            /* the request method is GET */
            $get = $request->get();
        }
        if ($request->isPost) {
            /* the request method is POST */
            $get = $request->post();
        }

        $file = \Yii::$app->basePath.'/web/upload/acmp.txt';
        $current = file_get_contents($file);
        $current .= "\n===============Quest================\n";
        $current .= var_export($get, true);//var_dump($get);
        $current .= "\n====================================\n";
        file_put_contents($file, $current);
        //print_r($get);
        exit();

    }

    //for paypal function
    public function actionFinish(){
        $request = Yii::$app->request;
        if ($request->isGet)  {
        /* the request method is GET */
            $get = $request->get();
        }
        if ($request->isPost) {
         /* the request method is POST */
            $get = $request->post();
        }
		//$get = Yii::$app->request->get();
        //$post = Yii::$app->request->post();
        //print_r($post);

        $session = Yii::$app->session;
		$session->open();

		### If Payment method was PayPal, user is redirected back to this page with token and Payer ID ###
		if(isset($get["token"]) && isset($get["PayerID"]) && isset($session["payment_id"])){

            try{
				$result = Yii::$app->paypal->execute_payment($session["payment_id"], $get["PayerID"]);  //call execute payment function.

                if($result->state == "approved"){ //if state = approved continue..
					//SUCESS
					unset($session["payment_id"]); //unset payment_id, it is no longer needed

					//get transaction details
                    $this->SaveTransaction(
                        $result->transactions[0]->item_list->items[0]->sku,
                        $result->transactions[0]->related_resources[0]->sale->id,
                        $result->transactions[0]->related_resources[0]->sale->amount->currency,
                        $result->transactions[0]->related_resources[0]->sale->amount->total,
                        $result->payer->payment_method,
                        $result->transactions[0]->related_resources[0]->sale->state);

                    $this->UpdateStatus($result->transactions[0]->item_list->items[0]->sku);

                    /*
                    $session["results"]  = array(
                        'state' => $result->state,
                        'code' =>  $result->transactions[0]->item_list->items[0]->sku
                    );
                    */

                    return $this->redirect(array('quest/ticket','code'=>$result->transactions[0]->item_list->items[0]->sku));
                    //header("location: ". RETURN_URL); //$_SESSION["results"] is set, redirect back to order_process.php
					//exit();
				}



			}catch(PPConnectionException $ex) {
				$ex->getData();
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}


		}else{
            return $this->redirect(array('/'));
        }

	}

    public function actionYes(){
        $request = Yii::$app->request;
        if ($request->isGet)  {
            /* the request method is GET */
            $get = $request->get();
        }
        if ($request->isPost) {
            /* the request method is POST */
            $get = $request->post();
        }

        //print_r($get);
        if(isset($get) && isset($get['Ds_SignatureVersion']) && isset($get['Ds_MerchantParameters']) && isset($get['Ds_Signature'])):
            $rs = RedsysWDG::checkData($get['Ds_SignatureVersion'],$get['Ds_MerchantParameters'],$get['Ds_Signature']);
            if($rs){
                $rsParam = RedsysWDG::decodeData($get['Ds_MerchantParameters']);
                $myParam = json_decode($rsParam,true);
                $code = $myParam['Ds_Order'];
                return $this->redirect(array('quest/ticket','code'=> $code));
            }
        endif;
        return $this->redirect(array('/'));
    }

	public function actionCancel(){
        $request = Yii::$app->request;
        if ($request->isGet)  {
            /* the request method is GET */
            $get = $request->get();
        }
        if ($request->isPost) {
            /* the request method is POST */
            $get = $request->post();
        }
        //print_r($get);
        if(isset($get) && isset($get['Ds_SignatureVersion']) && isset($get['Ds_MerchantParameters']) && isset($get['Ds_Signature'])):
            $rs = RedsysWDG::checkData($get['Ds_SignatureVersion'],$get['Ds_MerchantParameters'],$get['Ds_Signature']);
            if($rs){
                $rsParam = RedsysWDG::decodeData($get['Ds_MerchantParameters']);
                $myParam = json_decode($rsParam,true);
                $myDate = rawurldecode($myParam['Ds_Date']); $myHour = rawurldecode($myParam['Ds_Hour']); $myDatetime = $myDate.' '.$myHour;
                $myDatetime = str_replace('/', '-', $myDatetime);
                $transaction_time = Yii::$app->formatter->asDate($myDatetime,'php:Y-m-d H:i:s');//date('Y-d-m H:i:s',strtotime($myDatetime));
                $code = $myParam['Ds_Order'];
                $transaction_amount = $myParam['Ds_Amount']/100;
                $transaction_currency = $myParam['Ds_Currency'];
                $transaction_id = $myParam['Ds_AuthorisationCode'];
                $transaction_state = "none";
                $transaction_method = 'lacaixa';
                $rsTransaction = Transaction::find()->asArray()->where(['code' =>$code,'transaction_id'=>$transaction_id])->one();
                if(!isset($rsTransaction['code']) && $rsTransaction['code']!=$code)
                {
                    $this->SaveTransaction($code,$transaction_id,$transaction_currency,$transaction_amount,$transaction_method,$transaction_state,$transaction_time);
                }
            }
        endif;

        return $this->redirect(array('/'));
	}


	public function actionTicket(){
        $get = Yii::$app->request->get();
        if(isset($get['code'])){
            $code=$get['code'];
            //$session = Yii::$app->session;
            //$session->open();
            $row = Order::getTiket($code);
            if(isset($row)&&$row=="") return $this->redirect(array('/'));
            if(count($row) > 0) $address = Sections::getAddress($row['id']);


            return $this->render('ticket', [
                'model' => '',
                'address' => $address,
                'dataitems' => $row
            ]);
        }else{
            return $this->redirect(array('/'));
        }

    }

    public function actionTicketpdf() {
        $get = Yii::$app->request->get();
        if(isset($get['code'])){
            $code=$get['code'];
            $row = Order::getTiket($code);
            if(count($row) > 0) $address = Sections::getAddress($row['id']);
            // get your HTML raw content without any layouts or scripts
            $htmlContent = $this->renderPartial('ticketpdf', [
                'model' => '',
                'address' => $address,
                'dataitems' => $row
            ]);
            $date = date('Y').' &copy; Tacticgame.es';
            $pdf = Yii::$app->pdf;

            $pdf->content=$htmlContent;
            $pdf->cssFile='@frontend/web/athems/css/pdf-style.css';
            $pdf->cssInline = '.kv-heading-1{font-size:18px}';
            $pdf->methods= [
                'SetHeader'=>['www.tacticgame.es'],
                'SetFooter'=>[$date],
            ];

        // return the pdf output as per the destination setting
        return $pdf->render();
        }
    }

    public function actionBooking(){
        $get = Yii::$app->request->get();
        if(isset($get['code'])){
            $code=$get['code'];
            //$session = Yii::$app->session;
            //$session->open();
            //$row = Order::getOrders($code);
            //if(count($row) > 0) $address = Sections::getAddress($row['id']);


            return $this->render('booking', [
                'code' => $code,
            ]);
        }

    }

    private function SendEmailFromCode($code){
        $row = Order::getOrders($code);
        if(isset($row)&&$row==""){
            //s.id, s.title, s.min, s.max, s.duration, g.payment_type, g.date, g.time, g.status,g.code, g.amount, g.email, g.phone
            //if(count($row) > 0) $address = Sections::getAddress($row['id']);
            $this->goMail($code,$row['title'],$row['date'],$row['time'],$row['amount'],$row['email'],$row['phone']);
            //goMail($code,$quest_title,$date,$time,$amount,$email,$phone)

        }
    }

    private function sendEmailToClient($code){
        $row = Order::getOrders($code);
        $thanks = Yii::t('app','THANK YOU FOR YOUR BOOKING');
        $thanks_body = Yii::t('app','This confirms that your booking has been successful, you will receive an email confirmation together with full confirmation from our bookings manager.');
        $thanks_body .= Yii::t('app','In the meantime, if you have any questions or need further information, please contact us at').' <a href="mailto:info@tacticgame.es" style="color: #fe505a">info@tacticgame.es</a>'
            .Yii::t('app','or by calling').' +34 934 635 221 ';
        $thanks_body .= Yii::t('app','Beseech you to come 15 minutes before the time of booking. If you arrive late, that time will be deducted from your play time.');
        $thanks_body .= Yii::t('app','Once the reservation is made no refunds or cancellations are accepted.');
        $ticket_number = $code;
        $gamers = $row['min'].'-'.$row['max'];
        $duration = $row['duration'];
        $datetime = date('d/m/Y', strtotime($row['date'])).' '.$row['time'];
        $price = $row['amount'].' €';
        $quest = $row['title'];
        $email = $row['email'];

        $ItemTitle = 'Tactic Time Cafe';
        $ItemAddress = 'Carrer de los castillejos 287';

        $address = Sections::getAddress($row['id']);
        if(is_array($address)){
            foreach($address as $sitem){
                $ItemTitle = $sitem['title'];
                $ItemAddress = $sitem['address'];
            }
        }

        $address = '<span>'.$ItemTitle.'</span><br><span>'.$ItemAddress.'</span>';
        $subject = Yii::t('app','THANK YOU FOR YOUR BOOKING').':'.$code;
        $this->email_booking($thanks,$thanks_body,$ticket_number,$gamers,$duration,$datetime,$price,$quest,$address,$email,$subject);
    }

    protected function generateRandomString($length = 32)
    {
        $bytes = Yii::$app->security->generateRandomKey($length);
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
        return rand(1000,9000).strtr(substr(base64_encode($bytes), 0, $length), '+/_-', 'psnm');
    }

    private function goMail($code,$quest_title,$date,$time,$amount,$email,$phone){
        $tm = new \DateTime('now');

        $now = Yii::$app->formatter->asDate($tm,'php:Y-m-d H:i:s');
        $date = Yii::$app->formatter->asDate($date,'full');

        $emailHtml = "
            <table width='800' border='1' bordercolor='#B6B6B6' align='center' cellspacing='0' cellpadding='0' style='border:1px solid #B6B6B6; border-collapse:collapse; background-color:#FFF; margin-top:15px; margin-bottom:10px;'>
            <tr><td colspan='3' style='text-align:center; background-color:#e7deab; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0d0d0d; font-weight:bold; padding:15px;'>".$quest_title.":&nbsp;&nbsp;&nbsp;[&nbsp;".$now."&nbsp;]</td>

             <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Code').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$code."</td>
            </tr>

            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Quest').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$quest_title."</td>
            </tr>

            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Date').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$date."</td>
            </tr>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Time').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$time."</td>
            </tr>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Amount').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$amount." €</td>
            </tr>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Email').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$email."</td>
            </tr>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Phone').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$phone."</td>
            </tr>
            </table>
         ";

        $to = "tacticgame.es@gmail.com";
        $headers     = "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type: text/html; charset=utf-8\r\n";
        $headers    .= "From: <info@tacticgame.es>\r\n";
        $subject = Yii::t('app','New order').': '.$quest_title;

        //Yii::$app->SmtpSendEmail->Send($to, $subject, $emailHtml,$headers);

        if(mail($to,$subject,$emailHtml,$headers))
            return 1;
        else
            return 0;
    }

    private function email_booking($thanks,$thanks_body,$ticket_number,$gamers,$duration,$datetime,$price,$quest,$address,$email,$subject){
        $emailHtml='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head>
                <title></title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <style type="text/css">
                    .font-sans-serif {
                        font-family: sans-serif;
                    }
                    .font-avenir {
                        font-family: Avenir, sans-serif;
                    }
                    .mso .wrapper .font-avenir {
                        font-family: sans-serif !important;
                    }
                    .font-lato {
                        font-family: Lato, Tahoma, sans-serif;
                    }
                    .mso .wrapper .font-lato {
                        font-family: Tahoma, sans-serif !important;
                    }
                    .font-cabin {
                        font-family: Cabin, Avenir, sans-serif;
                    }
                    .mso .wrapper .font-cabin {
                        font-family: sans-serif !important;
                    }
                    .font-open-Sans {
                        font-family: \'Open Sans\', sans-serif;
                    }
                    .mso .wrapper .font-open-Sans {
                        font-family: sans-serif !important;
                    }
                    .font-roboto {
                        font-family: Roboto, Tahoma, sans-serif;
                    }
                    .mso .wrapper .font-roboto {
                        font-family: Tahoma, sans-serif !important;
                    }
                    .font-ubuntu {
                        font-family: Ubuntu, sans-serif;
                    }
                    .mso .wrapper .font-ubuntu {
                        font-family: sans-serif !important;
                    }
                    .font-pt-sans {
                        font-family: "PT Sans", "Trebuchet MS", sans-serif;
                    }
                    .mso .wrapper .font-pt-sans {
                        font-family: "Trebuchet MS", sans-serif !important;
                    }
                    .font-georgia {
                        font-family: Georgia, serif;
                    }
                    .font-merriweather {
                        font-family: Merriweather, Georgia, serif;
                    }
                    .mso .wrapper .font-merriweather {
                        font-family: Georgia, serif !important;
                    }
                    .font-bitter {
                        font-family: Bitter, Georgia, serif;
                    }
                    .mso .wrapper .font-bitter {
                        font-family: Georgia, serif !important;
                    }
                    .font-pt-serif {
                        font-family: "PT Serif", Georgia, serif;
                    }
                    .mso .wrapper .font-pt-serif {
                        font-family: Georgia, serif !important;
                    }
                    .font-pompiere {
                        font-family: Pompiere, "Trebuchet MS", sans-serif;
                    }
                    .mso .wrapper .font-pompiere {
                        font-family: "Trebuchet MS", sans-serif !important;
                    }
                    .font-roboto-slab {
                        font-family: "Roboto Slab", Georgia, serif;
                    }
                    .mso .wrapper .font-roboto-slab {
                        font-family: Georgia, serif !important;
                    }
                    @media only screen and (max-width: 620px) {
                        .wrapper .column .size-8 {
                            font-size: 8px !important;
                            line-height: 14px !important;
                        }
                        .wrapper .column .size-9 {
                            font-size: 9px !important;
                            line-height: 16px !important;
                        }
                        .wrapper .column .size-10 {
                            font-size: 10px !important;
                            line-height: 18px !important;
                        }
                        .wrapper .column .size-11 {
                            font-size: 11px !important;
                            line-height: 19px !important;
                        }
                        .wrapper .column .size-12 {
                            font-size: 12px !important;
                            line-height: 19px !important;
                        }
                        .wrapper .column .size-13 {
                            font-size: 13px !important;
                            line-height: 21px !important;
                        }
                        .wrapper .column .size-14 {
                            font-size: 14px !important;
                            line-height: 21px !important;
                        }
                        .wrapper .column .size-15 {
                            font-size: 15px !important;
                            line-height: 23px !important;
                        }
                        .wrapper .column .size-16 {
                            font-size: 16px !important;
                            line-height: 24px !important;
                        }
                        .wrapper .column .size-17 {
                            font-size: 17px !important;
                            line-height: 26px !important;
                        }
                        .wrapper .column .size-18 {
                            font-size: 17px !important;
                            line-height: 26px !important;
                        }
                        .wrapper .column .size-20 {
                            font-size: 17px !important;
                            line-height: 26px !important;
                        }
                        .wrapper .column .size-22 {
                            font-size: 18px !important;
                            line-height: 26px !important;
                        }
                        .wrapper .column .size-24 {
                            font-size: 20px !important;
                            line-height: 28px !important;
                        }
                        .wrapper .column .size-26 {
                            font-size: 22px !important;
                            line-height: 31px !important;
                        }
                        .wrapper .column .size-28 {
                            font-size: 24px !important;
                            line-height: 32px !important;
                        }
                        .wrapper .column .size-30 {
                            font-size: 26px !important;
                            line-height: 34px !important;
                        }
                        .wrapper .column .size-32 {
                            font-size: 28px !important;
                            line-height: 36px !important;
                        }
                        .wrapper .column .size-34 {
                            font-size: 30px !important;
                            line-height: 38px !important;
                        }
                        .wrapper .column .size-36 {
                            font-size: 30px !important;
                            line-height: 38px !important;
                        }
                        .wrapper .column .size-40 {
                            font-size: 32px !important;
                            line-height: 40px !important;
                        }
                        .wrapper .column .size-44 {
                            font-size: 34px !important;
                            line-height: 43px !important;
                        }
                        .wrapper .column .size-48 {
                            font-size: 36px !important;
                            line-height: 43px !important;
                        }
                        .wrapper .column .size-56 {
                            font-size: 40px !important;
                            line-height: 47px !important;
                        }
                        .wrapper .column .size-64 {
                            font-size: 44px !important;
                            line-height: 50px !important;
                        }
                    }
                    body {
                        margin: 0;
                        padding: 0;
                        min-width: 100%;
                    }
                    .mso body {
                        mso-line-height-rule: exactly;
                    }
                    .no-padding .wrapper .column .column-top,
                    .no-padding .wrapper .column .column-bottom {
                        font-size: 0px;
                        line-height: 0px;
                    }
                    table {
                        border-collapse: collapse;
                        border-spacing: 0;
                    }
                    td {
                        padding: 0;
                        vertical-align: top;
                    }
                    .spacer,
                    .border {
                        font-size: 1px;
                        line-height: 1px;
                    }
                    .spacer {
                        width: 100%;
                    }
                    img {
                        border: 0;
                        -ms-interpolation-mode: bicubic;
                    }
                    .image {
                        font-size: 12px;
                        mso-line-height-rule: at-least;
                    }
                    .image img {
                        display: block;
                    }
                    .logo {
                        mso-line-height-rule: at-least;
                    }
                    .logo img {
                        display: block;
                    }
                    strong {
                        font-weight: bold;
                    }
                    h1,
                    h2,
                    h3,
                    p,
                    ol,
                    ul,
                    blockquote,
                    .image {
                        font-style: normal;
                        font-weight: 400;
                    }
                    ol,
                    ul,
                    li {
                        padding-left: 0;
                    }
                    blockquote {
                        Margin-left: 0;
                        Margin-right: 0;
                        padding-right: 0;
                    }
                    .column-top,
                    .column-bottom {
                        font-size: 32px;
                        line-height: 32px;
                        transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
                        transition-duration: 150ms;
                        transition-property: all;
                    }
                    .half-padding .column .column-top,
                    .half-padding .column .column-bottom {
                        font-size: 16px;
                        line-height: 16px;
                    }
                    .column {
                        text-align: left;
                    }
                    .contents {
                        table-layout: fixed;
                        width: 100%;
                    }
                    .padded {
                        padding-left: 32px;
                        padding-right: 32px;
                        word-break: break-word;
                        word-wrap: break-word;
                    }
                    .wrapper {
                        display: table;
                        table-layout: fixed;
                        width: 100%;
                        min-width: 620px;
                        -webkit-text-size-adjust: 100%;
                        -ms-text-size-adjust: 100%;
                    }
                    .wrapper a {
                        transition: opacity 0.2s ease-in;
                    }
                    table.wrapper {
                        table-layout: fixed;
                    }
                    .one-col,
                    .two-col,
                    .three-col {
                        Margin-left: auto;
                        Margin-right: auto;
                        width: 600px;
                    }
                    .centered {
                        Margin-left: auto;
                        Margin-right: auto;
                    }
                    .btn a {
                        border-radius: 3px;
                        display: inline-block;
                        font-size: 14px;
                        font-weight: 700;
                        line-height: 24px;
                        padding: 13px 35px 12px 35px;
                        text-align: center;
                        text-decoration: none !important;
                    }
                    .btn a:hover {
                        opacity: 0.8;
                    }
                    .two-col .btn a {
                        font-size: 12px;
                        line-height: 22px;
                        padding: 10px 28px;
                    }
                    .three-col .btn a,
                    .wrapper .narrower .btn a {
                        font-size: 11px;
                        line-height: 19px;
                        padding: 6px 18px 5px 18px;
                    }
                    @media only screen and (max-width: 620px) {
                        .btn a {
                            display: block !important;
                            font-size: 14px !important;
                            line-height: 24px !important;
                            padding: 13px 10px 12px 10px !important;
                        }
                    }
                    .two-col .column {
                        width: 300px;
                    }
                    .two-col .first .padded {
                        padding-left: 32px;
                        padding-right: 16px;
                    }
                    .two-col .second .padded {
                        padding-left: 16px;
                        padding-right: 32px;
                    }
                    .three-col .column {
                        width: 200px;
                    }
                    .three-col .first .padded {
                        padding-left: 32px;
                        padding-right: 8px;
                    }
                    .three-col .second .padded {
                        padding-left: 20px;
                        padding-right: 20px;
                    }
                    .three-col .third .padded {
                        padding-left: 8px;
                        padding-right: 32px;
                    }
                    .wider {
                        width: 400px;
                    }
                    .narrower {
                        width: 200px;
                    }
                    @media only screen and (min-width: 0) {
                        .wrapper {
                            text-rendering: optimizeLegibility;
                        }
                    }
                    @media only screen and (max-width: 620px) {
                        [class=wrapper] {
                            min-width: 320px !important;
                            width: 100% !important;
                        }
                        [class=wrapper] .one-col,
                        [class=wrapper] .two-col,
                        [class=wrapper] .three-col {
                            width: 320px !important;
                        }
                        [class=wrapper] .column,
                        [class=wrapper] .gutter {
                            display: block;
                            float: left;
                            width: 320px !important;
                        }
                        [class=wrapper] .padded {
                            padding-left: 20px !important;
                            padding-right: 20px !important;
                        }
                        [class=wrapper] .block {
                            display: block !important;
                        }
                        [class=wrapper] .hide {
                            display: none !important;
                        }
                        [class=wrapper] .image img {
                            height: auto !important;
                            width: 100% !important;
                        }
                    }
                    .footer {
                        width: 100%;
                    }
                    .footer .inner {
                        padding: 58px 0 29px 0;
                        width: 600px;
                    }
                    .footer .left td,
                    .footer .right td {
                        font-size: 12px;
                        line-height: 22px;
                    }
                    .footer .left td {
                        text-align: left;
                        width: 400px;
                    }
                    .footer .right td {
                        max-width: 200px;
                        mso-line-height-rule: at-least;
                    }
                    .footer .links {
                        line-height: 26px;
                        Margin-bottom: 26px;
                        mso-line-height-rule: at-least;
                    }
                    .footer .links a:hover {
                        opacity: 0.8;
                    }
                    .footer .links img {
                        vertical-align: middle;
                    }
                    .footer .address {
                        Margin-bottom: 18px;
                    }
                    .footer .campaign {
                        Margin-bottom: 18px;
                    }
                    .footer .campaign a {
                        font-weight: bold;
                        text-decoration: none;
                    }
                    .footer .sharing div {
                        Margin-bottom: 5px;
                    }
                    .wrapper .footer .fblike,
                    .wrapper .footer .tweet,
                    .wrapper .footer .linkedinshare,
                    .wrapper .footer .forwardtoafriend {
                        background-repeat: no-repeat;
                        background-size: 200px 56px;
                        border-radius: 2px;
                        color: #ffffff;
                        display: block;
                        font-size: 11px;
                        font-weight: bold;
                        line-height: 11px;
                        padding: 8px 11px 7px 28px;
                        text-align: left;
                        text-decoration: none;
                    }
                    .wrapper .footer .fblike:hover,
                    .wrapper .footer .tweet:hover,
                    .wrapper .footer .linkedinshare:hover,
                    .wrapper .footer .forwardtoafriend:hover {
                        color: #ffffff !important;
                        opacity: 0.8;
                    }

                    @media only screen and (max-width: 620px) {
                        .footer {
                            width: 320px !important;
                        }
                        .footer td {
                            display: none;
                        }
                        .footer .inner,
                        .footer .inner td {
                            display: block;
                            text-align: center !important;
                            max-width: 320px !important;
                            width: 320px !important;
                        }
                        .footer .sharing {
                            Margin-bottom: 40px;
                        }
                        .footer .sharing div {
                            display: inline-block;
                        }
                        .footer .fblike,
                        .footer .tweet,
                        .footer .linkedinshare,
                        .footer .forwardtoafriend {
                            display: inline-block !important;
                        }
                    }
                    .wrapper h1,
                    .wrapper h2,
                    .wrapper h3,
                    .wrapper p,
                    .wrapper ol,
                    .wrapper ul,
                    .wrapper li,
                    .wrapper blockquote,
                    .image,
                    .btn,
                    .divider {
                        Margin-bottom: 0;
                        Margin-top: 0;
                    }
                    .wrapper .column h1 + * {
                        Margin-top: 20px;
                    }
                    .wrapper .column h2 + * {
                        Margin-top: 16px;
                    }
                    .wrapper .column h3 + * {
                        Margin-top: 12px;
                    }
                    .wrapper .column p + *,
                    .wrapper .column ol + *,
                    .wrapper .column ul + *,
                    .wrapper .column blockquote + *,
                    .image + .contents td > :first-child {
                        Margin-top: 24px;
                    }
                    .contents:nth-last-child(n+3) h1:last-child,
                    .no-padding .contents:nth-last-child(n+2) h1:last-child {
                        Margin-bottom: 20px;
                    }
                    .contents:nth-last-child(n+3) h2:last-child,
                    .no-padding .contents:nth-last-child(n+2) h2:last-child {
                        Margin-bottom: 16px;
                    }
                    .contents:nth-last-child(n+3) h3:last-child,
                    .no-padding .contents:nth-last-child(n+2) h3:last-child {
                        Margin-bottom: 12px;
                    }
                    .contents:nth-last-child(n+3) p:last-child,
                    .no-padding .contents:nth-last-child(n+2) p:last-child,
                    .contents:nth-last-child(n+3) ol:last-child,
                    .no-padding .contents:nth-last-child(n+2) ol:last-child,
                    .contents:nth-last-child(n+3) ul:last-child,
                    .no-padding .contents:nth-last-child(n+2) ul:last-child,
                    .contents:nth-last-child(n+3) blockquote:last-child,
                    .no-padding .contents:nth-last-child(n+2) blockquote:last-child,
                    .contents:nth-last-child(n+3) .image,
                    .no-padding .contents:nth-last-child(n+2) .image,
                    .contents:nth-last-child(n+3) .divider,
                    .no-padding .contents:nth-last-child(n+2) .divider,
                    .contents:nth-last-child(n+3) .btn,
                    .no-padding .contents:nth-last-child(n+2) .btn {
                        Margin-bottom: 24px;
                    }
                    .two-col .column p + *,
                    .wider .column p + *,
                    .two-col .column ol + *,
                    .wider .column ol + *,
                    .two-col .column ul + *,
                    .wider .column ul + *,
                    .two-col .column blockquote + *,
                    .wider .column blockquote + *,
                    .two-col .image + .contents td > :first-child,
                    .wider .image + .contents td > :first-child {
                        Margin-top: 21px;
                    }
                    .two-col .contents:nth-last-child(n+3) p:last-child,
                    .wider .contents:nth-last-child(n+3) p:last-child,
                    .no-padding .two-col .contents:nth-last-child(n+2) p:last-child,
                    .no-padding .wider .contents:nth-last-child(n+2) p:last-child,
                    .two-col .contents:nth-last-child(n+3) ol:last-child,
                    .wider .contents:nth-last-child(n+3) ol:last-child,
                    .no-padding .two-col .contents:nth-last-child(n+2) ol:last-child,
                    .no-padding .wider .contents:nth-last-child(n+2) ol:last-child,
                    .two-col .contents:nth-last-child(n+3) ul:last-child,
                    .wider .contents:nth-last-child(n+3) ul:last-child,
                    .no-padding .two-col .contents:nth-last-child(n+2) ul:last-child,
                    .no-padding .wider .contents:nth-last-child(n+2) ul:last-child,
                    .two-col .contents:nth-last-child(n+3) blockquote:last-child,
                    .wider .contents:nth-last-child(n+3) blockquote:last-child,
                    .no-padding .two-col .contents:nth-last-child(n+2) blockquote:last-child,
                    .no-padding .wider .contents:nth-last-child(n+2) blockquote:last-child,
                    .two-col .contents:nth-last-child(n+3) .image,
                    .wider .contents:nth-last-child(n+3) .image,
                    .no-padding .two-col .contents:nth-last-child(n+2) .image,
                    .no-padding .wider .contents:nth-last-child(n+2) .image,
                    .two-col .contents:nth-last-child(n+3) .divider,
                    .wider .contents:nth-last-child(n+3) .divider,
                    .no-padding .two-col .contents:nth-last-child(n+2) .divider,
                    .no-padding .wider .contents:nth-last-child(n+2) .divider,
                    .two-col .contents:nth-last-child(n+3) .btn,
                    .wider .contents:nth-last-child(n+3) .btn,
                    .no-padding .two-col .contents:nth-last-child(n+2) .btn,
                    .no-padding .wider .contents:nth-last-child(n+2) .btn {
                        Margin-bottom: 21px;
                    }
                    .three-col .column p + *,
                    .narrower .column p + *,
                    .three-col .column ol + *,
                    .narrower .column ol + *,
                    .three-col .column ul + *,
                    .narrower .column ul + *,
                    .three-col .column blockquote + *,
                    .narrower .column blockquote + *,
                    .three-col .image + .contents td > :first-child,
                    .narrower .image + .contents td > :first-child {
                        Margin-top: 18px;
                    }
                    .three-col .contents:nth-last-child(n+3) p:last-child,
                    .narrower .contents:nth-last-child(n+3) p:last-child,
                    .no-padding .three-col .contents:nth-last-child(n+2) p:last-child,
                    .no-padding .narrower .contents:nth-last-child(n+2) p:last-child,
                    .three-col .contents:nth-last-child(n+3) ol:last-child,
                    .narrower .contents:nth-last-child(n+3) ol:last-child,
                    .no-padding .three-col .contents:nth-last-child(n+2) ol:last-child,
                    .no-padding .narrower .contents:nth-last-child(n+2) ol:last-child,
                    .three-col .contents:nth-last-child(n+3) ul:last-child,
                    .narrower .contents:nth-last-child(n+3) ul:last-child,
                    .no-padding .three-col .contents:nth-last-child(n+2) ul:last-child,
                    .no-padding .narrower .contents:nth-last-child(n+2) ul:last-child,
                    .three-col .contents:nth-last-child(n+3) blockquote:last-child,
                    .narrower .contents:nth-last-child(n+3) blockquote:last-child,
                    .no-padding .three-col .contents:nth-last-child(n+2) blockquote:last-child,
                    .no-padding .narrower .contents:nth-last-child(n+2) blockquote:last-child,
                    .three-col .contents:nth-last-child(n+3) .image,
                    .narrower .contents:nth-last-child(n+3) .image,
                    .no-padding .three-col .contents:nth-last-child(n+2) .image,
                    .no-padding .narrower .contents:nth-last-child(n+2) .image,
                    .three-col .contents:nth-last-child(n+3) .divider,
                    .narrower .contents:nth-last-child(n+3) .divider,
                    .no-padding .three-col .contents:nth-last-child(n+2) .divider,
                    .no-padding .narrower .contents:nth-last-child(n+2) .divider,
                    .three-col .contents:nth-last-child(n+3) .btn,
                    .narrower .contents:nth-last-child(n+3) .btn,
                    .no-padding .three-col .contents:nth-last-child(n+2) .btn,
                    .no-padding .narrower .contents:nth-last-child(n+2) .btn {
                        Margin-bottom: 18px;
                    }
                    @media only screen and (max-width: 620px) {
                        .wrapper p + *,
                        .wrapper ol + *,
                        .wrapper ul + *,
                        .wrapper blockquote + *,
                        .image + .contents td > :first-child {
                            Margin-top: 24px !important;
                        }
                        .contents:nth-last-child(n+3) p:last-child,
                        .no-padding .contents:nth-last-child(n+2) p:last-child,
                        .contents:nth-last-child(n+3) ol:last-child,
                        .no-padding .contents:nth-last-child(n+2) ol:last-child,
                        .contents:nth-last-child(n+3) ul:last-child,
                        .no-padding .contents:nth-last-child(n+2) ul:last-child,
                        .contents:nth-last-child(n+3) blockquote:last-child,
                        .no-padding .contents:nth-last-child(n+2) blockquote:last-child,
                        .contents:nth-last-child(n+3) .image:last-child,
                        .no-padding .contents:nth-last-child(n+2) .image:last-child,
                        .contents:nth-last-child(n+3) .divider:last-child,
                        .no-padding .contents:nth-last-child(n+2) .divider:last-child,
                        .contents:nth-last-child(n+3) .btn:last-child,
                        .no-padding .contents:nth-last-child(n+2) .btn:last-child {
                            Margin-bottom: 24px !important;
                        }
                    }
                    td.border {
                        width: 1px;
                    }
                    tr.border {
                        background-color: #e3e3e3;
                        height: 1px;
                    }
                    tr.border td {
                        line-height: 1px;
                    }
                    .sidebar {
                        width: 600px;
                    }
                    .first.wider .padded {
                        padding-left: 32px;
                        padding-right: 20px;
                    }
                    .second.wider .padded {
                        padding-left: 20px;
                        padding-right: 32px;
                    }
                    .first.narrower .padded {
                        padding-left: 32px;
                        padding-right: 8px;
                    }
                    .second.narrower .padded {
                        padding-left: 8px;
                        padding-right: 32px;
                    }
                    .wrapper h1 {
                        font-size: 40px;
                        line-height: 48px;
                    }
                    .wrapper h2 {
                        font-size: 24px;
                        line-height: 32px;
                    }
                    .wrapper h3 {
                        font-size: 18px;
                        line-height: 24px;
                    }
                    .wrapper h1 a,
                    .wrapper h2 a,
                    .wrapper h3 a {
                        text-decoration: none;
                    }
                    .wrapper a:hover {
                        text-decoration: none;
                    }
                    .wrapper p,
                    .wrapper ol,
                    .wrapper ul {
                        font-size: 15px;
                        line-height: 24px;
                    }
                    .wrapper ol,
                    .wrapper ul {
                        Margin-left: 20px;
                    }
                    .wrapper li {
                        padding-left: 2px;
                    }
                    .wrapper blockquote {
                        Margin-left: 0;
                        padding-left: 18px;
                    }
                    .one-col,
                    .two-col,
                    .three-col,
                    .sidebar {
                        background-color: #ffffff;
                        table-layout: fixed;
                    }
                    .wrapper .two-col blockquote,
                    .wrapper .wider blockquote {
                        padding-left: 16px;
                    }
                    .wrapper .three-col ol,
                    .wrapper .narrower ol,
                    .wrapper .three-col ul,
                    .wrapper .narrower ul {
                        Margin-left: 14px;
                    }
                    .wrapper .three-col li,
                    .wrapper .narrower li {
                        padding-left: 1px;
                    }
                    .wrapper .three-col blockquote,
                    .wrapper .narrower blockquote {
                        padding-left: 12px;
                    }
                    .wrapper h2 {
                        font-weight: 700;
                    }
                    .wrapper blockquote {
                        font-style: italic;
                    }
                    .preheader a,
                    .header a {
                        text-decoration: none;
                    }
                    .header {
                        Margin-left: auto;
                        Margin-right: auto;
                        width: 600px;
                    }
                    .preheader td {
                        padding-bottom: 24px;
                        padding-top: 24px;
                    }
                    .logo {
                        width: 280px;
                    }
                    .logo div {
                        font-weight: 700;
                    }
                    .logo div.logo-center {
                        text-align: center;
                    }
                    .logo div.logo-center img {
                        Margin-left: auto;
                        Margin-right: auto;
                    }
                    .preheader td {
                        text-align: right;
                        width: 280px;
                    }
                    .preheader td {
                        letter-spacing: 0.01em;
                        line-height: 17px;
                        font-weight: 400;
                    }
                    .preheader a {
                        letter-spacing: 0.03em;
                        font-weight: 700;
                    }
                    .preheader td {
                        font-size: 11px;
                    }
                    @media only screen and (max-width: 620px) {
                        [class=wrapper] .header,
                        [class=wrapper] .preheader td,
                        [class=wrapper] .logo,
                        [class=wrapper] .sidebar {
                            width: 320px !important;
                        }
                        [class=wrapper] .header .logo {
                            padding-left: 10px !important;
                            padding-right: 10px !important;
                        }
                        [class=wrapper] .header .logo img {
                            max-width: 280px !important;
                            height: auto !important;
                        }
                        [class=wrapper] .header .preheader td {
                            padding-top: 3px !important;
                            padding-bottom: 22px !important;
                        }
                        [class=wrapper] .header .title {
                            display: none !important;
                        }
                        [class=wrapper] .header .webversion {
                            text-align: center !important;
                        }
                        [class=wrapper] h1 {
                            font-size: 40px !important;
                            line-height: 48px !important;
                        }
                        [class=wrapper] h2 {
                            font-size: 24px !important;
                            line-height: 32px !important;
                        }
                        [class=wrapper] h3 {
                            font-size: 18px !important;
                            line-height: 24px !important;
                        }
                        [class=wrapper] .column p,
                        [class=wrapper] .column ol,
                        [class=wrapper] .column ul {
                            font-size: 15px !important;
                            line-height: 24px !important;
                        }
                        [class=wrapper] ol,
                        [class=wrapper] ul {
                            Margin-left: 20px !important;
                        }
                        [class=wrapper] li {
                            padding-left: 2px !important;
                        }
                        [class=wrapper] blockquote {
                            border-left-width: 4px !important;
                            padding-left: 18px !important;
                        }
                        [class=wrapper] table.border {
                            width: 320px !important;
                        }
                        [class=wrapper] .second .column-top,
                        [class=wrapper] .third .column-top {
                            display: none;
                        }
                    }
                    @media only screen and (max-width: 320px) {
                        td.border {
                            display: none;
                        }
                    }
                </style>
                <!--[if !mso]><!-->
                <style type="text/css">

                    @import url(https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,700,400);
                </style><link href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,700,400" rel="stylesheet" type="text/css"><!--<![endif]--><style type="text/css">
                    .wrapper h1{}.wrapper h1{font-family:\'Open Sans\',sans-serif}.mso .wrapper h1{font-family:sans-serif !important}.wrapper h2{}.wrapper h2{font-family:\'Open Sans\',sans-serif}.mso .wrapper h2{font-family:sans-serif !important}.wrapper h3{}.wrapper h3{font-family:\'Open Sans\',sans-serif}.mso .wrapper h3{font-family:sans-serif !important}.wrapper p,.wrapper ol,.wrapper ul,.wrapper .image{}.wrapper p,.wrapper ol,.wrapper ul,.wrapper .image{font-family:\'Open Sans\',sans-serif}.mso .wrapper p,.mso .wrapper ol,.mso .wrapper ul,.mso .wrapper .image{font-family:sans-serif !important}.wrapper .btn a{}.wrapper .btn a{font-family:\'Open Sans\',sans-serif}.mso .wrapper .btn a{font-family:sans-serif !important}.logo div{}.logo div{font-family:Avenir,sans-serif}.mso .logo div{font-family:sans-serif
                    !important}.title,.webversion,.fblike,.tweet,.linkedinshare,.forwardtoafriend,.link,.address,.permission,.campaign{}.title,.webversion,.fblike,.tweet,.linkedinshare,.forwardtoafriend,.link,.address,.permission,.campaign{font-family:\'Open Sans\',sans-serif}.mso .title,.mso .webversion,.mso .fblike,.mso .tweet,.mso .linkedinshare,.mso .forwardtoafriend,.mso .link,.mso .address,.mso .permission,.mso .campaign{font-family:sans-serif !important}body,.wrapper,.emb-editor-canvas{background-color:#f5f7fa}.border{background-color:#dddee1}.wrapper h1{color:#44a8c7}.wrapper h2{color:#44a8c7}.wrapper h3{color:#3b3e42}.wrapper p,.wrapper ol,.wrapper ul{color:#60666d}.wrapper .image{color:#60666d}.wrapper a{color:#5c91ad}.wrapper a:hover{color:#48768e !important}.wrapper blockquote{border-left:4px solid #f5f7fa}.wrapper .three-col blockquote,.wrapper .narrower blockquote{border-left:2px solid
                    #f5f7fa}.wrapper .btn a{background-color:#5c91ad;color:#fff}.wrapper .btn a:hover{color:#fff !important}.logo div{color:#555}.logo div a{color:#555}.logo div a:hover{color:#555 !important}.title,.webversion,.footer .inner td{color:#b9b9b9}.wrapper .title a,.wrapper .webversion a,.wrapper .footer a{color:#b9b9b9}.wrapper .title a:hover,.wrapper .webversion a:hover,.wrapper .footer a:hover{color:#939393 !important}.wrapper .footer .fblike,.wrapper .footer .tweet,.wrapper .footer .linkedinshare,.wrapper .footer .forwardtoafriend{background-color:#7b7c7d}
                </style><meta name="robots" content="noindex,nofollow"></meta>
                <meta property="og:title" content="My First Campaign"></meta>
            </head>
        <!--[if mso]>
        <body class="mso">
        <![endif]-->
        <!--[if !mso]><!-->
        <body class="full-padding" style="margin: 0;padding: 0;min-width: 100%;background-color: #f5f7fa;">
    <!--<![endif]-->
    <center class="wrapper" style="display: table;table-layout: fixed;width: 100%;min-width: 620px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;background-color: #f5f7fa;">
        <table class="header centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 600px;">
            <tbody><tr>
                <td style="padding: 0;vertical-align: top;">
                    <table class="preheader" style="border-collapse: collapse;border-spacing: 0;" align="right">
                        <tbody><tr>
                            <td class="emb-logo-padding-box" style="padding: 0;vertical-align: top;padding-bottom: 24px;padding-top: 24px;text-align: right;width: 280px;letter-spacing: 0.01em;line-height: 17px;font-weight: 400;font-size: 11px;">
                                <div class="spacer" style="font-size: 1px;line-height: 2px;width: 100%;">&nbsp;</div>


                            </td>
                        </tr>
                        </tbody></table>
                    <table style="border-collapse: collapse;border-spacing: 0;" align="left">
                        <tbody><tr>
                            <td class="logo emb-logo-padding-box" style="padding: 0;vertical-align: top;mso-line-height-rule: at-least;width: 280px;padding-top: 24px;padding-bottom: 24px;">
                                <div class="logo-left" style="font-weight: 700;font-family: Avenir,sans-serif;color: #555;font-size: 0px !important;line-height: 0 !important;" align="left" id="emb-email-header">
                                    <a style="text-decoration: none;transition: opacity 0.2s ease-in;color: #555;" href="http://tacticgame.es/">
                                        <img style="border: 0;-ms-interpolation-mode: bicubic;display: block;max-width: 287px;" src="http://tacticgame.es/images/mail/logo.png" alt="" width="287" height="37">
                                    </a>
                               </div>
                            </td>
                        </tr>
                        </tbody></table>
                </td>
            </tr>
            </tbody></table>

        <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #dddee1;Margin-left: auto;Margin-right: auto;" width="602">
            <tbody><tr><td style="padding: 0;vertical-align: top;">&#8203;</td></tr>
            </tbody></table>

        <table class="centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;">
            <tbody><tr>
                <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #dddee1;width: 1px;">&#8203;</td>
                <td style="padding: 0;vertical-align: top;">
                    <table class="one-col" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 600px;background-color: #ffffff;table-layout: fixed;" emb-background-style>
                        <tbody><tr>
                            <td class="column" style="padding: 0;vertical-align: top;text-align: left;">

                                <div class="image" style="font-size: 12px;mso-line-height-rule: at-least;font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0;font-family: \'Open Sans\',sans-serif;color: #60666d;" align="center">
                                    <a style="transition: opacity 0.2s ease-in;color: #5c91ad;" href="http://tacticgame.es/">
                                    <img class="gnd-corner-image gnd-corner-image-center gnd-corner-image-top" style="border: 0;-ms-interpolation-mode: bicubic;display: block;max-width: 720px;" src="http://tacticgame.es/images/mail/_5MjmmE.jpg" alt="'.$quest.'" width="600" height="400"></a>
                                </div>

                                <table class="contents" style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;">
                                    <tbody><tr>
                                        <td class="padded" style="padding: 0;vertical-align: top;padding-left: 32px;padding-right: 32px;word-break: break-word;word-wrap: break-word;">

                                            <div style="Margin-top: 24px;line-height: 15px;font-size: 1px;">&nbsp;</div>

                                        </td>
                                    </tr>
                                    </tbody></table>

                                <table class="contents" style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;">
                                    <tbody><tr>
                                        <td class="padded" style="padding: 0;vertical-align: top;padding-left: 32px;padding-right: 32px;word-break: break-word;word-wrap: break-word;">

                                            <h2 style="font-style: normal;font-weight: 700;Margin-bottom: 0;Margin-top: 0;font-size: 24px;line-height: 32px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;text-align: center;"><span style="color:#4a4245">'.$thanks.'</span>&nbsp;</h2>
                                            <h3 style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 16px;font-size: 18px;line-height: 24px;font-family: \'Open Sans\',sans-serif;color: #3b3e42;text-align: center;">
                                                <span>'.$thanks_body.'</span>
                                            </h3>

                                        </td>

                                    </tr>
                                    </tbody></table>

                                <div class="column-bottom" style="font-size: 32px;line-height: 32px;transition-timing-function: cubic-bezier(0, 0, 0.2, 1);transition-duration: 150ms;transition-property: all;">&nbsp;</div>
                            </td>
                        </tr>
                        </tbody></table>
                </td>
                <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #dddee1;width: 1px;">&#8203;</td>
            </tr>
            </tbody></table>

        <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #dddee1;Margin-left: auto;Margin-right: auto;" width="602">
            <tbody><tr class="border" style="font-size: 1px;line-height: 1px;background-color: #e3e3e3;height: 1px;">
                <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #dddee1;width: 1px;">&#8203;</td>
                <td style="padding: 0;vertical-align: top;line-height: 1px;">&#8203;</td>
                <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #dddee1;width: 1px;">&#8203;</td>
            </tr>
            </tbody></table>

        <table class="centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;">
            <tbody><tr>
                <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #dddee1;width: 1px;">&#8203;</td>
                <td style="padding: 0;vertical-align: top;">
                    <table class="one-col" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 600px;background-color: #ffffff;table-layout: fixed;" emb-background-style>
                        <tbody><tr>
                            <td class="column" style="padding: 0;vertical-align: top;text-align: left;">
                                <div><div class="column-top" style="font-size: 32px;line-height: 32px;transition-timing-function: cubic-bezier(0, 0, 0.2, 1);transition-duration: 150ms;transition-property: all;">&nbsp;</div></div>
                                <table class="contents" style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;">
                                    <tbody><tr>
                                        <td class="padded" style="padding: 0;vertical-align: top;padding-left: 32px;padding-right: 32px;word-break: break-word;word-wrap: break-word;">

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 0;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','Ticket number').':</span>
                                            </h2>

                                            <h1 style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0;font-size: 16px;line-height: 26px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#4c4145">'.$ticket_number.'</span>
                                            </h1>

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 20px;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','Datetime').':</span>
                                            </h2>
                                            <p style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0px;font-size: 15px;line-height: 24px;font-family: \'Open Sans\',sans-serif;color: #60666d;">
                                                <span>'.$datetime.'</span>
                                            </p>

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 20px;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','Gamers').':</span>
                                            </h2>
                                            <p style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0px;font-size: 15px;line-height: 24px;font-family: \'Open Sans\',sans-serif;color: #60666d;">
                                                <span>'.$gamers.'</span>
                                            </p>

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 20px;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','Duration').':</span>
                                            </h2>
                                            <p style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0px;font-size: 15px;line-height: 24px;font-family: \'Open Sans\',sans-serif;color: #60666d;">
                                                <span>'.$duration.'</span>
                                            </p>

                                        </td>
                                        <td class="padded" style="padding: 0;vertical-align: top;padding-left: 32px;padding-right: 32px;word-break: break-word;word-wrap: break-word;">

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 0;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','QUEST').'</span>
                                            </h2>

                                            <h1 style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0;font-size: 16px;line-height: 26px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#4c4145">'.$quest.'</span>
                                            </h1>

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 20px;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','Price').':</span>
                                            </h2>
                                            <p style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0px;font-size: 15px;line-height: 24px;font-family: \'Open Sans\',sans-serif;color: #60666d;">
                                                <span>'.$price.'</span>
                                            </p>

                                            <h2 style="font-style: normal;font-weight: 600;Margin-bottom: 0;Margin-top: 20px;font-size: 16px;line-height:20px;font-family: \'Open Sans\',sans-serif;color: #44a8c7;">
                                                <span style="color:#3d4b4f">'.Yii::t('app','Address').':</span>
                                            </h2>
                                            <p style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0px;font-size: 15px;line-height: 24px;font-family: \'Open Sans\',sans-serif;color: #60666d;">'.$address.'</p>
                                        </td>
                                    </tr>
                                    </tbody></table>

                                <div class="column-bottom" style="font-size: 32px;line-height: 32px;transition-timing-function: cubic-bezier(0, 0, 0.2, 1);transition-duration: 150ms;transition-property: all;">&nbsp;</div>
                            </td>
                        </tr>
                        </tbody></table>
                </td>
                <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #dddee1;width: 1px;">&#8203;</td>
            </tr>
            </tbody></table>

        <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #dddee1;Margin-left: auto;Margin-right: auto;" width="602">
            <tbody><tr><td style="padding: 0;vertical-align: top;">&nbsp;</td></tr>
            </tbody></table>

        <table class="footer centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 100%;">
            <tbody><tr>
                <td style="padding: 0;vertical-align: top;">&nbsp;</td>
                <td class="inner" style="padding: 58px 0 29px 0;vertical-align: top;width: 600px;">

                    <table class="left" style="border-collapse: collapse;border-spacing: 0;" align="left">
                        <tbody><tr>
                            <td style="padding: 0;vertical-align: top;color: #b9b9b9;font-size: 12px;line-height: 22px;text-align: left;width: 400px;">

                                <div class="address" style="font-family: \'Open Sans\',sans-serif;Margin-bottom: 18px;">
                                    <div>&#169; Tactic Games LTD, 2015 - '.date('Y').'</div>
                                </div>

                            </td>

                            <td style="padding: 0;vertical-align: top;color: #b9b9b9;font-size: 12px;line-height: 22px;text-align: right;width: 400px;">
                                <div class="links emb-web-links" style="line-height: 26px;Margin-bottom: 26px;mso-line-height-rule: at-least;">
                                    <a style="transition: opacity 0.2s ease-in;color: #b9b9b9;" href="https://www.facebook.com/tacticgame.es/">
                                        <img style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;" src="http://tacticgame.es/images/mail/facebook-sf.png" width="29" height="26">
                                    </a>

                                    <a style="transition: opacity 0.2s ease-in;color: #b9b9b9;" href="http://vk.com/club102014074/">
                                        <img style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;" src="http://tacticgame.es/images/mail/vk-sf.png" width="29" height="26">
                                    </a>


                                    <a style="transition: opacity 0.2s ease-in;color: #b9b9b9;" href="https://instagram.com/tacticescaperoom.es/"><img style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;" src="http://tacticgame.es/images/mail/instagram-sf.png" width="29" height="26"></a>
                                </div>

                            </td>


                        </tr>
                        </tbody></table>
                </td>
                <td style="padding: 0;vertical-align: top;">&nbsp;</td>
            </tr>
            </tbody></table>
        </center>
        </body></html>';
        //<img style="border: 0 !important;-ms-interpolation-mode: bicubic;visibility: hidden !important;display: block !important;height: 1px !important;width: 1px !important;margin: 0 !important;padding: 0 !important;" src="https://tacticgame.createsend1.com/t/i-o-ijjjduk-l/o.gif" width="1" height="1" border="0" alt="">
        //$email

        $to = $email;
        $headers     = "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type: text/html; charset=utf-8\r\n";
        $headers    .= "From: <info@tacticgame.es>\r\n";

        //Yii::$app->SmtpSendEmail->Send($to, $subject, $emailHtml);

        if(mail($to,$subject,$emailHtml,$headers))
            return 1;
        else
            return 0;
    }

    private function SaveTransaction($code,$transaction_id,$transaction_currency,$transaction_amount,$transaction_method,$transaction_state,$transaction_time=""){
        $model= new Transaction();
        $model->code = $code;
        $model->transaction_id = $transaction_id ;
        $model->transaction_currency = $transaction_currency;
        $model->transaction_amount = $transaction_amount;
        $model->transaction_method =  $transaction_method;
        $model->transaction_state = $transaction_state;
        $model->transaction_time = $transaction_time;
        $model->save();
    }

    private function UpdateStatus($code){
        Order::updateAll(['status'=>1,'check'=>1],['code'=>$code]);
    }

    protected function findModel($id)
    {
        if (($model = Sections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function diffDateTime($datetime,$time){
        $rowTime = explode(':',$time);
        $a = strtotime('+'.$rowTime[0].' hour +'.$rowTime[1].' minutes',strtotime($datetime));
        $b = strtotime("now");
        return  $a-$b;
    }



}
