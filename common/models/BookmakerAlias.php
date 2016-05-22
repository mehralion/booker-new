<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bookmaker_alias".
 *
 * @property integer $id
 * @property integer $bookmaker_id
 * @property string $host
 * @property integer $updated_at
 *
 * @property Bookmaker $bookmaker
 */
class BookmakerAlias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookmaker_alias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookmaker_id'], 'required'],
            [['bookmaker_id', 'updated_at'], 'integer'],
            [['host'], 'string', 'max' => 255],
            [['bookmaker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bookmaker::className(), 'targetAttribute' => ['bookmaker_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bookmaker_id' => 'Bookmaker ID',
            'host' => 'Host',
            'updated_at' => 'Updated At',
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
     * @inheritdoc
     * @return \common\models\query\BookmakerAliasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookmakerAliasQuery(get_called_class());
    }
}
