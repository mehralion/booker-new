<?php
use common\models\SportAlias;
use common\models\sport\Sport;
use common\models\event\Event;
use common\models\EventBookmakerVersion;
use common\models\EventProblem;
use common\models\EventOdds;
use common\factories\problem\event\FactoryProblemEvent;


/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 13.05.2016
 */ ?>


<?php
if(!\Yii::$app->bookmaker->haveBookmaker()) {
    //@TODO Logging
    return;
}

$Bookmakers = \Yii::$app->bookmaker->getList();
foreach ($Bookmakers as $Bookmaker) {
    $SportAliases = SportAlias::find()
        ->alias('t')
        ->select(['t.link', 't.sport_id', 'sport.sport_type'])
        ->joinWith([
            'sport' => function (\yii\db\ActiveQuery $query) {
                $query->select('id');
            }
        ], true, 'INNER JOIN')
        ->andWhere('bookmaker = :bookmaker', [':bookmaker' => $Bookmaker->getKey()])
        ->andWhere('sport.status = :enable', [':enable' => Sport::STATUS_ENABLE])
        ->asArray()
        ->all();

    foreach ($SportAliases as $alias) {
        $Sport = $Bookmaker->getSport();
        $Sport
            ->setLink($alias['link'])
            ->setSportType($alias['sport_type']);

        $SportEvents = $Bookmaker->getEvents($Sport);
        if($SportEvents->isEmpty()) {
            continue;
        }

        foreach ($SportEvents as $key => $Event) {
            $Event->setSportId($alias['sport_id']);

            $t = \Yii::$app->db->beginTransaction();
            try {
                $model = Event::find()
                    ->with(['eventFixedValues'])
                    ->joinWith([
                        'eventBookmakerVersion' => function (\yii\db\ActiveQuery $query) {
                            $query->alias('ebv');
                        }
                    ], true, 'INNER JOIN')
                    ->andWhere('team_1_id = :team_1_id and team_2_id = :team_2_id', [
                        ':team_1_id' => $Event->getTeam1Alias()->team_id,
                        ':team_2_id' => $Event->getTeam2Alias()->team_id,
                    ])
                    ->andWhere(['in', 'status', [Event::STATUS_ENABLE, Event::STATUS_NEW]])
                    ->andWhere('started_at = :date or ((started_at - :date) < 144000 and (started_at - :date) > 0) or ((started_at - :date) > -144000 and (started_at - :date) < 0)', [
                        ':date' => $Event->getDate()
                    ])
                    ->andWhere('ebv.bookmaker = :bookmaker', [':bookmaker' => $Bookmaker->getKey()])
                    ->one();

                $new_odds = $Event->getOdds();
                $old_odds = [];
                if(!$model) {
                    $model                  = Event::getInstance($Sport->getSportType());

                    $model->team_1          = $Event->getTeam1Alias()->title;
                    $model->team_1_id       = $Event->getTeam1Alias()->team_id;
                    $model->team_1_alias    = $Event->getTeam1Alias()->id;

                    $model->team_2          = $Event->getTeam2Alias()->title;
                    $model->team_2_id       = $Event->getTeam2Alias()->team_id;
                    $model->team_2_alias    = $Event->getTeam2Alias()->id;

                    $model->sport_type      = $alias['sport_type'];
                    $model->template        = $Sport->getTemplate();
                    $model->status          = Event::STATUS_NEW;
                    $model->_v              = 0;
                    $model->bookmaker       = $Bookmaker->getKey();
                    if(!$model->save()) {
                        throw new \Exception;
                    }

                    $EventBookmaker = new EventBookmakerVersion();
                    $EventBookmaker->event_id = $model->id;
                    $EventBookmaker->bookmaker = $Bookmaker->getKey();
                    $EventBookmaker->_v = 0;
                    if(!$EventBookmaker->save()) {
                        throw new \Exception;
                    }

                } else {
                    $EventBookmaker = $model->eventBookmakerVersion;

                    $Odds = EventOdds::find()
                        ->select(['field', 'value'])
                        ->andWhere('event_id = :event_id and _v = :_v', [
                            ':event_id' => $EventBookmaker->event_id,
                            ':_v'       => $EventBookmaker->_v
                        ])
                        ->asArray()
                        ->all();
                    foreach ($Odds as $_item) {
                        $old_odds[$_item['field']] = $_item['value'];
                    }
                    $model->getOldOdds()->setAttributes($old_odds);
                }
                $model->getNewOdds()->setAttributes($new_odds);
                $model->sport_id        = $alias['sport_id'];
                $model->started_at      = $Event->getDate();

                $problem_list = [
                    EventProblem::PROBLEM_DATE,
                    EventProblem::PROBLEM_FORA,
                    EventProblem::PROBLEM_SPORT_ID,
                ];
                foreach ($problem_list as $problem_type) {
                    $ProblemChecker = FactoryProblemEvent::factory($Sport->getSportType(), $problem_type);
                    $ProblemChecker->setBookmakerEvent($Event)
                        ->setEvent($model)
                        ->check();
                }
                $problem_count = EventProblem::find()
                    ->andWhere('event_id = :event_id and is_resolved = 0', [
                        ':event_id' => $model->id
                    ])->count();
                $model->have_problem = $problem_count > 0 ? 1 : 0;

                if($model->_v == 0 || $new_odds != $old_odds) {
                    $EventBookmaker->_v += 1;
                    if($model->bookmaker == $EventBookmaker->bookmaker) {
                        $model->_v = $EventBookmaker->_v;
                    }
                    if($EventBookmaker->_v > 1) {
                        EventOdds::toOld($model->id, $EventBookmaker->bookmaker);
                        EventOdds::toLast($model->id, $EventBookmaker->bookmaker);
                    }

                    EventOdds::add($model->id, $EventBookmaker->bookmaker, $EventBookmaker->_v, $new_odds);
                }
                if(!$EventBookmaker->save()) {
                    throw new \Exception;
                }

                switch ($model->status) {
                    case Event::STATUS_NEW:
                        if(\Yii::$app->settings->book()->autoapprove && $model->canAuto()) {
                            $model->status = Event::STATUS_ENABLE;
                        }
                        break;
                    case Event::STATUS_ENABLE:
                        $model->is_not_auto = false;
                        $model->not_auto_reason = null;

                        break;
                }

                if(!$model->save()) {
                    throw new \Exception;
                }

                $t->commit();
                break 3;
            } catch (\Exception $ex) {
                $t->rollBack();
                echo '<pre>';
                var_dump($ex->getMessage());
                var_dump($ex->getTraceAsString());die;
            }
        }
    }
}
?>