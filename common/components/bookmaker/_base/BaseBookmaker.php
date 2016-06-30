<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_base;


use common\components\bookmaker\_interface\iBookmaker;
use common\components\bookmaker\Connector;
use common\models\Bookmaker;
use common\models\Team;
use common\models\TeamAlias;

abstract class BaseBookmaker implements iBookmaker
{
    protected $aliases = [];
    protected $connector;

    /** @var Bookmaker */
    protected $settings;

    public function __construct(Bookmaker $settings)
    {
        $this->settings = $settings;
        $this->connector = $this->getConnector();
    }

    protected function getConnector()
    {
        return new Connector();
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    public function getAlias()
    {
        $key = rand(0, count($this->aliases) - 1);

        return $this->aliases[$key];
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
    public function getKey()
    {
        return $this->settings->key;
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->settings->id;
    }

    /**
     * @param Bookmaker $settings
     *
     * @return $this
     */
    public function setSettings(Bookmaker $settings)
    {
        $this->settings = $settings;
        return $this;
    }

    public function isProxyUse()
    {
        return $this->settings->use_proxy;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->is_connected;
    }

    /**
     * @param boolean $is_connected
     *
     * @return $this
     */
    public function setIsConnected($is_connected)
    {
        $this->is_connected = $is_connected;
        return $this;
    }
}