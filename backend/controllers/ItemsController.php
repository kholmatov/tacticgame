<?php

namespace backend\controllers;

use Yii;
use backend\models\Items;
use backend\models\ItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//add me
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
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
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Items model.
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
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Items();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Items model.
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
     * Deletes an existing Items model.
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
				->resize(new Box(400, 240), ImageInterface::FILTER_UNDEFINED)
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
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
