<?php

namespace common\models;

use common\models\event\Event;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_problem".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $problem_type
 * @property string $description
 * @property integer $is_resolved
 * @property integer $resolver_id
 * @property string $custom
 * @property integer $resolved_at
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Event $event
 * @property User $resolver
 */
class EventProblem extends \yii\db\ActiveRecord
{
    const PROBLEM_DATE      = 'date';
    const PROBLEM_FORA      = 'fora';
    const PROBLEM_NO_RESULT = 'noResult';
    const PROBLEM_FORA_WIN  = 'foraWin';
    const PROBLEM_SPORT_ID  = 'sportId';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_problem';
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
            [['event_id', 'is_resolved', 'resolver_id'], 'required'],
            [['event_id', 'is_resolved', 'resolver_id', 'resolved_at', 'updated_at', 'created_at'], 'integer'],
            [['description', 'custom'], 'string'],
            [['problem_type'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['resolver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['resolver_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'problem_type' => 'Problem Type',
            'description' => 'Description',
            'is_resolved' => 'Is Resolved',
            'resolver_id' => 'Resolver ID',
            'custom' => 'Custom',
            'resolved_at' => 'Resolved At',
            'updated_at' => 'Updated At',
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
     * @return \yii\db\ActiveQuery
     */
    public function getResolver()
    {
        return $this->hasOne(User::className(), ['id' => 'resolver_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\EventProblemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EventProblemQuery(get_called_class());
    }
}
