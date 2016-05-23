<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace console\controllers;


use YiiNodeSocket\NodeSocketCommand;

class NodeSocketController extends NodeSocketCommand
{
    public function actionInit() {
        $this->compileServer();
        $this->compileClient();
        exit(1);
    }
}