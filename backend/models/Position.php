<?php

//namespace app\models;
namespace backend\models;
use Yii;
//use yii\base\Model;


/**
 * This is the model class for table "{{%position}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $array_sections
 * @property integer $active
 */
class Position extends \yii\db\ActiveRecord
{
    public $sections;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'address','title_es', 'address_es'], 'required'],
            [['address','address_es'], 'string'],
            [['active'], 'integer'],
            [['title', 'title_es','latitude', 'longitude'], 'string', 'max' => 255]
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
            'address' => Yii::t('app', 'Address'),
            'title_es' => Yii::t('app', 'Title Spain'),
            'address_es' => Yii::t('app', 'Address Spain'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    public function multiInsert($rows){
        Yii::$app->db->createCommand()
            ->batchInsert($this->tableName(),
                ['postion_item_id', 'date','timearray','active'],$rows)
            ->execute();
    }
}
