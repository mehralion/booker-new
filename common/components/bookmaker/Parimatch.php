<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker;


use common\components\bookmaker\_base\BaseBookmaker;
use common\components\bookmaker\_interface\iBookmakerEvent;
use common\components\bookmaker\_interface\iSport;
use common\components\bookmaker\parimatch\Event;
use common\components\bookmaker\parimatch\Sport;
use common\helpers\SportHelper;
use common\models\Team;
use common\models\TeamAlias;
use Doctrine\Common\Collections\ArrayCollection;
use simplehtmldom_1_5\simple_html_dom;
use simplehtmldom_1_5\simple_html_dom_node;
use common\components\bookmaker\parimatch\factories\ParserValidate;

class Parimatch extends BaseBookmaker
{
    protected $client;

    protected $sport_link = [
        SportHelper::SPORT_FOOTBALL     => 'sport/futbol',
        SportHelper::SPORT_TENNIS       => 'sport/tennis',
        SportHelper::SPORT_BASKETBALL   => 'sport/basketbol',
        SportHelper::SPORT_HOKKEY       => 'sport/khokkejj',
    ];

    public function __construct()
    {
        $this->client = \Yii::$app->phantom;
        $this->client
            ->useProxy(true)
            ->delay(8);
    }

    public function connect()
    {
        $time_start = microtime(true);

        $r = false;
        foreach ($this->getAliases() as $alias) {
            $response = $this->client->get($alias, true);
            if(preg_match('/Результаты live/ui', $response)) {
                $this->work_host = $alias;

                $r = true;
                break;
            }
        }
        
        \Yii::trace(sprintf('Тестовый коннект: %s сек. Результат: %d', microtime(true) - $time_start, $r ? 1 : 0));
        return $r;
    }

    public function getSportList($sport_type)
    {
        $returned = [];

        if(!isset($this->sport_link[$sport_type])) {
            return $returned;
        }

        $response = $this->client
            ->referer($this->work_host)
            ->get(sprintf('%s/%s', rtrim($this->work_host, '/'), ltrim($this->sport_link[$sport_type], '/')), true);

        /** @var simple_html_dom $dom */
        $dom = \Sunra\PhpSimple\HtmlDomParser::str_get_html('<html>'.$response.'</html>');
        /** @var simple_html_dom_node $sport_el */
        foreach ($dom->find('ul[id=sports] li a') as $sport_el) {
            $title = $sport_el->text();
            if(preg_match('/'.$this->regexp_ignore_sport.'/ui', $title)) {
                continue;
            }

            $Sport = $this->getSport();
            $Sport
                ->setLink($sport_el->getAttribute('href'))
                ->setTitle($title)
                ->setSportType($sport_type);
            $returned[] = $Sport;
        }

        return $returned;
    }

    public function getSport()
    {
        return new Sport();
    }

    /**
     * @param iSport $Sport
     * @return iBookmakerEvent[]
     */
    public function getEvents($Sport)
    {
        $team_list = [];

        $events = new ArrayCollection();

        $time_start = microtime(true);
        $response = $this->client
            ->referer($this->work_host)
            ->get(sprintf('%s/%s', rtrim($this->work_host, '/'), ltrim($Sport->getLink(), '/')), true);
        \Yii::trace(sprintf('Получили HTML лиг: %s сек.', microtime(true) - $time_start));
        if(!$response) {
            return $events;
        }

        $time_start = microtime(true);
        $Validator = ParserValidate::getValidator($Sport->getSportType(), '<html>'.$response.'</html>');
        if($Validator === false) {
            return $events;
        }
        \Yii::trace(sprintf('Определили парсер: %s сек.', microtime(true) - $time_start));

        $time_start = microtime(true);
        $items = $Validator->getParser()->run()->getEvents();
        \Yii::trace(sprintf('Распарсили: %s сек.', microtime(true) - $time_start));

        $template_name = $Validator->getTemplateName();
        unset($Validator);

        $Sport->setTemplate($template_name);
        foreach ($items as $item) {
            if($this->regexp_ignore_event && preg_match('/'.$this->regexp_ignore_event.'/ui', $item['team_1'])) {
                continue;
            }
            if($this->regexp_ignore_event && preg_match('/'.$this->regexp_ignore_event.'/ui', $item['team_2'])) {
                continue;
            }

            $Event = new Event();
            $Event->setDate($item['date_string'])
                ->setTeam1($item['team_1'])
                ->setTeam2($item['team_2'])
                ->setOdds($item['ratio_list']);

            if($Event->getDate() < time()) {
                continue;
            }

            if(!$events->contains($Event)) {
                $events->add($Event);


                foreach (['team_1', 'team_2'] as $team) {
                    if(!in_array($item[$team], $team_list)) {
                        $team_list[] = $item[$team];
                    }
                }
            }
        }

        $AliasList = TeamAlias::find()
            ->indexBy('title')
            ->andWhere(['in', 'title', $team_list])
            ->all();
        /** @var Event $event */
        foreach ($events as $event) {
            $team_1 = $event->getTeam1();
            $team_2 = $event->getTeam2();

            $TeamAlias1 = isset($AliasList[$team_1]) ? $AliasList[$team_1] : $this->createTeam($team_1);
            $TeamAlias2 = isset($AliasList[$team_2]) ? $AliasList[$team_2] : $this->createTeam($team_2);

            $event->setTeam1Alias($TeamAlias1);
            $event->setTeam2Alias($TeamAlias2);
        }

        return $events;
    }

    private function createTeam($team)
    {
        $Team = new Team();
        $Team->title = $team;
        $Team->save();

        $TeamAlias = new TeamAlias();
        $TeamAlias->team_id = $Team->id;
        $TeamAlias->title = $team;
        $TeamAlias->save();

        return $TeamAlias;
    }
}