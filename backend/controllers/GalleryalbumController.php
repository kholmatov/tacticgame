<?php

namespace backend\controllers;

use Yii;
use backend\models\Galleryalbum;
use backend\models\GalleryalbumSearch;
use backend\models\Sections;
use backend\models\Gallerycounter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use zxbodya\yii2\galleryManager\GalleryManagerAction;
use yii\filters\AccessControl;
/**
 * GalleryalbumController implements the CRUD actions for Galleryalbum model.
 */
class GalleryalbumController extends Controller
{
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
                        'actions' => ['logout', 'index','create','update','view','galleryApi','delete'],
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

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'galleryApi' => [
                'class' => GalleryManagerAction::className(),
                // mappings between type names and model classes (should be the same as in behaviour)
                'types' => [
                    'product' => Galleryalbum::className()
                ]
            ],
        ];
    }

    /**
     * Lists all Galleryalbum models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new GalleryalbumSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Galleryalbum model.
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
     * Creates a new Galleryalbum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Galleryalbum();
        $sections = Sections::find()->asArray()->where(['active' =>1])->all();

       // $counterResult = Gallerycounter::find()->asArray()->where(['id' =>1])->one();

        if ($model->load(Yii::$app->request->post())) {

            $counter = Gallerycounter::findOne(1);
            $counter->counter++;
            $counter->save();

            $model->created = strtotime($model->created);
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'sections'=> $sections
            ]);
        }
    }

    /**
     * Updates an existing Galleryalbum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sections = Sections::find()->asArray()->where(['active' =>1])->all();
        if ($model->load(Yii::$app->request->post())) {
            $model->created = strtotime($model->created);
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
           // print_r($rs);
        } else {
            return $this->render('update', [
                'model' => $model,
                'sections'=> $sections
            ]);
        }
    }

    /**
     * Deletes an existing Galleryalbum model.
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
     * Finds the Galleryalbum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Galleryalbum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Galleryalbum::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
