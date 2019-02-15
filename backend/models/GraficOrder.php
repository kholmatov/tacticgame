<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%grafic_order}}".
 *
 * @property integer $id
 * @property integer $sections_id
 * @property string $date
 * @property string $time
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $amount
 * @property string $comment
 * @property integer $status
 * @property integer $check
 * @property string $createdate
 */
class GraficOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grafic_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sections_id', 'status', 'check'], 'integer'],
            [['date', 'time', 'phone', 'email', 'amount', 'createdate','code'], 'required'],
            [['date', 'createdate'], 'safe'],
            [['amount'], 'number'],
            [['comment','code'], 'string'],
            [['time'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['phone', 'email'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sections_id' => Yii::t('app', 'Sections ID'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'amount' => Yii::t('app', 'Amount'),
            'comment' => Yii::t('app', 'Comment'),
            'status' => Yii::t('app', 'Status'),
            'check' => Yii::t('app', 'Check'),
            'code' => Yii::t('app', 'Code'),
            'createdate' => Yii::t('app', 'Createdate'),
        ];
    }


    public function getOrders($code){
        $query = new Query;
        $query->select('s.id, s.title, s.min, s.max, s.duration, g.date, g.time, g.status,g.code, g.amount')
            //->from('{{%position_item}} pi')
            ->from('{{%grafic_order}} g')
            ->join('LEFT JOIN','{{%sections}}  s','s.id = g.sections_id')
            ->where('g.code = :code AND g.status = :status' ,[':code' => $code,':status'=>1]);
        //$rows = $query->all();
        $rows = $query->one();
        return $rows;
    }
}
