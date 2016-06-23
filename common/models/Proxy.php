<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "proxy".
 *
 * @property integer $id
 * @property string $ip
 * @property integer $port
 * @property integer $delay
 * @property string $country_code
 * @property string $country_name
 * @property integer $is_enabled
 * @property integer $attemt
 * @property string $source
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property BookmakerProxy[] $bookmakerProxies
 * @property Bookmaker[] $bookmakers
 */
class Proxy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proxy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'port', 'source'], 'required'],
            [['port', 'delay', 'is_enabled', 'updated_at', 'created_at', 'attemt'], 'integer'],
            [['ip', 'country_code', 'source'], 'string', 'max' => 255],
            [['country_name'], 'string', 'max' => 50],
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
            'delay' => 'Delay',
            'country_code' => 'Country Code',
            'country_name' => 'Country Name',
            'is_enabled' => 'Is Enabled',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'attemt' => 'Attemt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmakerProxies()
    {
        return $this->hasMany(BookmakerProxy::className(), ['proxy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmakers()
    {
        return $this->hasMany(Bookmaker::className(), ['id' => 'bookmaker_id'])->viaTable('bookmaker_proxy', ['proxy_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\ProxyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProxyQuery(get_called_class());
    }
}
