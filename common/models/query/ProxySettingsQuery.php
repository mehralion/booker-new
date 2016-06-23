<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\ProxySettings]].
 *
 * @see \common\models\ProxySettings
 */
class ProxySettingsQuery extends \yii\db\ActiveQuery
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
     * @return \common\models\ProxySettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\ProxySettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
