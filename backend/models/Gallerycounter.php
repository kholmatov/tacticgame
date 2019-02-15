<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%gallery_counter}}".
 *
 * @property integer $id
 * @property integer $counter
 */
class Gallerycounter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_counter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'counter' => Yii::t('app', 'Counter'),
        ];
    }
}
