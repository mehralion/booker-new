<?php

namespace common\models\event;

use common\helpers\SportHelper;
use common\models\EventFixedValue;
use common\models\EventOdds;
use common\models\EventProblem;
use common\models\EventResult;
use common\models\sport\Sport;
use common\models\Team;
use common\models\TeamAlias;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $sport_id
 * @property string $sport_type
 * @property string $template
 * @property integer $started_at
 * @property integer $team_1
 * @property integer $team_1_id
 * @property integer $team_1_alias
 * @property integer $team_2
 * @property integer $team_2_id
 * @property integer $team_2_alias
 * @property string $status
 * @property integer $_v
 * @property integer $have_result
 * @property string $admin_text
 * @property integer $have_problem
 * @property string $extra_ratio
 * @property integer $is_not_auto
 * @property string $not_auto_reason
 * @property string $bookmaker
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property TeamAlias $team1Alias
 * @property Team $team1
 * @property TeamAlias $team2Alias
 * @property Team $team2
 * @property Sport $sport
 * @property EventFixedValue[] $eventFixedValues
 * @property EventOdds[] $eventOdds
 * @property EventProblem[] $eventProblems
 * @property EventResult[] $eventResults
 */
class Event extends \yii\db\ActiveRecord implements iEvent
{
    const STATUS_NEW        = 'new';
    const STATUS_ENABLE     = 'enable';
    const STATUS_DISABLE    = 'disable';
    const STATUS_FINISH     = 'finish';

    protected $current_odds_arr = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
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
     * @return BasketballEvent|FootballEvent|HokkeyEvent|TennisEvent
     * @throws \Exception
     */
    public static function getInstance($sport_type)
    {
        switch ($sport_type) {
            case SportHelper::SPORT_FOOTBALL:
                return new FootballEvent();
                break;
            case SportHelper::SPORT_TENNIS:
                return new TennisEvent();
                break;
            case SportHelper::SPORT_BASKETBALL:
                return new BasketballEvent();
                break;
            case SportHelper::SPORT_HOKKEY:
                return new HokkeyEvent();
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
            [['sport_id', 'sport_type', 'template', 'started_at', 'team_1_id', 'team_2_id', 'status'], 'required'],
            [['sport_id', 'started_at', 'team_1_id', 'team_1_alias', 'team_2_id', 'team_2_alias', '_v', 'have_result', 'have_problem', 'is_not_auto', 'updated_at', 'created_at'], 'integer'],
            [['admin_text', 'not_auto_reason', 'team_1', 'team_2'], 'string'],
            [['extra_ratio'], 'number'],
            [['team_1_alias'], 'exist', 'skipOnError' => true, 'targetClass' => TeamAlias::className(), 'targetAttribute' => ['team_1_alias' => 'id']],
            [['team_1_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_1_id' => 'id']],
            [['team_2_alias'], 'exist', 'skipOnError' => true, 'targetClass' => TeamAlias::className(), 'targetAttribute' => ['team_2_alias' => 'id']],
            [['team_2_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_2_id' => 'id']],
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
            'sport_type' => 'Sport Type',
            'template' => 'Template',
            'started_at' => 'Started At',
            'team_1_id' => 'Team 1 ID',
            'team_2_id' => 'Team 2 ID',
            'status' => 'Status',
            '_v' => 'V',
            'have_result' => 'Have Result',
            'admin_text' => 'Admin Text',
            'have_problem' => 'Have Problem',
            'extra_ratio' => 'Extra Ratio',
            'is_not_auto' => 'Is Not Auto',
            'not_auto_reason' => 'Not Auto Reason',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam1Alias()
    {
        return $this->hasOne(TeamAlias::className(), ['id' => 'team_1_alias']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam1()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_1_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam2Alias()
    {
        return $this->hasOne(TeamAlias::className(), ['id' => 'team_2_alias']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam2()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sport::className(), ['id' => 'sport_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventFixedValues()
    {
        return $this->hasMany(EventFixedValue::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventOdds()
    {
        return $this->hasMany(EventOdds::className(), ['event_id' => 'id', '_v' => '_v']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventProblems()
    {
        return $this->hasMany(EventProblem::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventResults()
    {
        return $this->hasMany(EventResult::className(), ['event_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EventQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function getCurrentOddsArr()
    {
        return $this->current_odds_arr;
    }

    /**
     * @param array $current_odds_arr
     *
     * @return $this
     */
    public function setCurrentOddsArr($current_odds_arr)
    {
        $this->current_odds_arr = $current_odds_arr;
        return $this;
    }
}
