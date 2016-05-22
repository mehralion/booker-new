<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_interface;


interface iSport
{
    public function getTitle();

    /**
     * @param $title
     * @return self
     */
    public function setTitle($title);

    public function getSportType();

    /**
     * @param $sport_type
     * @return self
     */
    public function setSportType($sport_type);

    public function getLink();

    /**
     * @param $link
     * @return self
     */
    public function setLink($link);

    /**
     * @return iBookmakerEvent
     */
    public function getEvents();

    public function setEvents($events);

    public function addEvent($event);

    /**
     * @return string
     */
    public function getTemplate();

    public function setTemplate($template);
}