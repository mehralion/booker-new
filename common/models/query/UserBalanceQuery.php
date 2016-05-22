<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\UserBalance]].
 *
 * @see \common\models\UserBalance
 */
class UserBalanceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\UserBalance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\UserBalance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
