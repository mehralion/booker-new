<?php
namespace frontend\controllers;

use common\models\event\FootballEvent;
use common\models\EventOdds;
use frontend\components\WebController;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends WebController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $Events = FootballEvent::find()
            ->with(['sport'])
            ->forUser()
            ->limit(15)
            ->indexBy('id')
            ->orderBy('started_at')
            ->all();

        EventOdds::find()
            ->andWhere(['in', 'event_id', array_keys($Events)])
            ->andWhere(['in', 'type', ['ratio_p1', 'ratio_p2', 'ratio_x']])
            ->andWhere('position = :position', EventOdds::POSITION_NEW)
            ->asArray()
            ->all();

        return $this->render('index');
    }
}
