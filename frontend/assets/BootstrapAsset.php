<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@frontend/web/themes/bootstrap';

    public $css = [
        'css/bootstrap.min.css',
        'font-awesome/css/font-awesome.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
    ];
    public $jsOptions = [
        'position' => View::POS_BEGIN
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
