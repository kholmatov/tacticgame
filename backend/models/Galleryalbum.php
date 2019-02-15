<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use zxbodya\yii2\galleryManager\GalleryBehavior;
use Imagine\Image\ManipulatorInterface;
/**
 * This is the model class for table "{{%gallery_album}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $id_section
 * @property integer $active
 * @property integer $created
 * @property integer $updated
 */
class Galleryalbum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_album}}';
    }

    public function behaviors(){
        //$mode= new \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND;
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT =>  'updated',//['created', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated',
                ],
                'value' => function() { return time(); },
            ],
            'galleryBehavior' => [
                'class' => GalleryBehavior::className(),
                'type' => 'product',
                'extension' => 'jpg',
                'directory' => Yii::$app->params['dirUploadGalleryPath'],
                'url' => Yii::$app->params['urlUploadGalleryPath'],
                'versions' => [
                    'small' => function ($img) {
                        return $img
                            ->copy()
                            ->thumbnail(new \Imagine\Image\Box(262, 262),ManipulatorInterface::THUMBNAIL_OUTBOUND);
                    },
                    'preview' => function ($img) {
                        return $img
                            ->copy()
                            ->thumbnail(new \Imagine\Image\Box(200, 200),ManipulatorInterface::THUMBNAIL_OUTBOUND);
                    }
                    ,
                    'medium' => function ($img) {

                        $dstSize = $img->getSize();
                        $maxWidth = 1000;
                        if ($dstSize->getWidth() > $maxWidth) {
                            $dstSize = $dstSize->widen($maxWidth);
                        }
                        return $img
                            ->copy()
                            ->resize($dstSize);
                    },
                ]
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'id_section', 'created','active'], 'required'],
            [['id_section', 'active'], 'integer'],
            [['created','updated'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'id_section' => Yii::t('app', 'Section'),
            'active' => Yii::t('app', 'Active'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }
}
