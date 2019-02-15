<?php

namespace backend\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%grafic}}".
 *
 * @property integer $id
 * @property integer $postion_item_id
 * @property string $date
 * @property string $timearray
 */
class Grafic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grafic}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_item_id', 'date', 'timearray'], 'required'],
            [['section_item_id','active'], 'integer'],
            [['date'], 'safe'],
            [['timearray'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'section_item_id' => Yii::t('app', 'Section Item ID'),
            'date' => Yii::t('app', 'Date'),
            'timearray' => Yii::t('app', 'Timearray'),
            'active' => Yii::t('app', 'Public'),
        ];
    }

    public function getSections(){
        $query = new Query;
        $query->select('s.title as stitle, p.title as ptitle, s.id')
            //->from('{{%position_item}} pi')
            ->from('{{%sections}} s')
            ->join('LEFT JOIN','{{%position}}  p','s.position_id = p.id')
            ->where(['s.active' => 1]);
        $rows = $query->all();
        return $rows;
    }

    public function multiInsert($rows){
        Yii::$app->db->createCommand()
            ->batchInsert($this->tableName(),
                ['section_item_id', 'date','timearray','active'],$rows)
            ->execute();
    }


    public function getGraficOrder($now,$end,$id){
        $query = new Query;
        $query->select('`id`, `sections_id`, `date`, `time`, `name`, `phone`, `email`, `amount`, `comment`, `status`, `check`, `createdate`')
            ->from('{{%grafic_order}} g')
            ->where('date >= :now AND date <= :end AND sections_id = :sectionid',
                ['now'=>$now, 'end'=>$end, 'sectionid'=>$id]);
        $rows = $query->all();
        return $rows;
    }

}
