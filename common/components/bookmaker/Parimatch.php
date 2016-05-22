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
        foreach ($this->getAliases() as $alias) {
            $response = $this->client->get($alias, true);
            if(preg_match('/Результаты live/ui', $response)) {
                $this->work_host = $alias;

                return true;
            }
        }

        return false;
    }

    public function getSportList($sport_type)
    {
        $returned = [];

        if(!isset($this->sport_link[$sport_type])) {
            return $returned;
        }

        $response = $this->client
            ->referer($this->work_host)
            ->get(sprintf('%s/%s', rtrim($this->work_host, '/'), ltrim($this->sport_link[$sport_type], '/')));
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
        $events = new ArrayCollection();

        $response = $this->client
            ->referer($this->work_host)
            ->get(sprintf('%s/%s', rtrim($this->work_host, '/'), ltrim($Sport->getLink(), '/')));
        if(!$response) {
            return $events;
        }

        $Validator = ParserValidate::getValidator($Sport->getSportType(), '<html>'.$response.'</html>');
        if($Validator === false) {
            return $events;
        }
        $items = $Validator->getParser()->run()->getEvents();
        $template_name = $Validator->getTemplateName();
        unset($Validator);

        $Sport->setTemplate($template_name);
        foreach ($items as $item) {
            if(preg_match('/'.$this->regexp_ignore_event.'/ui', $item['team_1'])) {
                continue;
            }
            if(preg_match('/'.$this->regexp_ignore_event.'/ui', $item['team_2'])) {
                continue;
            }

            $Event = new Event();
            $Event->setDate($item['date_string'])
                ->setTeam1Alias($this->getTeamAlias($item['team_1']))
                ->setTeam2Alias($this->getTeamAlias($item['team_2']))
                ->setOdds($item['ratio_list']);

            if($Event->getDate() > time()) {
                continue;
            }

            if(!$events->contains($Event)) {
                $events->add($Event);
            }
        }

        return $events;
    }
}