<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "settings_currency".
 *
 * @property string $currency_type
 * @property string $extra_ratio
 * @property string $event_limit
 * @property string $strange_output
 * @property integer $auto_output
 * @property string $min_bet
 * @property string $short_name
 * @property string $max_ratio
 * @property integer $updated_at
 */
class SettingsCurrency extends \yii\db\ActiveRecord
{
    const CURRENCY_KR   = 'kr';
    const CURRENCY_EKR  = 'ekr';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_currency';
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
            [['currency_type'], 'required'],
            [['extra_ratio', 'event_limit', 'strange_output', 'min_bet', 'max_ratio'], 'number'],
            [['auto_output', 'updated_at'], 'integer'],
            [['currency_type'], 'string', 'max' => 255],
            [['short_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_type' => 'Currency Type',
            'extra_ratio' => 'Extra Ratio',
            'event_limit' => 'Event Limit',
            'strange_output' => 'Strange Output',
            'auto_output' => 'Auto Output',
            'min_bet' => 'Min Bet',
            'short_name' => 'Short Name',
            'max_ratio' => 'Max Ratio',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\SettingsCurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SettingsCurrencyQuery(get_called_class());
    }
}
