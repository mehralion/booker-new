<?php

namespace common\models;

use common\models\event\Event;
use Yii;

/**
 * This is the model class for table "event_bookmaker_version".
 *
 * @property integer $event_id
 * @property string $bookmaker
 * @property integer $_v
 *
 * @property Event $event
 */
class EventBookmakerVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_bookmaker_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'bookmaker'], 'required'],
            [['event_id', '_v'], 'integer'],
            [['bookmaker'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'bookmaker' => 'Bookmaker',
            '_v' => 'V',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\EventBookmakerVersionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EventBookmakerVersionQuery(get_called_class());
    }
}
