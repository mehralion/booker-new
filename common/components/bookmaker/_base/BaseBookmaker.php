<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_base;


use common\components\bookmaker\_interface\iBookmaker;
use common\models\Team;
use common\models\TeamAlias;

abstract class BaseBookmaker implements iBookmaker
{
    protected $aliases = [];
    protected $regexp_ignore_sport;
    protected $regexp_ignore_event;
    protected $key;
    protected $work_host = null;
    protected $is_proxy_use = false;
    protected $id;

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @param array $aliases
     *
     * @return $this
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
        return $this;
    }

    public function addAlias($alias)
    {
        if(!in_array($alias, $this->aliases)) {
            $this->aliases[] = $alias;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegexpIgnoreSport()
    {
        return $this->regexp_ignore_sport;
    }

    /**
     * @param mixed $regexp_ignore_sport
     *
     * @return $this
     */
    public function setRegexpIgnoreSport($regexp_ignore_sport)
    {
        $this->regexp_ignore_sport = $regexp_ignore_sport;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegexpIgnoreEvent()
    {
        return $this->regexp_ignore_event;
    }

    /**
     * @param mixed $regexp_ignore_event
     *
     * @return $this
     */
    public function setRegexpIgnoreEvent($regexp_ignore_event)
    {
        $this->regexp_ignore_event = $regexp_ignore_event;
        return $this;
    }

    /**
     * @return null
     */
    public function getWorkHost()
    {
        return $this->work_host;
    }

    /**
     * @param null $work_host
     *
     * @return $this
     */
    public function setWorkHost($work_host)
    {
        $this->work_host = $work_host;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param $team
     * @return TeamAlias
     */
    protected function getTeamAlias($team)
    {
        $TeamAlias = TeamAlias::find()
            ->andWhere('title = :title', [':title' => $team])
            ->one();
        if($TeamAlias) {
            return $TeamAlias;
        }

        $Team = new Team();
        $Team->title = $team;
        $Team->save();

        $TeamAlias = new TeamAlias();
        $TeamAlias->team_id = $Team->id;
        $TeamAlias->title = $team;
        $TeamAlias->save();

        return $TeamAlias;
    }

    public function setUseProxy($isProxyUse)
    {
        $this->is_proxy_use = $isProxyUse;
        return $this;
    }

    public function isProxyUse()
    {
        return $this->is_proxy_use;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}