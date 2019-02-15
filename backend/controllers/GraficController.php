<?php

namespace backend\controllers;

use Faker\Provider\cs_CZ\DateTime;
use Yii;
use backend\models\Grafic;
use backend\models\GraficSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\ArrayHelper;

/**
 * GraficController implements the CRUD actions for Grafic model.
 */
class GraficController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','getevent','makefields','addedit','addevent'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Grafic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GraficSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Grafic();
        $sections = $model->getSections();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'sections'=>$sections
        ]);
     }

    /**
     * Displays a single Grafic model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Grafic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Grafic();
        $sections=$model->getSections();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'sections' => $sections
            ]);
        }
    }

    /**
     * Updates an existing Grafic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Grafic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Grafic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grafic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grafic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function  actionAddevent(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post=Yii::$app->request->post();
        if(isset($post['sectionid'])){
            $sectionid = $post['sectionid'];
            $title=$post['title'];
            $post_startdate=$post['startdate'];
            $post_enddate = $post['enddate'];

            $start = Yii::$app->formatter->asDate($post_startdate,'dd.MM.yyyy');
            $end = Yii::$app->formatter->asDate($post_enddate,'dd.MM.yyyy');

            $_start = Yii::$app->formatter->asDate($post_startdate,'yyyy-MM-dd');
            $_end = Yii::$app->formatter->asDate($post_enddate,'yyyy-MM-dd');

            $modelGrafic = new Grafic();

            $models = Grafic::find()
                ->where('date >= :start AND date < :end AND section_item_id = :sectionid',
                    ['start'=>$_start, 'end'=>$_end, 'sectionid'=>$sectionid])
                ->all();

            foreach($models as $item){
               $haveDate[] = $item->date;
            }


            $days = $this->diffDate($start,$end);

            if($days > 1){

                for($i=0; $i < $days; $i++){

                    $startDate = $this->EventAddDays($post_startdate,$i);

                    $haveStatus = false;
                    if(isset($haveDate)){
                        if(in_array($startDate,$haveDate))
                           $haveStatus = true;

                    }

                    if(!$haveStatus){
                        $rows[] = [
                            'section_item_id' => $sectionid,
                            'date' => $startDate,
                            'timearray' => $title
                        ];

                        $json[]=['id'=>$i,
                               'title'=>$title,
                               'start'=> $startDate,
                               'enddate'=> $startDate,
                               'allDays'=>true
                        ];
                    }
                }

                if(isset($rows))
                    $modelGrafic->multiInsert($rows);

            }else{


                $haveStatus = false;
                if(isset($haveDate)){
                    $_startDate = Yii::$app->formatter->asDate($post_startdate,'Y-m-dd');
                    if(in_array($_startDate,$haveDate))
                        $haveStatus = true;
                }

                if(!$haveStatus){
                    $modelGrafic->section_item_id = $sectionid;
                    $modelGrafic->date = $post_startdate;
                    $modelGrafic->timearray = $title;
                    $modelGrafic->save();


                    $json[]=['id'=>1,
                        'title'=>$title,
                        'start'=> $post_startdate,
                        'enddate'=> $post['enddate'],
                        'allDays'=>true
                    ];
                }
            }
            if(isset($json))
                return  $response[] = $json;
        }

    }

    public function actionGetevent(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $get=Yii::$app->request->get();
        $start = $get['start'];//Yii::$app->formatter->asDate($get['start'],'dd.MM.yyyy');
        $end = $get['end'];//Yii::$app->formatter->asDate($get['end'],'dd.MM.yyyy');
        //$end= $this->parseDateTime($get['end']);
        //$end = $this->stripTime($end);

        $models = Grafic::find()
            ->where('date >= :start AND date < :end AND section_item_id = :sectionid',
                ['start'=>$start, 'end'=>$end, 'sectionid'=>$get['sectionid']])
            ->all();
        if($models){
            foreach($models as $item){
                if($item->active==1)
                   $colorStatus='#82FA58';
                else
                    $colorStatus='#ff9f89';

                $json[]=['id' => $item->id,
                    'title' => $item->timearray,
                    'start' => $item->date,
                    'enddate' => $item->date,
                    'allDays' => true,
                    'active' => $item->active,
                    'color'=>$colorStatus,
                    'rendering'=>'background'
                ];
            }
        }

        if(isset($json))
            return  $response[] = $json;
    }

    private function parseDateTime($string, $timezone=null) {
        $start_date = new \DateTime();
        $start_date->setTimestamp($string);
//        $date = new \DateTime(
//            $string,
//            $timezone ? $timezone : new \DateTimeZone('UTC')
//        // Used only when the string is ambiguous.
//        // Ignored if string has a timezone offset in it.
//        );
//        if ($timezone) {
//            // If our timezone was ignored above, force it.
//            $date->setTimezone($timezone);
//        }
        return $start_date;
    }
    private  function stripTime($datetime) {
        return new \DateTime($datetime->format('Y-m-d'));
    }

    public function  actionMakefields(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $get=Yii::$app->request->get();

        $start = Yii::$app->formatter->asDate($get['start'],'dd.MM.yyyy');
        $end = Yii::$app->formatter->asDate($get['end'],'dd.MM.yyyy');

        $_start = Yii::$app->formatter->asDate($get['start'],'yyyy-MM-dd');
        $_end = Yii::$app->formatter->asDate($get['end'],'yyyy-MM-dd');

        $models = Grafic::find()
            ->where('date >= :start AND date < :end AND section_item_id = :sectionid',
                ['start'=>$_start, 'end'=>$_end, 'sectionid'=>$get['sectionid']])
            ->all();

        foreach($models as $item){
            $json[]=[
                'id' => $item->id,
                'title' => $item->timearray,
                'start' => $item->date,
                'enddate' => $item->date,
                'allDays' => true,
                'active' => $item->active
            ];

            $haveDate[] = $item->date;
        }
        if(isset($json)){
            $days = $this->diffDate($start,$end);

            if($days > 1){

                for($i=0; $i < $days; $i++){
                    $startDate = $this->EventAddDays($start,$i);

                    if(!in_array($startDate,$haveDate)){

                        $json[]=[
                            'id' => $i,
                            'title' => '',
                            'start' => $startDate,
                            'enddate' => $startDate,
                            'allDays' => false,
                            'active' => 0
                        ];
                    }
                }
            }

        }else{
            $days = $this->diffDate($start,$end);
            if($days > 1){
                for($i=0; $i < $days; $i++){
                    $startDate = $this->EventAddDays($start,$i);

                        $json[]=[
                            'id' => $i,
                            'title' => '',
                            'start' => $startDate,
                            'enddate' => $startDate,
                            'allDays' => false,
                            'active' => 0
                        ];
                }
            }else{

                    $json[]=[
                        'id' => 0,
                        'title' => '',
                        'start' => $start,
                        'enddate' => $start,
                        'allDays' => false,
                        'active' => 0
                    ];

            }

        }

        ArrayHelper::multisort($json, ['start'], [SORT_ASC]);
        return  $response[] = $json;
    }

    public function actionAddedit(){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $sectionid = $post['sectionid'];
        $start = $post['start'];
        $end = $post['end'];
        $_start = Yii::$app->formatter->asDate($start,'yyyy-MM-dd');
        $_end = Yii::$app->formatter->asDate($end,'yyyy-MM-dd');
        $models = Grafic::find()
            ->where('date >= :start AND date < :end AND section_item_id = :sectionid',
                ['start'=>$_start, 'end'=>$_end, 'sectionid'=>$sectionid])
            ->all();


        foreach($models as $item){
            $i=Yii::$app->formatter->asDate($item->date,'yyyymmdd');
            $update[$i]=[
                'id' => $item->id,
                'title' => $item->timearray,
                'start' => $item->date,
                'enddate' => $item->date,
                'allDays' => true,
                'active' => $item->active
            ];

            $haveDate[] = $item->date;
        }

        $arrayData= Json::decode($post['arraydata']);
        foreach($arrayData as $item){
            if(isset($haveDate)){
                if(in_array($item['date'],$haveDate)){
                  //Update
                  $i=Yii::$app->formatter->asDate($item['date'],'yyyymmdd');
                  //print_r($update[$i]['start']);
                  if($update[$i]['title']!=$item['title'] || $update[$i]['active']!=$item['active']){
                      $modelUpdate = Grafic::findOne($update[$i]['id']);//$this->findModel($update[$i]['id']);
                      $modelUpdate->timearray = $item['title'];
                      $modelUpdate->active = $item['active'];
                      $modelUpdate->save();  // equivalent to $model->update();
                      //unset($update[$i]);
                  }
                    if($item['active']==1)
                        $colorStatus = '#82FA58';
                    else
                        $colorStatus = '#ff9f89';

                    $json[]=[
                        'id' => $update[$i]['id'],
                        'title' => $item['title'],
                        'start' => $update[$i]['start'],
                        'enddate' =>$update[$i]['enddate'],
                        'allDays' => $update[$i]['allDays'],
                        'active' => $item['active'],
                        'color'=>$colorStatus,
                        'rendering'=>'background'
                  ];

                }else{
                  //Insert
                  $rows[] = [
                        'section_item_id' => $sectionid,
                        'date' => $item['date'],
                        'timearray' => $item['title'],
                        'active' => $item['active']
                    ];

                    if($item['active']==1)
                        $colorStatus = '#82FA58';
                    else
                        $colorStatus = '#ff9f89';

                    $json[]=['id'=>$i,
                        'title'=>$item['title'],
                        'start'=> $item['date'],
                        'enddate'=> $item['date'],
                        'allDays'=>true,
                        'active'=>$item['active'],
                        'color'=>$colorStatus,
                        'rendering'=>'background'
                    ];
                }
            }else{
                $_date = Yii::$app->formatter->asDate($item['date'],'yyyy-MM-dd');
             if(count($arrayData)>1){

                //Insert
                $rows[] = [
                    'section_item_id' => $sectionid,
                    'date' => $item['date'],
                    'timearray' => $item['title'],
                    'active' => $item['active']
                ];

               }else{

                   $modelGrafic = new Grafic();
                   $modelGrafic->section_item_id = $sectionid;
                   $modelGrafic->date = $_date; //Yii::$app->formatter->asDate($item['date'],'Y-mm-dd');
                   $modelGrafic->timearray = $item['title'];
                   $modelGrafic->active = $item['active'];
                   $modelGrafic->save();
               }

                if($item['active']==1)
                    $colorStatus = '#82FA58';
                else
                    $colorStatus = '#ff9f89';


                $json[]=['id'=>0,
                    'title'=>$item['title'],
                    'start'=> $_date,//Yii::$app->formatter->asDate($item['date'],'Y-mm-dd'),
                    'enddate'=> $_date,//Yii::$app->formatter->asDate($item['date'],'Y-mm-dd'),
                    'allDays'=>true,
                    'active'=>$item['active'],
                    'color'=>$colorStatus,
                    'rendering'=>'background'
                ];

            }
        }

        if(isset($rows)){
            $modelGrafic = new Grafic();
            $modelGrafic->multiInsert($rows);
        }

        return  $response[] = $json;

    }

    protected function diffDate($start,$end){
        $diff = strtotime($end) - strtotime($start);
        $days = $diff / 60 / 60 / 24;
       return $days;
    }

    protected  function  EventAddDays($date,$i){
        $idate = date('Y-m-d', strtotime($date.' + '.$i.' day'));
        return $idate;
    }

//    protected function objectToArray($d)
//    {
//        $result = array();
//        foreach ($post_id as $key => $value) {
//            $result[] = $value->post_id;
//        }
//        return $result;
//    }
}
