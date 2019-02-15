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
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grafic_order}}';
    }

    public function getsection(){
        return $this->hasMany(Sections::className(), ['id'=>'sections_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sections_id', 'status', 'check'], 'integer'],
            [['date', 'time', 'phone', 'email', 'amount', 'createdate','code'], 'required'],
            [['payment_type','date', 'createdate'], 'safe'],
            [['amount'], 'number'],
            [['comment','code','language','coupon_code','certificate'], 'string'],
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
            'payment_type'=> Yii::t('app','Payment'),
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
            'language' => Yii::t('app','Language'),
            'createdate' => Yii::t('app', 'Createdate'),
            'coupon_code'=> Yii::t('app', 'Promocode'),
            'certificate'=> Yii::t('app', 'Certificate'),
        ];
    }


    public function getOrders($code){
        $query = new Query;
        $query->select('s.id, s.title, s.min, s.max, s.duration, g.payment_type, g.date, g.time, g.status,g.code, g.amount, g.email, g.phone')
            //->from('{{%position_item}} pi')
            ->from('{{%grafic_order}} g')
            ->join('LEFT JOIN','{{%sections}}  s','s.id = g.sections_id')
            ->where('g.code = :code AND g.status = :status' ,[':code' => $code,':status'=>1]);
        //$rows = $query->all();
        $rows = $query->one();
        return $rows;
    }

    public function getTiket($code){
        $query = new Query;
        $query->select('s.id, s.title, s.min, s.max, s.duration, g.payment_type, g.date, g.time, g.status,g.code, g.amount')
            //->from('{{%position_item}} pi')
            ->from('{{%grafic_order}} g')
            ->join('LEFT JOIN','{{%sections}}  s','s.id = g.sections_id')
            ->where('g.code = :code AND g.status = :status AND g.check=:check',[':code' => $code,':status'=>1,':check'=>1]);
        //$rows = $query->all();
        $rows = $query->one();
        return $rows;
    }

}
