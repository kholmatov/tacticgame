<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%certificat_order}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $creatdate
 * @property integer $status
 */
class Certificatorder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificat_order}}';
    }
    /*
     * SELECT `id`, `order`, `code`, `sect_id`, `price`, `email`, `phone`, `comment`,
     * `createdate`, `status`, `period`
     * FROM `game_certificat_order` WHERE 1
     */
        /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order','code', 'price','email','phone', 'createdate','period','users'], 'required'],
            [['createdate'], 'safe'],
            [['status','sect_id','period','users'], 'integer'],
            [['code','order'], 'string', 'max' => 50],
            [['phone','email'], 'string', 'max' => 100],
            [['price'], 'number'],
            [['comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order' =>Yii::t('app','Order'),
            'code' => Yii::t('app', 'Certificat Code'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'price' => Yii::t('app','Price'),
            'sect_id' =>Yii::t('app','Certification'),
            'comment' => Yii::t('app', 'Comment'),
            'createdate' => Yii::t('app', 'Date of purchase'),
            'status' => Yii::t('app', 'Paid'),
            'period' => Yii::t('app', 'Expire'),
            'users' => Yii::t('app', 'Users')
        ];
    }

    public function getCertification($code){
        $query = new Query();
        $query->select('co.code')
            ->from('{{%certificat_order}} co')
           // ->join('LEFT JOIN','{{%grafic_order}} g','co.code <> g.certificate')
            ->where('co.code = :code AND co.status = :status AND DATE_ADD(co.createdate, INTERVAL co.period MONTH) >= NOW() 
                    AND co.code NOT IN (SELECT certificate FROM {{%grafic_order}} g WHERE g.certificate = :code)',
                    [':code' => $code,':status'=>1]);
        //$rows = $query->all();
//        echo $query->createCommand()->getRawSql();

        return $query->one();
    }

    public function getTiket($order){
        $query = new Query();
        $query->select('co.code,co.order,co.price,co.email,co.users, co.period,co.createdate')
            ->from('{{%certificat_order}} co')
             ->join('LEFT JOIN','{{%certificat_section}} cs','co.sect_id = cs.id')
            ->where('co.order = :order AND co.status = :status',
                [':order' => $order,':status'=>1]);
        return $query->one();
    }
}
