<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_interface;


use Doctrine\Common\Collections\ArrayCollection;

interface iBookmaker
{
    /**
     * @param $aliases
     * @return self
     */
    public function setAliases($aliases);

    /**
     * @param $alias
     * @return self
     */
    public function addAlias($alias);

    /**
     * @param $regexp
     * @return self
     */
    public function setRegexpIgnoreSport($regexp);

    /**
     * @param $regexp
     * @return self
     */
    public function setRegexpIgnoreEvent($regexp);

    public function connect($proxy = null);

    /**
     * @param $key
     * @return self
     */
    public function setKey($key);

    public function getKey();

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return self
     */
    public function setId($id);

    /**
     * @return iSport
     */
    public function getSport();

    /**
     * @param $sport_type
     * @return iSport[]
     */
    public function getSportList($sport_type);

    /**
     * @param $Sport
     * @return iBookmakerEvent[]|ArrayCollection
     */
    public function getEvents($Sport);

    /**
     * @param $isProxyUse
     * @return self
     */
    public function setUseProxy($isProxyUse);

    /**
     * @return boolean
     */
    public function isProxyUse();
}