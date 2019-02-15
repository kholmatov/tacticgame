<?php

namespace backend\controllers;

use Yii;
use backend\models\Coupon;
use backend\models\CouponSeacrh;
use backend\models\Couponcode;
use backend\models\Sections;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
{
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
//        ];
//    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','create','update','view','delete','shows'],
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
     * Lists all GameCoupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponSeacrh();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GameCoupon model.
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
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();
       // $sRows = Sections::findAll(['active' => 1]);
        $sRows = ArrayHelper::map(Sections::find()->all(), 'id', 'title');
        $post = Yii::$app->request->post();
        if ($model->load($post)){
            $sectionid = "";
            if(isset($post['sectionid']) && count($post['sectionid']) > 0) $sectionid = implode(",", $post['sectionid']);
            $model->section_id = $sectionid;
            $date = new \DateTime();
            $model->start = date('Y-m-d H:i:s', strtotime($model->start));
            $model->end = date('Y-m-d H:i:s', strtotime($model->end));
            $model->createdate = $date->format('Y-m-d H:i:s');
            $model->save();
            $this->genCoupon($model->id, $model->number);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->status = 1;
            $model->discount = 10;
            $model->number = 50;
            $model->count = 1;
            return $this->render('create', [
                'model' => $model,
                'srows' => $sRows
            ]);
        }
    }

    private function genCoupon($coupon_id, $number){
        $rows = [];
        for($i = 0; $i < $number; $i++) {
            $rows[] =  array(
                        'coupon_id' => $coupon_id,
                        'code' => strtoupper($this->generateRandomString(8))
            );
         }
        $this->insertSeveral(Couponcode::tableName(), $rows);
    }

    public static function insertSeveral($table, $array_columns)
    {
        $connection = \Yii::$app->db;

        $sql = '';
        $params = array();
        $i = 0;
        foreach ($array_columns as $columns) {
            $names = array();
            $placeholders = array();
            foreach ($columns as $name => $value) {
                if (!$i) {
                    $names[] = $connection->quoteColumnName($name);
                }
                if ($value instanceof CDbExpression) {
                    $placeholders[] = $value->expression;
                    foreach ($value->params as $n => $v)
                        $params[$n] = $v;
                } else {
                    $placeholders[] = ':' . $name . $i;
                    $params[':' . $name . $i] = $value;
                }
            }
            if (!$i) {
                $sql = 'INSERT INTO ' . $connection->quoteTableName($table)
                    . ' (' . implode(', ', $names) . ') VALUES ('
                    . implode(', ', $placeholders) . ')';
            } else {
                $sql .= ',(' . implode(', ', $placeholders) . ')';
            }
            $i++;
        }
        $command = \Yii::$app->db->createCommand($sql);//Yii::app()->db->createCommand($sql);
        foreach($params as $k=>$v){
            $command->bindValue($k, $v);
        };
        return $command->execute();
    }

    protected function generateRandomString($length = 32)
    {
        $bytes = Yii::$app->security->generateRandomKey($length);
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
        return rand(1000,9000).strtr(substr(base64_encode($bytes), 0, $length), '+/_-', 'psnm');
    }
    /**
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sRows = ArrayHelper::map(Sections::find()->all(), 'id', 'title');
        $post = Yii::$app->request->post();
        if ($model->load($post)){
            $sectionid = "";
            if(isset($post['sectionid']) && count($post['sectionid']) > 0) $sectionid = implode(",", $post['sectionid']);
            $model->section_id = $sectionid;
            $date = new \DateTime();
            $model->start = date('Y-m-d H:i:s', strtotime($model->start));
            $model->end = date('Y-m-d H:i:s', strtotime($model->end));
            //$model->createdate = $date->format('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'srows' => $sRows
            ]);
        }
    }

    /**
     * Deletes an existing GameCoupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Couponcode::deleteAll('coupon_id = :couponid', [':couponid' => $id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the GameCoupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GameCoupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
