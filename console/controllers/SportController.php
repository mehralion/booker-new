<?php
namespace console\controllers;
use common\factories\problem\event\FactoryProblemEvent;
use common\helpers\SportHelper;
use common\models\event\Event;
use common\models\EventBookmakerVersion;
use common\models\EventOdds;
use common\models\EventProblem;
use common\models\sport\Sport;
use common\models\SportAlias;
use yii\console\Controller;

/**
 * Site controller
 */
class SportController extends Controller
{
    public function actionSport($sport_type)
    {
        if(!SportHelper::checkSport($sport_type)) {
            //@TODO Logging
            return;
        }

        if(!\Yii::$app->bookmaker->haveBookmaker()) {
            //@TODO Logging
            return;
        }

        $Bookmakers = \Yii::$app->bookmaker->getList();
        foreach ($Bookmakers as $Bookmaker) {
            $SportList = $Bookmaker->getSportList($sport_type);
            var_dump('Sport: '.count($SportList));
            $titles = [];
            foreach ($SportList as $Sport) {
                $titles[] = $Sport->getTitle();
            }

            $sport_id_by_title = SportAlias::find()
                ->select(['sport_id', 'id', 'title'])
                ->indexBy('title')
                ->andWhere(['in', 'title', $titles])
                ->andWhere('bookmaker = :bookmaker', [':bookmaker' => $Bookmaker->getKey()])
                ->all();

            $SportModels = Sport::find()
                ->select(['id', 'sport_type'])
                ->indexBy('id')
                ->andWhere(['in', 'id', array_values($sport_id_by_title)])
                ->all();

            $sport_ids = [];
            foreach ($SportList as $Sport) {
                $t = \Yii::$app->db->beginTransaction();
                try {
                    if(isset($sport_id_by_title[$Sport->getTitle()])) {
                        $sport_alias = $sport_id_by_title[$Sport->getTitle()];
                        $model = $SportModels[$sport_alias->sport_id];
                    } else {
                        $model = Sport::getInstance($sport_type);
                    }

                    $model->status = Sport::STATUS_ENABLE;
                    $model->title = $Sport->getTitle();
                    if(!$model->save()) {
                        throw new \Exception;
                    }

                    $alias = isset($sport_id_by_title[$Sport->getTitle()]) ? $sport_id_by_title[$Sport->getTitle()]: new SportAlias();
                    $alias->sport_id = $model->id;
                    $alias->title = $model->title;
                    $alias->bookmaker = $Bookmaker->getKey();
                    $alias->link = $Sport->getLink();
                    if(!$alias->save()) {
                        throw new \Exception;
                    }

                    $sport_ids[] = $model->id;

                    $t->commit();
                } catch (\Exception $ex) {
                    $t->rollBack();
                    var_dump($ex->getMessage());
                    var_dump($ex->getTraceAsString());die;
                    //@TODO Logging
                }
            }

            Sport::updateAll([
                'status' => Sport::STATUS_DISABLE,
                'updated_at' => time(),
            ], ['and',
                ['not in', 'id', $sport_ids],
                'sport_type = :sport_type'
            ], [':sport_type' => $sport_type]);
        }
    }

    public function actionEvent()
    {
        if(!\Yii::$app->bookmaker->haveBookmaker()) {
            //@TODO Logging
            return;
        }

        $Bookmakers = \Yii::$app->bookmaker->getList();
        foreach ($Bookmakers as $Bookmaker) {
            $SportAliases = SportAlias::find()
                ->alias('t')
                ->select(['t.link', 't.sport_id'])
                ->with([
                    'sport' => function (\yii\db\ActiveQuery $query) {
                        $query
                            ->select(['sport_type', 'id'])
                            ->andWhere('status = :enable', [':enable' => Sport::STATUS_ENABLE]);
                    }
                ])
                ->andWhere('bookmaker = :bookmaker', [':bookmaker' => $Bookmaker->getKey()])
                ->asArray()
                ->all();

            foreach ($SportAliases as $alias) {
                $Sport = $Bookmaker->getSport();
                $Sport
                    ->setLink($alias['link'])
                    ->setSportType($alias['sport']['sport_type']);

                $SportEvents = $Bookmaker->getEvents($Sport);
                var_dump('Events: '.$SportEvents->count());
                if($SportEvents->isEmpty()) {
                    continue;
                }

                foreach ($SportEvents as $key => $Event) {
                    $Event->setSportId($alias['sport']['id']);
                    
                    $t = \Yii::$app->db->beginTransaction();
                    try {
                        $new_odds = $Event->getOdds();

                        $model = Event::find()
                            ->andWhere('team_1_id = :team_1_id and team_2_id = :team_2_id', [
                                ':team_1_id' => $Event->getTeam1Alias()->team_id,
                                ':team_2_id' => $Event->getTeam2Alias()->team_id,
                            ])
                            ->andWhere(['in', 'status', [Event::STATUS_ENABLE, Event::STATUS_NEW]])
                            ->andWhere('started_at = :date or ((started_at - :date) < 144000 and (started_at - :date) > 0) or ((started_at - :date) > -144000 and (started_at - :date) < 0)', [
                                ':date' => $Event->getDate()
                            ])
                            ->with([
                                'eventFixedValues',
                                'eventBookmakerVersion' => function (\yii\db\ActiveQuery $query) use ($Bookmaker) {
                                    $query
                                        ->andWhere('bookmaker = :bookmaker', [':bookmaker' => $Bookmaker->getKey()]);
                                }
                            ])
                            ->one();
                        if(!$model) {
                            $model                  = Event::getInstance($Sport->getSportType());

                            $model->team_1          = $Event->getTeam1Alias()->title;
                            $model->team_1_id       = $Event->getTeam1Alias()->team_id;
                            $model->team_1_alias    = $Event->getTeam1Alias()->id;

                            $model->team_2          = $Event->getTeam2Alias()->title;
                            $model->team_2_id       = $Event->getTeam2Alias()->team_id;
                            $model->team_2_alias    = $Event->getTeam2Alias()->id;

                            $model->sport_id        = $alias['sport']['id'];
                            $model->sport_type      = $alias['sport']['sport_type'];
                            $model->template        = $Sport->getTemplate();
                            $model->started_at      = $Event->getDate();
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

                            $model->setCurrentOddsArr($new_odds);
                        } else {
                            $EventBookmaker = $model->eventBookmakerVersion;

                            $old_odds = [];
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
                            $model->setCurrentOddsArr($old_odds);
                        }

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


                        if($model->_v == 0 || $new_odds != $model->getCurrentOddsArr()) {
                            $EventBookmaker->_v += 1;
                            if($model->bookmaker == $EventBookmaker->bookmaker) {
                                $model->_v = $EventBookmaker->_v;
                            }
                            if($EventBookmaker > 1) {
                                EventOdds::toOld($model->id, $EventBookmaker->bookmaker);
                                EventOdds::toLast($model->id, $EventBookmaker->bookmaker);
                            }

                            EventOdds::add($model->id, $EventBookmaker->bookmaker, $EventBookmaker->_v, $new_odds);
                        }
                        if(!$EventBookmaker->save()) {
                            throw new \Exception;
                        }
                        if(!$model->save()) {
                            throw new \Exception;
                        }

                        var_dump($key);
                        $t->commit();
                    } catch (\Exception $ex) {
                        $t->rollBack();
                        var_dump($ex->getMessage());
                    }
                }
            }
        }
    }
}
