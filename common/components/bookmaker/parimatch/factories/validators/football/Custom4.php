<?php
namespace common\components\bookmaker\parimatch\factories\validators\football;

use common\components\bookmaker\parimatch\factories\validators\Base;
use common\components\bookmaker\parimatch\factories\parsers\football\Custom4 as FootballParserCustom4;

/**
 * Created by PhpStorm.
 */
class Custom4 extends Base
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
        '1X',
        '12',
        'X2',
    ];

    public function check()
    {
        $count = 0;
        foreach ($this->getDom()->find('form div.wrapper table[id] tr')->eq(0)->find('th') as $key => $th) {
            if(!isset($this->thFieldName[$key]))
                return false;

            $th = \phpQuery::pq($th);
            if(strtolower($th->text()) != strtolower($this->thFieldName[$key]) || (($key == 4 && $th->attr('colspan') === null)))
                return false;

            $count++;
        }

        return $count == 14;
    }

    /**
     * @return FootballParserCustom4
     */
    public function getParser()
    {
        $Parser = new FootballParserCustom4();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}