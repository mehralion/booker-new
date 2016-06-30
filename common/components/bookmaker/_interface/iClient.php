<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 28.06.2016
 */

namespace common\components\bookmaker\_interface;


interface iClient
{
    /**
     * @return self
     */
    public function clearOptions();

    /**
     * @param $options
     * @return self
     */
    public function setOptions($options);
}