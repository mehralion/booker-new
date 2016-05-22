<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_balance".
 *
 * @property integer $user_id
 * @property string $currency
 * @property string $sum
 * @property string $sum_in
 * @property string $sum_out
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_balance';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'updated_at',
                'createdAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'updated_at'], 'integer'],
            [['sum', 'sum_in', 'sum_out'], 'number'],
            [['currency'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'currency' => 'Currency',
            'sum' => 'Sum',
            'sum_in' => 'Sum In',
            'sum_out' => 'Sum Out',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\UserBalanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserBalanceQuery(get_called_class());
    }
}
