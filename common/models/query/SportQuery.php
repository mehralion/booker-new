<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Sport]].
 *
 * @see \common\models\Sport
 */
class SportQuery extends \yii\db\ActiveQuery
{
    public $sport_type;

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function prepare($builder)
    {
        if ($this->sport_type !== null) {
            $this->andWhere(['sport_type' => $this->sport_type]);
        }
        return parent::prepare($builder);
    }

    /**
     * @inheritdoc
     * @return \common\models\sport\Sport[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\sport\Sport|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
