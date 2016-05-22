<?php

namespace common\models;

use common\models\event\Event;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_result".
 *
 * @property integer $event_id
 * @property string $field
 * @property integer $value
 * @property integer $created_at
 *
 * @property Event $event
 */
class EventResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_result';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'field', 'value'], 'required'],
            [['event_id', 'value', 'created_at'], 'integer'],
            [['field'], 'string', 'max' => 255],
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
            'field' => 'Field',
            'value' => 'Value',
            'created_at' => 'Created At',
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
     * @return \common\models\query\EventResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EventResultQuery(get_called_class());
    }
}
