<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_transaction".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $transaction_type
 * @property string $price
 * @property string $currency_type
 * @property string $payment_type
 * @property string $balance_before
 * @property string $balance_after
 * @property integer $is_moderated
 * @property string $status
 * @property string $message
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property User $user
 */
class UserTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_transaction';
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
            [['user_id', 'is_moderated', 'updated_at', 'created_at'], 'integer'],
            [['transaction_type', 'price', 'currency_type', 'payment_type', 'balance_before', 'balance_after'], 'required'],
            [['price', 'balance_before', 'balance_after'], 'number'],
            [['message'], 'string'],
            [['transaction_type', 'currency_type', 'payment_type', 'status'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'transaction_type' => 'Transaction Type',
            'price' => 'Price',
            'currency_type' => 'Currency Type',
            'payment_type' => 'Payment Type',
            'balance_before' => 'Balance Before',
            'balance_after' => 'Balance After',
            'is_moderated' => 'Is Moderated',
            'status' => 'Status',
            'message' => 'Message',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
     * @return \common\models\query\UserTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserTransactionQuery(get_called_class());
    }
}
