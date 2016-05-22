<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_base;


use common\components\bookmaker\_interface\iBookmakerEvent;
use common\components\bookmaker\_interface\iSport;
use Doctrine\Common\Collections\ArrayCollection;

abstract class BaseSport implements iSport
{
    public $title;
    public $sport_type;
    public $link;
    public $template;
    /** @var ArrayCollection */
    public $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSportType()
    {
        return $this->sport_type;
    }

    /**
     * @param mixed $sport_type
     *
     * @return $this
     */
    public function setSportType($sport_type)
    {
        $this->sport_type = $sport_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return ArrayCollection|iBookmakerEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     *
     * @return $this
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * @param iBookmakerEvent $event
     * @return $this
     */
    public function addEvent($event)
    {
        if(!$this->events->contains($event)) {
            $this->events->add($event);
        }

        return $this;
    }
}