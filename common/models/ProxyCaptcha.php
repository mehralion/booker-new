<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "proxy_captcha".
 *
 * @property integer $id
 * @property string $ip
 * @property string $port
 * @property string $link
 */
class ProxyCaptcha extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proxy_captcha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'port', 'link'], 'required'],
            [['link'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'port' => 'Port',
            'link' => 'Link',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\ProxyCaptchaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProxyCaptchaQuery(get_called_class());
    }
}
