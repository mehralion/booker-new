<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 27.02.2016
 */

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = include(__DIR__ . '/../../vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container;

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property \common\components\View $view
 * @property User $user
 * @property \yii\db\Connection $db
 * @property \common\components\Phantom $phantom
 * @property \common\components\Bookmaker $bookmaker
 * @property \YiiNodeSocket\NodeSocket $nodeSocket
 * @property \common\components\Settings $settings
 *
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 *
 */
class ConsoleApplication extends yii\console\Application
{
}

/**
 * Class User
 *
 * @property \common\models\User $identity
 */
class User extends \yii\web\User
{

}