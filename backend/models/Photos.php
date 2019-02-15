<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%photos}}".
 *
 * @property integer $id
 * @property integer $photo_id
 * @property string $photo
 */
class Photos extends \yii\db\ActiveRecord
{
    public $image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photos}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_id', 'photo'], 'required'],
            [['photo_id'], 'integer'],
            [['image'],'file'],
            [['photo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'photo_id' => Yii::t('app', 'Photo ID'),
            'photo' => Yii::t('app', 'Photo'),
        ];
    }
}
