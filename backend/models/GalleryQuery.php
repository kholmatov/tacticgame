<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Gallery]].
 *
 * @see Gallery
 */
class GalleryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Gallery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Gallery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}