<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\parimatch;


use common\components\bookmaker\_base\BaseEvent;

class Event extends BaseEvent
{
    public function setDate($date)
    {
        $arr = explode(' ', $date);
        $date_arr = explode('.', $arr[0]);
        $time_arr = explode(':', $arr[1]);

        $date_string = sprintf('%d-%d-%d %d:%d:%d',
            $date_arr[2], $date_arr[1], $date_arr[0], $time_arr[0], $time_arr[1], $time_arr[2]);

        $DateTime = new \DateTime($date_string);

        $this->date = $DateTime->getTimestamp();
        
        return $this;
    }
}