<?php

namespace frontend\controllers;

use Yii;
use backend\models\Certification;
use backend\models\CertificationSearch;
use backend\models\Certificationorder;
use backend\models\Certificatsection;
use backend\models\CertificatsectionSearch;
use backend\models\Transaction;
use backend\models\Certificatorder;

use yii\imagine\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kholmatov\lacaixa\RedsysWDG;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class CertificationController extends Controller
{

    public $currentUrl = '';
    public $success_url = '';
    public $cancel_url = '';
    public $notifc_url = '';


    public function init()
    {
        parent::init();
        $this->currentUrl = Yii::$app->request->serverName;
        $this->success_url = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/certification/success';
        $this->cancel_url = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/certification/cancel';
        $this->notifc_url = 'http://'.$this->currentUrl.'/'.Yii::$app->language.'/certification/notification';
    }


    public function behaviors()
    {
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

    /**
     * Lists all Items models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $searchModel = new ItemsSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    /**
     * Displays a single Items model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->language=='es-ES' || Yii::$app->language=='es'):
            $model->title = $model->title_es;
            $model->text = $model->text_es;
        endif;
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    private function goMail($code,$name,$phone,$text){
        $tm = new \DateTime('now');

        $now = Yii::$app->formatter->asDate($tm,'php:Y-m-d H:i:s');


        $emailHtml = "
            <table width='800' border='1' bordercolor='#B6B6B6' align='center' cellspacing='0' cellpadding='0' style='border:1px solid #B6B6B6; border-collapse:collapse; background-color:#FFF; margin-top:15px; margin-bottom:10px;'>
            <tr><td colspan='3' style='text-align:center; background-color:#e7deab; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0d0d0d; font-weight:bold; padding:15px;'>".$name.":&nbsp;&nbsp;&nbsp;[&nbsp;".$now."&nbsp;]</td>

             <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Code').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$code."</td>
            </tr>

            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Name').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$name."</td>
            </tr>

            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Phone').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$phone."</td>
            </tr>
            <tr>
            <td align='left' valign='top' width='270' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6D6D6D; font-weight:bold; background-color:#f3efd9; padding:10px 5px 10px 10px;'>".Yii::t('app','Text').":</td>
            <td colspan='2' align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:normal; padding:10px 5px 10px 10px;'>".$text."</td>
            </tr>
            </table>
         ";

        $to = "tacticgame.es@gmail.com";
        $headers     = "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type: text/html; charset=utf-8\r\n";
        $headers    .= "From: <info@tacticgame.es>\r\n";
        $subject=Yii::t('app','New order Certification').': '.$code;
        Yii::$app->SmtpSendEmail->Send($to, $subject, $emailHtml,$headers);
        if(mail($to,$subject,$emailHtml,$headers))
            return 1;
        else
            return 0;
    }


    public function actionOrders(){
        $post = Yii::$app->request->post();
        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $code = strtoupper($this->generateRandomString(3));
       //`id`, `code`, `name`, `phone`, `text`, `creatdate`, `status`
        $model = new Certificationorder;
        $model->name = $post['user-name'];
        $model->phone = $post['user-phone'];
        $model->text = $post['user-message'];
        $model->creatdate = $now;
        $model->code = $code;
        $model->status = 0;
        if($model->save()){
            $this->goMail($code,$post['user-name'],$post['user-phone'],$post['user-message']);
            echo $code;
            exit();
        }
    }

    public function actionCertorder(){

        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $request = Yii::$app->request;
        if ($request->isGet)  {
            /* the request method is GET */
            $post = $request->get();
        }
        if ($request->isPost) {
            /* the request method is POST */
            $post = $request->post();
        }
        $code = strtoupper($this->generateRandomString(2));
        $certificate = strtoupper($this->generateRandomString(4));
        $phone = $post['client_phone'];
        $email = $post['client_email'];
        $_id = $post['cert_id'];
        $tarif = $post['client_tarif'];
        $model = $this->findModel($_id);

        if(Yii::$app->language=='es-ES' || Yii::$app->language=='es'):
            $language="";
            $title = $model->title_es;
        else:
            $language="002";
            $title = $model->title;
        endif;

        $period = $model->period;
        $gamers = explode('|', $model->gamers);
        foreach ($gamers as $rows) {
            $gamer = explode('-', $rows);
            if($gamer[0] == $tarif){
                $amount = $gamer[1];
                break;
            }
        }

        $pDesc = $title.' '.$code.' '.$tarif.' - '.$amount.'€';
        $certO = new Certificatorder();
        $certO->order = $code;
        $certO->code = $certificate.$tarif;
        $certO->sect_id = $_id;
        $certO->price = $amount;
        $certO->email = $email;
        $certO->phone = $phone;
        $certO->createdate =  $now;
        $certO->comment = $title.' '.$code.' '.$tarif.' - '.$amount.'€';
        $certO->period = $period;
        $certO->users = $tarif;
        $certO->save();
        //$this->SaveTransaction($code, '++++++', '978', $amount, 'lacaixa', 'none',$now);
        echo RedsysWDG::getFormDataJson($code,$amount*100,$language,$pDesc, $this->success_url, $this->cancel_url, $this->notifc_url);
        exit();
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
        $current .= "\n==========Certificate===============\n";

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
                    $row = Certificatorder::getTiket($code);
                    if(isset($row)&&$row!="") {
                       echo $this->sendMailToClient($code, $row['code'], $row['createdate'], $row['period'], $row['users'], $row['price'], $row['email']);
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

    public function actionCancel(){
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
        return $this->redirect(array('/'));
    }

    public function actionSuccess(){
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
        if(isset($get) && isset($get['Ds_SignatureVersion']) && isset($get['Ds_MerchantParameters']) && isset($get['Ds_Signature'])):
            $rs = RedsysWDG::checkData($get['Ds_SignatureVersion'],$get['Ds_MerchantParameters'],$get['Ds_Signature']);
            if($rs){
                $rsParam = RedsysWDG::decodeData($get['Ds_MerchantParameters']);
                $myParam = json_decode($rsParam,true);
                return $this->redirect(array('certification/ticket','order'=> $myParam['Ds_Order']));
            }
        endif;
        return $this->redirect(array('/'));
    }


    public function actionTicket(){
        $get = Yii::$app->request->get();
        if(isset($get['order'])){
            $row = Certificatorder::getTiket($get['order']);
            if(isset($row)&&$row=="") return $this->redirect(array('/'));
            //if(count($row) > 0) $address = Sections::getAddress($row['id']);
            return $this->render('ticket', [
                'model' => '',
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

    protected function generateRandomString($length = 32)
    {
        $bytes = Yii::$app->security->generateRandomKey($length);
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
        return rand(1000,9000).strtr(substr(base64_encode($bytes), 0, $length), '+/_-', 'psnm');
    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Certification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function SaveTransaction($code,$transaction_id,$transaction_currency,$transaction_amount,$transaction_method,$transaction_state,$transaction_time=""){
        $trans = new Transaction();
        $trans->code = $code;
        $trans->transaction_id = $transaction_id ;
        $trans->transaction_currency = $transaction_currency;
        $trans->transaction_amount = $transaction_amount;
        $trans->transaction_method =  $transaction_method;
        $trans->transaction_state = $transaction_state;
        $trans->transaction_time = $transaction_time;
        if(!$trans->save()) echo $trans->getErrors();

  }

    private function UpdateStatus($code){
        Certificatorder::updateAll(['status'=>1],['order'=>$code]);
    }

    public function actionTestsend(){
        echo $this->sendMailToClient('NR345', '123456','07/07/2017',6, 5, '230.00', 'shohin002@mail.ru');
        exit();
    }

    private function sendMailToClient($order, $code, $date, $expired, $gamers, $amount, $to){
//        $emailHtml = "<b>Order Number:</b> ".$order."<br>";
//        $emailHtml .= "<b>".Yii::t('app','Bonus gift')."</b>: ".$code."<br>";
//        $emailHtml .= "<b>".Yii::t('app','Date of purchase')."</b>: ".$date."<br>";
//        $emailHtml .= "<b>".Yii::t('app','Expired date')."</b>: ".$date."<br>";
//        $emailHtml .= "<b>".Yii::t('app','Gamers')."</b>: ".$gamers."<br>";
//        $emailHtml .= "<b>".Yii::t('app','Price')."</b>: ".$amount."<br>";
//
        $emailHtml = "Gracias por comprar nuestro Bono Regalo!<br>";

        $emailHtml .= "Es el detalle perfecto para que sus amigos o familiares vivan nuevas experiencias. Un juego en vivo donde el regalo se convertirá en una aventura en primera persona.<br>";

        $emailHtml .= "Para hacer uso del cupón siga los siguientes pasos:<br>
	    1: En nuestra página Web www.tacticgame.es escoja una de las dos salas.<br>
        2: Seleccione cualquier hora libre que mas le convenga para llevar a cabo la actividad.<br>
        3: Elija el número de jugadores para el que es válido el cupón.<br>
        4: Rellene los campos con su información de contacto.<br>
        5: Y por último, en el método de pago marque “Bono Regalo” y introduzca el código de autorización.<br>
        <br>
        Regala este bono para que tus amigos o familiares descubran una aventura física e intrépida. Mediante este bono, podrás reservar una partida en una de nuestras salas de juego para cualquier día y hora en nuestra página web.
        Será una experiencia única y fascinante que dará que hablar, pensar y reír.<br><br>";

        //valencia_cupon_temp.jpg
        //Yii::$app->params['urlUploadGalleryPath']
        //$to = "tacticgame.es@gmail.com";
        $fontFile =  Yii::getAlias('@frontend').'/web/upload/fonts/myriadpro/MyriadPro-Regular.otf';
        $image_name = $code.'.jpg';
        $runtimeImageName = Yii::getAlias('@frontend').'/web/upload/temp/'.$image_name;
        $img = Image::text(Yii::getAlias('@frontend/web/upload/certification/cupon_temp').'.jpg', $code, $fontFile, [527, 584], ['size' => 11, 'color' => '00ff24']);
        $img->save($runtimeImageName);
        $img = Image::text($runtimeImageName, $gamers, $fontFile, [437, 483], ['size' => 18, 'color' => '00ff24']);
        $img->save($runtimeImageName);
        $img = Image::text($runtimeImageName, $date, $fontFile, [230, 567], ['size' => 12, 'color' => '00ff24']);
        $img->save($runtimeImageName);


        $emailHtml .= '<img style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;" src="http://tacticgame.es/upload/temp/'.$image_name.'" width="100%" >';

        //$this->assertTrue(file_exists($this->runtimeTextFile));


        $headers     = "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type: text/html; charset=utf-8\r\n";
        $headers    .= "From: <info@tacticgame.es>\r\n";
        $subject = Yii::t('app','Bonus gift').': '.$code;

        //Yii::$app->SmtpSendEmail->Send($to, $subject, $emailHtml,$headers);

        if(mail($to,$subject,$emailHtml,$headers))
            return 1;
        else
            return 0;
    }

}
