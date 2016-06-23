<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "proxy_settings".
 *
 * @property integer $id
 * @property string $link
 * @property string $adapter
 * @property integer $is_enabled
 * @property integer $created_at
 */
class ProxySettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proxy_settings';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
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
            [['link', 'adapter'], 'required'],
            [['link'], 'string'],
            [['is_enabled', 'created_at'], 'integer'],
            [['adapter'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'adapter' => 'Adapter',
            'is_enabled' => 'Is Enabled',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\ProxySettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProxySettingsQuery(get_called_class());
    }
}
