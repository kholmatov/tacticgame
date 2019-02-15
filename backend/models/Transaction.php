<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $transaction_id
 * @property string $transaction_time
 * @property string $transaction_currency
 * @property string $transaction_amount
 * @property string $transaction_method
 * @property string $transaction_state
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'transaction_id', 'transaction_currency', 'transaction_amount', 'transaction_method', 'transaction_state'], 'required'],
            [['transaction_time'], 'safe'],
            [['transaction_amount'], 'number'],
            [['code', 'transaction_id'], 'string', 'max' => 60],
            [['transaction_currency'], 'string', 'max' => 4],
            [['transaction_method', 'transaction_state'], 'string', 'max' => 40]
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
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'transaction_time' => Yii::t('app', 'Transaction Time'),
            'transaction_currency' => Yii::t('app', 'Transaction Currency'),
            'transaction_amount' => Yii::t('app', 'Transaction Amount'),
            'transaction_method' => Yii::t('app', 'Transaction Method'),
            'transaction_state' => Yii::t('app', 'Transaction State'),
        ];
    }
}
