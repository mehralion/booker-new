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
 * @property string $class
 * @property integer $is_enabled
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property BookmakerAlias[] $bookmakerAliases
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
            [['updated_at', 'created_at', 'is_enabled'], 'integer'],
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
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
     * @inheritdoc
     * @return \common\models\query\BookmakerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookmakerQuery(get_called_class());
    }
}
