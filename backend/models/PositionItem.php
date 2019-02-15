<?php

namespace app\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%position_item}}".
 *
 * @property integer $position_id
 * @property integer $section_id
 */
class PositionItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position_id', 'section_id'], 'required'],
            [['position_id', 'section_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'position_id' => Yii::t('app', 'Position ID'),
            'section_id' => Yii::t('app', 'Section ID'),
        ];
    }

    public function getSections($position_id){
        $query = new Query;
        $query->select('s.title')
         ->from('{{%position_item}} p')
         ->join('INNER JOIN','{{%sections}} s','p.section_id=s.id')
         ->where(['p.position_id' => $position_id]);
        $rows = $query->all();
        return $rows;
    }

    public function multiInsert($rows){
        Yii::$app->db->createCommand()
            ->batchInsert($this->tableName(),
                ['position_id', 'section_id'],$rows)
            ->execute();
    }
}
