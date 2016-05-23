<?php

namespace common\models;

use common\models\event\Event;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "team_alias".
 *
 * @property integer $id
 * @property integer $team_id
 * @property string $title
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $is_other
 *
 * @property Event[] $events
 * @property Event[] $events0
 * @property Team $team
 */
class TeamAlias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_alias';
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
            [['team_id', 'title'], 'required'],
            [['team_id', 'updated_at', 'created_at', 'is_other'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'title' => 'Title',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_other' => 'Is Other',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['team_1_alias' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents0()
    {
        return $this->hasMany(Event::className(), ['team_2_alias' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\TeamAliasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TeamAliasQuery(get_called_class());
    }
}
