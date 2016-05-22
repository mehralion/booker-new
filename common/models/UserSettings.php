<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property integer $user_id
 * @property string $active_currency
 * @property string $extra_ratio
 * @property string $admin_comment
 *
 * @property User $user
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['extra_ratio'], 'number'],
            [['admin_comment'], 'string'],
            [['active_currency'], 'string', 'max' => 255],
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
            'active_currency' => 'Active Currency',
            'extra_ratio' => 'Extra Ratio',
            'admin_comment' => 'Admin Comment',
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
     * @return \common\models\query\UserSettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserSettingsQuery(get_called_class());
    }
}
