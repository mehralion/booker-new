<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_base;


use common\components\bookmaker\_interface\iBookmakerEvent;
use common\factories\problem\event\_interface\iProblemEvent;
use common\models\event\Event;
use common\models\event\iEvent;
use common\models\EventProblem;

/**
 * Class BaseProblemEvent
 * @package common\factories\problem\event\_base
 *
 */
abstract class BaseProblemEvent implements iProblemEvent
{
    abstract protected function getProblemType();
    abstract protected function prepare();
    abstract protected function checkSame($LastSameProblem);
    abstract protected function getParams();
    abstract protected function getMessage();

    /** @var iBookmakerEvent */
    protected $bookmaker_event;
    /** @var iEvent|Event */
    protected $event;

    protected $is_problem = true;

    /**
     * @param mixed $bookmaker_event
     *
     * @return $this
     */
    public function setBookmakerEvent($bookmaker_event)
    {
        $this->bookmaker_event = $bookmaker_event;
        return $this;
    }

    /**
     * @param mixed $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isProblem()
    {
        return $this->is_problem;
    }

    /**
     * @param boolean $is_problem
     *
     * @return $this
     */
    public function setIsProblem($is_problem)
    {
        $this->is_problem = $is_problem;
        return $this;
    }

    /**
     * @return array|EventProblem|null
     */
    protected function getLastSameProblem()
    {
        return EventProblem::find()
            ->andWhere('event_id = :event_id and problem_type = :problem_type', [
                ':event_id'     => $this->event->id,
                ':problem_type' => $this->getProblemType(),
            ])
            ->orderBy('id desc')
            ->asArray()
            ->one();
    }

    public function check()
    {
        $this->prepare();

        $LastSameProblem = $this->getLastSameProblem();
        if(!$LastSameProblem && $this->isProblem() === false) {
            return;
        }

        if(!$LastSameProblem || !$this->checkSame($LastSameProblem)) {
            $EventProblem = new EventProblem();
            $EventProblem->event_id = $this->event->id;
            $EventProblem->problem_type = $this->getProblemType();
            $EventProblem->custom = serialize($this->getParams());
            $EventProblem->description = $this->getMessage();
            $EventProblem->save();
        } else {
            EventProblem::updateAll([
                'resolver_id'   => \Yii::$app->params['AutoResolveProblem'],
                'is_resolved'   => 1,
                'updated_at'    => time(),
                'resolved_at'   => time(),
            ]);
        }
    }
}