<?php

namespace backend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%certificat_section}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $finish_date
 * @property integer $gamer
 * @property integer $number
 * @property string $price
 * @property string $createdate
 */
class Certificatsection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificat_section}}';
    }
    /*
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['start_date', 'finish_date', 'createdate'],//['created','updated'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['start_date', 'finish_date'],//'updated', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' =>
                    function() {
                            return date('Y-m-d H:i:s', strtotime($this->start_date));
                },
//            function ($event) {
//                    return date('Y-m-d H:i:s', strtotime($this->LastUpdated));
//                },
            ],
        ];
    }
    */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'start_date', 'finish_date', 'gamers', 'createdate'], 'required'],
            [['start_date', 'finish_date', 'createdate'], 'safe'],
            [['number'], 'integer'],
            [['title', 'gamers'], 'string', 'max' => 255]
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
            'start_date' => Yii::t('app', 'Start Date'),
            'finish_date' => Yii::t('app', 'Finish Date'),
            'gamers' => Yii::t('app', 'Gamers'),
            'period' => Yii::t('app', 'Period'),
            'createdate' => Yii::t('app', 'Createdate'),
        ];
    }
}
