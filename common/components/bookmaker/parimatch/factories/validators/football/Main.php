<?php
namespace common\components\bookmaker\parimatch\factories\validators\football;

use common\components\bookmaker\parimatch\factories\validators\Base;
use common\components\bookmaker\parimatch\factories\parsers\football\Main as FootballParserMain;

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
        '1X',
        '12',
        'X2',
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
            if($th->text() != $this->thFieldName[$key] || (($key == 4 && $th->attr('colspan') === null)))
                return false;

            $count++;
        }

        return $count == 17;
    }

    /**
     * @return FootballParserMain
     */
    public function getParser()
    {
        $Parser = new FootballParserMain();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}