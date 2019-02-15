<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%items}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $shorttext
 * @property string $moretext
 * @property string $photo
 * @property integer $active
 * @property integer $ordering
 * @property string $creatdate
 */
class Items extends \yii\db\ActiveRecord
{
	public $file,$photos;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'shorttext','title_es', 'shorttext_es'], 'required'],
            [['shorttext','shorttext_es', 'youtube', 'photo'], 'string'],
            [['active', 'ordering'], 'integer'],
	        [['file','photos'],'file'],
            [['creatdate'], 'safe'],
            [['title','title_es'], 'string', 'max' => 255]
        ];
    }

	public  function getImageUrl(){
		return '/../upload'.$this->photo;
	}
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'title_es' => Yii::t('app', 'Title Spain'),
            'shorttext' => Yii::t('app', 'Shorttext'),
            'shorttext_es' => Yii::t('app', 'Shorttext Spain'),
            'youtube' => Yii::t('app', 'Youtube'),
            'photo' => Yii::t('app', 'Photo'),
            'active' => Yii::t('app', 'Active'),
            'ordering' => Yii::t('app', 'Ordering'),
            'creatdate' => Yii::t('app', 'Creatdate'),
        ];
    }
}
