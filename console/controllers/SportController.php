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
use yii\helpers\Console;

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
            if($Bookmaker->connect() === false) {
                continue;
            }

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
        $Bookmakers = \Yii::$app->bookmaker->getList();
        foreach ($Bookmakers as $Bookmaker) {
            if($Bookmaker->connect() === false) {
                continue;
            }

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

            $this->log('Sport count: %d', count($SportAliases));
            foreach ($SportAliases as $alias) {
                $Sport = $Bookmaker->getSport();
                $Sport
                    ->setLink($alias['link'])
                    ->setSportType($alias['sport_type']);

                $SportEvents = $Bookmaker->getEvents($Sport);
                $this->log('Event count: %d', $SportEvents->count());
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
                        $this->log('Problem count %d', $problem_count);


                        if($model->_v == 0 || $new_odds != $old_odds) {
                            $this->log('New Odds');

                            $EventBookmaker->_v += 1;
                            if($model->bookmaker == $EventBookmaker->bookmaker) {
                                $model->_v = $EventBookmaker->_v;
                            }
                            if($EventBookmaker->_v > 1) {
                                $this->log('Put LAST to OLD and NEW to LAST');

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
                                    $this->log('Event is auto approved');
                                }
                                break;
                            case Event::STATUS_ENABLE:
                                $model->is_not_auto = false;
                                $model->not_auto_reason = null;

                                $this->log('Event is enabled. Clear auto approve settings');
                                break;
                        }

                        if(!$model->save()) {
                            throw new \Exception;
                        }

                        $this->log('Event saved');
                        $t->commit();
                    } catch (\Exception $ex) {
                        $t->rollBack();
                        var_dump($ex->getMessage());
                    }
                }
            }
        }
    }

    protected function log($string)
    {
        $args = func_get_args();
        array_shift($args);

        $this->stdout(vsprintf($string, $args)."\n", Console::FG_RED);
    }
}
