<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\EventResult]].
 *
 * @see \common\models\EventResult
 */
class EventResultQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\EventResult[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\EventResult|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
