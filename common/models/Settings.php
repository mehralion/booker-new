<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "settings".
 *
 * @property string $field
 * @property string $value
 * @property integer $updated_at
 * @property string $type
 */
class Settings extends \yii\db\ActiveRecord
{
    const TYPE_BOOKMAKER = 'bookmaker';
    const TYPE_CURRENCY = 'currency';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
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
            [['field', 'value'], 'required'],
            [['updated_at'], 'integer'],
            [['field', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'field' => 'Field',
            'value' => 'Value',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SettingsQuery(get_called_class());
    }
}
