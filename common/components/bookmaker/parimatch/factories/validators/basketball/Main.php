<?php
namespace common\components\bookmaker\parimatch\factories\validators\basketball;
use common\components\bookmaker\parimatch\factories\validators\Base;
use common\components\bookmaker\parimatch\factories\parsers\basketball\Main as BasketballParserStatistic;

/**
 * Created by PhpStorm.
 */
class Main extends Base
{
    private $thFieldName = [
        '№',
        'Дата',
        'Событие',
        'Фора',
        'КФ',
        'Т',
        'Б',
        'М',
        'П1',
        'X',
        'П2',
        'iТ',
        'Б',
        'М'
    ];

    public function check()
    {
        $count = 0;
        foreach ($this->getDom()->find('form div.wrapper table[id] tr')->eq(0)->find('th') as $key => $th) {
            if(!isset($this->thFieldName[$key]))
                return false;

            $th = \phpQuery::pq($th);
            if(strtolower($th->text()) != strtolower($this->thFieldName[$key]))
                return false;

            $count++;
        }

        return $count == 14;
    }

    /**
     * @return BasketballParserStatistic
     */
    public function getParser()
    {
        $Parser = new BasketballParserStatistic();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}