<?php
namespace common\components\bookmaker\parimatch\factories\validators\_interface;
use common\components\bookmaker\parimatch\factories\parsers\_interface\iParser;

/**
 * Created by PhpStorm.
 */
interface iValidator
{
    /**
     * @return boolean
     */
    public function check();

    /**
     * @return iParser
     */
    public function getParser();

    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @param $array
     * @return mixed
     */
    public function populate($array);
}