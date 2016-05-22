<?php

namespace common\models;

use common\models\sport\Sport;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sport_alias".
 *
 * @property integer $id
 * @property integer $sport_id
 * @property string $title
 * @property string $bookmaker
 * @property string $link
 * @property integer $updated_at
 *
 * @property Sport $sport
 */
class SportAlias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sport_alias';
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
            [['sport_id', 'title'], 'required'],
            [['sport_id', 'updated_at'], 'integer'],
            [['title', 'bookmaker', 'link'], 'string', 'max' => 255],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::className(), 'targetAttribute' => ['sport_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sport_id' => 'Sport ID',
            'title' => 'Title',
            'bookmaker' => 'Bookmaker',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sport::className(), ['id' => 'sport_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\SportAliasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SportAliasQuery(get_called_class());
    }
}
