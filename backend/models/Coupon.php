<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $section_id
 * @property string $start
 * @property string $end
 * @property integer $discount
 * @property integer $number
 * @property integer $count
 * @property integer $status
 * @property string $createdate
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'section_id', 'start', 'end', 'discount', 'number', 'createdate'], 'required'],
            [['section_id'], 'string'],
            [['start', 'end', 'createdate'], 'safe'],
            [['discount', 'number', 'count', 'status'], 'integer'],
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
            'section_id' => Yii::t('app', 'Sections'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'discount' => Yii::t('app', 'Discount (1-100) %'),
            'number' => Yii::t('app', 'Number'),
            'count' => Yii::t('app', 'Count'),
            'status' => Yii::t('app', 'Status'),
            'createdate' => Yii::t('app', 'Createdate'),
        ];
    }
}
