<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Proxy]].
 *
 * @see \common\models\Proxy
 */
class ProxyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function active()
    {
        return $this->andWhere('is_enabled = 1');
    }

    /**
     * @inheritdoc
     * @return \common\models\Proxy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Proxy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
