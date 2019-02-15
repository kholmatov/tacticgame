<?php

namespace backend\controllers;

use backend\models\Position;
use Yii;
use backend\models\Sections;
use backend\models\SectionsSearch;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use yii\filters\AccessControl;



/**
 * SectionsController implements the CRUD actions for Sections model.
 */
class SectionsController extends Controller
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
     * Lists all Sections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sections model.
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
     * Creates a new Sections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sections();
        $position = Position::find()->asArray()->where(['active' =>1])->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file2 = UploadedFile::getInstance($model, 'file2');

            if ($model->validate()) {
                $model->file->saveAs(Yii::$app->params['dirUploadPath'].'/slide/'. $model->file->baseName . '.' . $model->file->extension);
                $model->file2->saveAs(Yii::$app->params['dirUploadPath'].'/avatar/'. $model->file2->baseName . '.' . $model->file2->extension);
            }
            $model->slide = '/slide/' . $model->file->baseName . '.' . $model->file->extension;
            $model->avatar = '/avatar/' . $model->file2->baseName . '.' . $model->file2->extension;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'position' => $position
            ]);
        }
    }

    /**
     * Updates an existing Sections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $position = Position::find()->asArray()->where(['active' =>1])->all();
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file2 = UploadedFile::getInstance($model, 'file2');

            if (!empty($model->file)) {
                if ($model->validate()) {
                    $model->file->saveAs(Yii::$app->params['dirUploadPath'].'/slide/'. $model->file->baseName . '.' . $model->file->extension);
                }
                $model->slide = '/slide/' . $model->file->baseName . '.' . $model->file->extension;
            }

            if (!empty($model->file2)) {
               $model->file2->saveAs(Yii::$app->params['dirUploadPath'].'/avatar/'. $model->file2->baseName . '.' . $model->file2->extension);
               $model->avatar = '/avatar/' . $model->file2->baseName . '.' . $model->file2->extension;
            }

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'position' => $position
            ]);
        }
    }

    /**
     * Deletes an existing Sections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUpload($id)
    {
//        $redirect=false;
//        if(!$id){
//            $model = new Sections();
//            $model->title =Yii::t('app', 'Sample page');
//            $model->active = 0;
//            $model->save();
//            $id = Yii::$app->db->getLastInsertID();
//            $redirect=true;
//        }

        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

        //$picture->photo_id = $id;
        $model->photos = UploadedFile::getInstance($model, 'photos');

        if ($model->photos !== null && $model->validate(['photos'])) {
            $photoID = Yii::$app->security->generateRandomString(7);
            $imgName = $photoID.'.'.$model->photos->extension;
            $fullImage = Yii::$app->params['dirUploadPath'].'/images/' .$imgName;
            $smallImage = Yii::$app->params['dirUploadPath'].'/small/' .$imgName;
            $avatarImage = Yii::$app->params['dirUploadPath'].'/avatar/' .$imgName;
            $model->photos->saveAs($fullImage);
            //ImageInterface::
            Image::frame($fullImage)
                ->resize(new Box(400, 240), ImageInterface::FILTER_UNDEFINED) //ImageInterface::FILTER_UNDEFINED
                ->save($smallImage);

            Image::frame($fullImage)
                ->resize(new Box(569, 242), ImageInterface::FILTER_UNDEFINED)
                ->save($avatarImage);

            //$model_array_a[$id][] = Array();
            if($model->photo){
                //$model_array_a[$id]['value'] = unserialize($model->photo);
                $model_array = unserialize($model->photo);
            }


            $model_array[] = Array(
                'photo_id' =>  $photoID,
                'preview' => '/small/'.$imgName,
                'normal' => '/images/'.$imgName,
                'avatar' => '/avatar/'.$imgName,
            );

            //if(isset($model_array_a[$id]))
             //   $model_array = array_merge($model_array, $model_array_a);

            if(isset($model_array))
                $model->photo =  serialize($model_array);
            else
                $model->photo = "";

            Yii::$app->response->getHeaders()->set('Vary', 'Accept');
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response = [];

            if ($model->save(false)) {
                 $response['files'][] = [
                    'name' => $imgName,
                    'type' => $model->photos->type,
                    'size' => $model->photos->size,
                    'url' => '/../upload/images/'.$imgName,
                    'thumbnailUrl' => '/../upload/small/'.$imgName,//Sections::SMALL_IMAGE
                    'deleteUrl' => Url::to(['photodelete', 'id' => $model->id,'photo_id'=>$photoID]),
                    'deleteType' => 'POST'
                ];
            } else {
                $response[] = ['error' => Yii::t('app', 'Unable to save picture')];
            }
               @unlink($model->photos->tempName);
        } else {
            if ($model->hasErrors(['model'])) {
                $response[] = ['error' => HtmlHelper::errors($model)];
            } else {
                throw new HttpException(500, Yii::t('app', 'Could not upload file.'));
            }
        }


        return $response;

    }

    public function actionPhotodelete($id){

       if($getVar=Yii::$app->request->get()){

         $model = $this->findModel($id);

         if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

        if($model->photo){
            $model_array = unserialize($model->photo);
        }
        if(isset($model_array)){
            foreach($model_array as $item){
                if($item['photo_id']==$getVar['photo_id']){

                    $preview=Yii::$app->params['dirUploadPath'].$item['preview'];
                    if(is_file($preview)) @unlink($preview);

                    $normal=Yii::$app->params['dirUploadPath'].$item['normal'];
                    if(is_file($normal)) @unlink($normal);

                    if(isset($item['avatar'])){
                    $avatar=Yii::$app->params['dirUploadPath'].$item['avatar'];
                    if(is_file($avatar)) @unlink($avatar);
                    }
                }else{
                    $model_array_after[]=$item;
                }
            }
        if(isset($model_array_after))
            $model->photo = serialize($model_array_after);
        else
            $model->photo="";
        }

        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        if($model->save(false)){
            $response[] = ['ok' => Yii::t('app', 'Remove picture ok')];
        }
        return $response;
     }
    }

    /**
     * Finds the Sections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
