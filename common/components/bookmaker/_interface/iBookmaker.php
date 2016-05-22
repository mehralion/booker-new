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

    public function connect();

    /**
     * @param $key
     * @return self
     */
    public function setKey($key);


    public function getKey();

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
}