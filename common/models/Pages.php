<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $dir
 * @property string $title
 * @property string $text
 * @property integer $updated_at
 * @property integer $created_at
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'updated_at',
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
            [['dir', 'title', 'text'], 'required'],
            [['text'], 'string'],
            [['updated_at', 'created_at'], 'integer'],
            [['dir', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dir' => 'Dir',
            'title' => 'Title',
            'text' => 'Text',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\PagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PagesQuery(get_called_class());
    }
}
