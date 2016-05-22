<?php

namespace common\models\query;
use common\models\event\Event;

/**
 * This is the ActiveQuery class for [[\common\models\Event]].
 *
 * @see \common\models\Event
 */
class EventQuery extends \yii\db\ActiveQuery
{
    public $sport_type;

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function forUser()
    {
        return $this->andWhere('started_at > :time and status = :enable', [
            ':time' => time(),
            ':enable' => Event::STATUS_ENABLE
        ]);
    }

    public function prepare($builder)
    {
        if ($this->sport_type !== null) {
            $this->andWhere(['sport_type' => $this->sport_type]);
        }
        return parent::prepare($builder);
    }

    /**
     * @inheritdoc
     * @return \common\models\event\Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\event\Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
