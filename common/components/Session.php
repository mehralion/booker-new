<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\components;


class Session extends \yii\web\Session
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->open();
    }
}