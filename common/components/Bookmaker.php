<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components;


use common\components\bookmaker\_interface\iBookmaker;
use yii\base\Component;

class Bookmaker extends Component
{
    protected $bookmaker_list = [];

    /**
     * @return iBookmaker[]
     */
    public function getList()
    {
        $bookmakers = \common\models\Bookmaker::find()
            ->joinWith(['bookmakerAliases'], false)
            ->andWhere('is_enabled = 1')
            ->all();

        foreach ($bookmakers as $bookmaker) {
            if(!$bookmaker->class || !$bookmaker->is_enabled) {
                continue;
            }
            $class = $bookmaker->class;

            /** @var iBookmaker $bookmaker_obj */
            $bookmaker_obj = new $class($bookmaker);
            foreach ($bookmaker->bookmakerAliases as $alias) {
                $bookmaker_obj->addAlias($alias->host);
            }

            $this->bookmaker_list[$class] = $bookmaker_obj;
        }

        return $this->bookmaker_list;
    }
}