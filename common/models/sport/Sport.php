<?php

namespace common\models\sport;

use common\helpers\SportHelper;
use common\models\event\Event;
use common\models\SportAlias;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sport".
 *
 * @property integer $id
 * @property string $title
 * @property integer $event_count
 * @property integer $event_active_count
 * @property string $sport_type
 * @property string $template
 * @property string $sport_id
 * @property string $status
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Event[] $events
 * @property SportAlias[] $sportAliases
 */
class Sport extends \yii\db\ActiveRecord implements iSport
{
    const STATUS_ENABLE     = 'enable';
    const STATUS_DISABLE    = 'disable';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sport';
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

    public static function instantiate($row)
    {
        return self::getInstance($row['sport_type']);
    }

    /**
     * @param $sport_type
     * @return BasketballSport|FootballSport|HokkeySport|TennisSport
     * @throws \Exception
     */
    public static function getInstance($sport_type)
    {
        switch ($sport_type) {
            case SportHelper::SPORT_FOOTBALL:
                return new FootballSport();
                break;
            case SportHelper::SPORT_TENNIS:
                return new TennisSport();
                break;
            case SportHelper::SPORT_BASKETBALL:
                return new BasketballSport();
                break;
            case SportHelper::SPORT_HOKKEY:
                return new HokkeySport();
                break;
        }

        throw new \Exception('Вид спорта не найден', 420);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_count', 'event_active_count', 'updated_at', 'created_at'], 'integer'],
            [['sport_type'], 'required'],
            [['title', 'sport_type', 'template', 'sport_id', 'status'], 'string', 'max' => 255],
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
            'event_count' => 'Event Count',
            'event_active_count' => 'Event Active Count',
            'sport_type' => 'Sport Type',
            'template' => 'Template',
            'sport_id' => 'Sport ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['sport_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSportAliases()
    {
        return $this->hasMany(SportAlias::className(), ['sport_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\SportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SportQuery(get_called_class());
    }
}
