<?php

namespace common\models;

use common\models\event\Event;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_odds".
 *
 * @property integer $event_id
 * @property string $field
 * @property integer $_v
 * @property string $value
 * @property integer $position
 * @property string $bookmaker
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Event $event
 */
class EventOdds extends \yii\db\ActiveRecord
{
    const POSITION_NEW  = 2;
    const POSITION_LAST = 1;
    const POSITION_OLD  = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_odds';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'updated_at',
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
            [['event_id', 'type', '_v', 'value', 'position'], 'required'],
            [['event_id', '_v', 'position', 'updated_at', 'created_at'], 'integer'],
            [['value'], 'number'],
            [['field', 'bookmaker'], 'string', 'max' => 255],
            [['event_id', '_v'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id', '_v' => '_v']],
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
            '_v' => 'V',
            'value' => 'Value',
            'position' => 'Position',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id', '_v' => '_v']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\EventOddsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EventOddsQuery(get_called_class());
    }
    
    public static function toOld($event_id, $bookmaker)
    {
        return self::updateAll([
            'position'      => self::POSITION_OLD,
            'updated_at'    => time(),
        ], 'event_id = :event_id and position = :position and bookmaker = :bookmaker', [
            ':event_id'     => $event_id,
            ':position'     => self::POSITION_LAST,
            ':bookmaker'    => $bookmaker,
        ]);
    }

    public static function toLast($event_id, $bookmaker)
    {
        return self::updateAll([
            'position'      => self::POSITION_LAST,
            'updated_at'    => time()
        ], 'event_id = :event_id and position = :position and bookmaker = :bookmaker', [
            ':event_id'     => $event_id,
            ':position'     => self::POSITION_NEW,
            ':bookmaker'    => $bookmaker,
        ]);
    }
    
    public static function add($event_id, $bookmaker, $v, $odds)
    {
        $rows = [];
        foreach ($odds as $key => $value) {
            if(!is_numeric($value)) {
                continue;
            }

            $rows[] = [
                'event_id'      => $event_id,
                'field'         => $key,
                '_v'            => $v,
                'value'         => $value,
                'position'      => self::POSITION_NEW,
                'bookmaker'     => $bookmaker,
                'updated_at'    => time(),
                'created_at'    => time(),
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), (new self)->attributes(), $rows)->execute();

        return true;
    }
}
