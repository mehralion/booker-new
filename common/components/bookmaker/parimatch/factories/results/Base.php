<?php
namespace common\components\bookmaker\parimatch\factories\results;
use common\components\bookmaker\parimatch\factories\results\_interfaces\iResult;
use phpQuery;
use phpQueryObject;
/**
 * Created by PhpStorm.
 */
abstract class Base implements iResult
{
    /** @var null|string */
    private $_html = null;
    /** @var \phpQueryObject */
    protected $dom;
    /** @var string */
    protected $team1;
    /** @var string */
    protected $team2;
    /** @var array */
    protected $team_list = [];

    protected abstract function getListData();

    public function __construct($html, $team1, $team2)
    {
        $this
            ->setHtml($html)
            ->setDom(phpQuery::newDocument($html))
            ->newTeams($team1, $team2);
    }

    public function newTeams($team1, $team2)
    {
        $team1 = mb_strtolower($team1);
        $team2 = mb_strtolower($team2);

        $this
            ->setTeam1($team1)
            ->setTeam2($team2)
            ->prepareTeams();

        return $this;
    }

    /**
     * @return null|string
     */
    public function getHtml()
    {
        return $this->_html;
    }

    /**
     * @param null|string $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    /**
     * @return phpQueryObject
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param phpQueryObject $dom
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * @param string $team1
     * @return $this
     */
    public function setTeam1($team1)
    {
        $this->team1 = $team1;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * @param string $team2
     * @return $this
     */
    public function setTeam2($team2)
    {
        $this->team2 = $team2;
        return $this;
    }

    /**
     * @return array
     */
    public function getTeamList()
    {
        return $this->team_list;
    }

    /**
     * @param array $team_list
     * @return $this
     */
    public function setTeamList($team_list)
    {
        $this->team_list = $team_list;
        return $this;
    }

    protected function prepareTeams()
    {
        $returned = [];
        $returned[] = trim(preg_replace('/\(.+?\)/ui', '', $this->getTeam1()), ',.');
        $returned[] = trim(preg_replace('/\(.+?\)/ui', '', $this->getTeam2()), ',.');
        foreach ([$this->getTeam1(), $this->getTeam2()] as $team) {
            if(!in_array($team, $returned))
                $returned[] = $team;

            if(preg_match('/  /ui', $team))
                $returned[] = str_replace('  ', ' ', $team);
        }

        $this->setTeamList($returned);

        return $returned;
    }

    /**
     * @param $team
     * @return bool
     */
    protected function hasTeam($team)
    {
        return in_array($team, $this->getTeamList());
    }

    /**
     * @param $team
     * @return string
     */
    protected function prepareTeam($team)
    {
        return mb_strtolower(trim(strip_tags($team), ',. '));
    }
}