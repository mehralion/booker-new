<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $game_id
 * @property string $align
 * @property string $klan
 * @property string $login
 * @property integer $level
 * @property integer $is_blocked
 * @property string $blocked_message
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $is_bot
 *
 * @property EventProblem[] $eventProblems
 * @property UserBalance $userBalance
 * @property UserSettings $userSettings
 * @property UserTransaction[] $userTransactions
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
            [['game_id', 'login'], 'required'],
            [['game_id', 'level', 'is_blocked', 'updated_at', 'created_at'], 'integer'],
            [['blocked_message'], 'string'],
            [['align', 'klan', 'login'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'align' => 'Align',
            'klan' => 'Klan',
            'login' => 'Login',
            'level' => 'Level',
            'is_blocked' => 'Is Blocked',
            'blocked_message' => 'Blocked Message',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventProblems()
    {
        return $this->hasMany(EventProblem::className(), ['resolver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBalance()
    {
        return $this->hasOne(UserBalance::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTransactions()
    {
        return $this->hasMany(UserTransaction::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserQuery(get_called_class());
    }
}
