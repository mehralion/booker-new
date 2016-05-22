<?php
namespace common\components\bookmaker\parimatch\factories\parsers\_interface;
/**
 * Created by PhpStorm.
 */
interface iParser
{
    /**
     * @return $this
     */
    public function run();

    /**
     * @return array
     */
    public function getEvents();
}