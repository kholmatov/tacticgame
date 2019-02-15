<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%certification_order}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $creatdate
 * @property integer $status
 */
class Certificationorder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certification_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'phone', 'creatdate'], 'required'],
            [['creatdate'], 'safe'],
            [['status'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name','gamers'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 100],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'text' => Yii::t('app', 'Text'),
            'creatdate' => Yii::t('app', 'Creatdate'),
            'status' => Yii::t('app', 'Status'),
            'gamers' => Yii::t('app', 'Gamers')
        ];
    }
}
