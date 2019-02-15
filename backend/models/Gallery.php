<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use zxbodya\yii2\galleryManager\GalleryBehavior;
/**
 * This is the model class for table "{{%gallery_image}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $ownerId
 * @property integer $rank
 * @property string $name
 * @property string $description
 */
class Gallery extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
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
                            ->thumbnail(new \Imagine\Image\Box(262, 262));
                    },
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
    public static function tableName()
    {
        return '{{%gallery_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ownerId'], 'required'],
            [['rank'], 'integer'],
            [['description'], 'string'],
            [['type', 'ownerId', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'ownerId' => Yii::t('app', 'Owner ID'),
            'rank' => Yii::t('app', 'Rank'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @inheritdoc
     * @return GalleryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GalleryQuery(get_called_class());
    }
}
