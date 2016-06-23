<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bookmaker".
 *
 * @property integer $id
 * @property string $title
 * @property string $key
 * @property string $ignore_sport_regexp
 * @property string $ignore_event_regexp
 * @property integer $is_enabled
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $class
 * @property int $use_proxy
 *
 * @property BookmakerAlias[] $bookmakerAliases
 * @property BookmakerProxy[] $bookmakerProxies
 * @property Proxy[] $proxies
 */
class Bookmaker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookmaker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'key'], 'required'],
            [['ignore_sport_regexp', 'ignore_event_regexp'], 'string'],
            [['is_enabled', 'updated_at', 'created_at', 'use_proxy'], 'integer'],
            [['title', 'key', 'class'], 'string', 'max' => 255],
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
            'key' => 'Key',
            'ignore_sport_regexp' => 'Ignore Sport Regexp',
            'ignore_event_regexp' => 'Ignore Event Regexp',
            'is_enabled' => 'Is Enabled',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'class' => 'Class',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmakerAliases()
    {
        return $this->hasMany(BookmakerAlias::className(), ['bookmaker_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmakerProxies()
    {
        return $this->hasMany(BookmakerProxy::className(), ['bookmaker_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProxies()
    {
        return $this->hasMany(Proxy::className(), ['id' => 'proxy_id'])->viaTable('bookmaker_proxy', ['bookmaker_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\BookmakerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookmakerQuery(get_called_class());
    }
}
