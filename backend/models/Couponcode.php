<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%coupon_code}}".
 *
 * @property integer $id
 * @property integer $coupon_id
 * @property string $code
 * @property integer $status
 */
class Couponcode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'code'], 'required'],
            [['coupon_id', 'status'], 'integer'],
            [['code'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'coupon_id' => Yii::t('app', 'Coupon ID'),
            'code' => Yii::t('app', 'Code'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function checkCode($code){
        $query = new Query;
        $query->select('cc.id, c.section_id, c.start, c.end, c.discount, c.status, cc.code, c.count, (SELECT COUNT(*) FROM {{%grafic_order}} go  WHERE go.coupon_code=cc.code) AS cnt')
            ->from('{{%coupon_code}} cc')
            ->join('INNER JOIN','{{%coupon}} c','cc.coupon_id=c.id')
            ->where('c.status = 1 AND cc.status = 0 AND (c.start <= now() AND c.end >= now()) AND cc.code = :code',[':code'=>$code]);
        return $query->one();
    }
}
