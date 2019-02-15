<?php

//namespace app\models;
namespace backend\models;

use Yii;
use yii\db\Query;


/**
 * This is the model class for table "{{%sections}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $shorttext
 * @property string $moretext
 * @property string $photo
 * @property string $slide
 * @property integer $min
 * @property integer $max
 * @property string $duration
 * @property integer $active
 */
class Sections extends \yii\db\ActiveRecord
{
    public $file,$file2,$photos;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sections}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','title_es','position_id','tariff'], 'required'],
            [['shorttext','shorttext_es', 'moretext', 'youtube','photo', 'slide','avatar'], 'string'],
            [['min', 'max', 'active','position_id','rating'], 'integer'],
            [['file','photos','file2'],'file'],
            [['title','title_es','tariff'], 'string', 'max' => 255],
            [['duration','duration_es'], 'string', 'max' => 200]
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
            'title_es' => Yii::t('app', 'Title Spain'),
            'tariff' => Yii::t('app', 'Tariff'),
            'shorttext' => Yii::t('app', 'Shorttext'),
            'shorttext_es' => Yii::t('app', 'Shorttext Spain'),
            'moretext' => Yii::t('app', 'Moretext'),
            'youtube' => Yii::t('app', 'Youtube'),
            'photo' => Yii::t('app', 'Photo'),
            'avatar' => Yii::t('app', 'Avatar'),
            'slide' => Yii::t('app', 'Slide'),
            'min' => Yii::t('app', 'Min'),
            'max' => Yii::t('app', 'Max'),
            'duration' => Yii::t('app', 'Duration'),
            'duration_es' => Yii::t('app', 'Duration Spain'),
            'active' => Yii::t('app', 'Active'),
            'photos' => Yii::t('app','Photos'),
            'positon_id' => Yii::t('app','Position'),
            'rating' => Yii::t('app','Rating')
        ];
    }

    public  function getImageUrl(){
        return '/../upload'.$this->photo;
    }

    public function getAddress($section_id){
        $query = new Query;
        $query->select('p.id, p.address, p.title, p.address_es, p.title_es, p.latitude, p.longitude')
            ->from('{{%position}} p')
            ->join('INNER JOIN','{{%sections}} s','s.position_id=p.id')
            ->where(['s.id' => $section_id]);
        $rows = $query->all();
        return $rows;
    }
}
