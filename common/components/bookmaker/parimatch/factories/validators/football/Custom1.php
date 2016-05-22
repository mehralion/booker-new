<?php
namespace common\components\bookmaker\parimatch\factories\validators\football;
use common\components\bookmaker\parimatch\factories\validators\Base;
use common\components\bookmaker\parimatch\factories\parsers\football\Custom1 as FootballParserCustom1;

/**
 * Created by PhpStorm.
 */
class Custom1 extends Base
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
            if($th->text() != $this->thFieldName[$key] || $th->attr('colspan') !== null)
                return false;

            $count++;
        }

        return $count == 14;
    }

    /**
     * @return FootballParserCustom1
     */
    public function getParser()
    {
        $Parser = new FootballParserCustom1();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}