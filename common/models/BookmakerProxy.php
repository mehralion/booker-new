<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bookmaker_proxy".
 *
 * @property integer $bookmaker_id
 * @property integer $proxy_id
 * @property string $alias
 *
 * @property Bookmaker $bookmaker
 * @property Proxy $proxy
 */
class BookmakerProxy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookmaker_proxy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookmaker_id', 'proxy_id', 'alias'], 'required'],
            [['bookmaker_id', 'proxy_id'], 'integer'],
            [['bookmaker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bookmaker::className(), 'targetAttribute' => ['bookmaker_id' => 'id']],
            [['proxy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proxy::className(), 'targetAttribute' => ['proxy_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bookmaker_id' => 'Bookmaker ID',
            'proxy_id' => 'Proxy ID',
            'alias' => 'Alias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmaker()
    {
        return $this->hasOne(Bookmaker::className(), ['id' => 'bookmaker_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProxy()
    {
        return $this->hasOne(Proxy::className(), ['id' => 'proxy_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\BookmakerProxyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookmakerProxyQuery(get_called_class());
    }
}
