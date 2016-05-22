<?php

namespace common\models;

use common\models\event\Event;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_fixed_value".
 *
 * @property integer $event_id
 * @property string $filed
 * @property string $value
 * @property integer $created_at
 * @property string $field_type
 *
 * @property Event $event
 */
class EventFixedValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_fixed_value';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
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
            [['event_id', 'filed', 'value'], 'required'],
            [['event_id', 'created_at'], 'integer'],
            [['filed', 'value', 'field_type'], 'string', 'max' => 255],
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
            'filed' => 'Odds Name',
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
     * @return \common\models\query\EventFixedValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EventFixedValueQuery(get_called_class());
    }
}
