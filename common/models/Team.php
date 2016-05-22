<?php

namespace common\models;

use common\models\event\Event;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "team".
 *
 * @property integer $id
 * @property string $title
 * @property string $country
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Event[] $events
 * @property Event[] $events0
 * @property TeamAlias[] $teamAliases
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team';
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
            [['title'], 'required'],
            [['updated_at', 'created_at'], 'integer'],
            [['title', 'country'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'country' => 'Country',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['team_1_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents0()
    {
        return $this->hasMany(Event::className(), ['team_2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamAliases()
    {
        return $this->hasMany(TeamAlias::className(), ['team_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\TeamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TeamQuery(get_called_class());
    }
}
