<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_interface;


use common\models\Bookmaker;
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
     * @param string $alias
     * @param null $proxy
     * @return boolean
     */
    public function connect($alias, $proxy = null);

    /**
     * @param null $proxy
     * @return array
     */
    public function checkAll($proxy = null);

    /**
     * @return int
     */
    public function getId();

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
     * @return boolean
     */
    public function isProxyUse();

    /**
     * @param Bookmaker $settings
     * @return mixed
     */
    public function setSettings(Bookmaker $settings);
}