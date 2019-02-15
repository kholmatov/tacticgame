<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%certification_text}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $photo
 */
class Certification extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certification_text}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','title_es', 'text','text_es', 'photo','gamers','period'], 'required'],
            [['file'],'file'],
            [['period'], 'integer'],
            [['text', 'text_es','photo'], 'string'],
            [['title','title_es'], 'string', 'max' => 255]
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
            'title_es' => Yii::t('app', 'Title Es'),
            'text' => Yii::t('app', 'Text'),
            'text_es' => Yii::t('app', 'Text Es'),
            'gamers'=>Yii::t('app','Gamers'),
            'period'=>Yii::t('app','Period (months)'),
            'photo' => Yii::t('app', 'Photo'),
        ];
    }
}
