<?php
define('ROOT_DIR', realpath(__DIR__ . '/../../'));

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('@localVendor', dirname(dirname(__DIR__)) . '/common/vendor');

date_default_timezone_set('Europe/Moscow');