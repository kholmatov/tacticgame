<?php

namespace backend\controllers;

use Yii;
use backend\models\Position;
use app\models\PositionSearch;
use app\models\PositionItem;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Sections;
use dosamigos\google\maps\services\GeocodingClient;

/**
 * PositionController implements the CRUD actions for Position model.
 */
class PositionController extends Controller
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
                        'actions' => ['logout', 'index','create','update','view','delete','upload','photodelete'],
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
     * Lists all Position models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PositionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Position model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   //$getSections = PositionItem::getSections($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            //'getSections' => $getSections
        ]);
    }

    /**
     * Creates a new Position model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Position();
        $sectItem = Sections::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$this->PositionItemSave($model->id);
            if($model->address!=""){

                $location=$this->getGeoLocation($model->address);
                if (!is_null($location)) {
                    $model->latitude = (string)$location['lat'];
                    $model->longitude = (string)$location['lng'];
                    $model->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //$sectPostItem = PositionItem::findAll(['position_id' => $model->id]);
            return $this->render('create', [
                'model' => $model,
                'sectItem'=> $sectItem,
                //'sectPostItem' => $sectPostItem
            ]);
        }
    }

    /**
     * Updates an existing Position model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$this->PositionItemDelete($model->id);
            //$this->PositionItemUpdate($model->id);

            if($model->address!=""){

                $location=$this->getGeoLocation($model->address);
                if (!is_null($location)) {
                    $model->latitude = (string)$location['lat'];
                    $model->longitude = (string)$location['lng'];
                    $model->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $sectItem = Sections::find()->all();
            //$sectPostItem = PositionItem::findAll(['position_id' => $model->id]);
            return $this->render('update', [
                'model' => $model,
                'sectItem' => $sectItem,
                //'sectPostItem' => $sectPostItem
            ]);
        }
    }

    /**
     * Deletes an existing Position model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        //$this->PositionItemDelete($id);
        return $this->redirect(['index']);
    }


    /**
     * Finds the Position model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Position the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Position::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //Save Item Position in to #_position_item
    protected function PositionItemSave($id){
        if(isset($_POST['sections'])){
            $sectItemList = $_POST['sections'];
            if(is_array($sectItemList)){

                foreach($sectItemList as $value){
                    $positionItem = new PositionItem;
                    $positionItem->position_id = $id;
                    $positionItem->section_id = $value;
                    $positionItem->save();
                }
            }
        }
    }

    protected function PositionItemUpdate($id){

        if(isset($_POST['sections'])){
            $sectItemList = $_POST['sections'];

            if(is_array($sectItemList)){

                $models = PositionItem::find()
                    ->where('position_id = :position_id',
                        [':position_id' => $id])
                    ->all();

                $arrSectionHave[]="";

                foreach($models as $item){
                    $arrSectionHave[] = $item->section_id;
                }
                print_r($arrSectionHave);
                foreach($sectItemList as $value){

                    if(!in_array($value,$arrSectionHave)){
                        $rows[] = [
                            'position_id' => $id,
                            'section_id' => $value
                        ];
                    }
                    $arrSections[] = $value;
                }


                if(isset($rows)){
                    $positionItem = new PositionItem();
                    $positionItem->multiInsert($rows);
                }

                $arrSections = implode(",", $arrSections);
                PositionItem::deleteAll('position_id = '.$id.' AND section_id NOT IN ('.$arrSections.')');

            }
        }else{
            PositionItem::deleteAll('position_id = :position_id' , [':position_id' => $id]);

        }

    }

    protected function PositionItemDelete($id){

        //Delete Items from #_position_item table
        PositionItem::deleteAll('position_id = :position_id', [':position_id' => $id]);
    }

    protected function PositionItemDeleteWhere($id,$sections){
        $arrSections=implode(",", $sections);
        PositionItem::deleteAll('position_id = :position_id AND section_id NOT IN (:section_id)' , [':position_id' => $id,':section_id'=>$arrSections]);
    }

    protected function getGeoLocation($address){
        $gc = new GeocodingClient();
        $result = $gc->lookup(array('address'=>$address,'components'=>'country'));
        $location = $result['results'][0]['geometry']['location'];
        return $location;
    }

}
