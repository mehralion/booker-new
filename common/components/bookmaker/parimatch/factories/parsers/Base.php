<?php
namespace common\components\bookmaker\parimatch\factories\parsers;
use common\components\bookmaker\parimatch\factories\parsers\_interface\iParser;
use Doctrine\Common\Collections\ArrayCollection;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 */
abstract class Base implements iParser
{
    /** @var null */
    public $html = null;

    /** @var \phpQueryObject */
    public $dom = null;

    /** @var ArrayCollection */
    protected $events = [];

    /**
     * @return array
     */
    abstract  public function getTdMapping();

    /**
     * @return array
     */
    abstract protected function getRatioField();

    /**
     * @return array
     */
    abstract protected function getPlaceholder();

    /**
     * @param boolean|string $html
     */
    public function __construct($html = false)
    {
        if($html !== false)
            $this->setHtml($html)
                ->setDom(\phpQuery::newDocument($html));
    }

    /**
     * @return \phpQueryObject
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param \phpQueryObject $dom
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }

    /**
     * @return null
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param null $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     * @return $this
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    protected function prepareMethod($field)
    {
        $method = 'get';
        foreach (explode('_', $field) as $item)
            $method .= ucfirst($item);

        return $method;
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getNumber($td)
    {
        return ['number' => trim($td->text())];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     * @throws \Exception
     */
    protected function getDate($td)
    {
        if(!preg_match('/(\d{2})\/(\d{2})(\d{2}\:\d{2})/ui', $td->text(), $out))
            throw new \Exception('Неудалось найти дату у события');
        $date = sprintf('%s.%s.%s %s:00', $out[1], $out[2], date('Y'), $out[3]);
        $date_int = strtotime(sprintf('%s-%s-%s %s:00', date('Y'), $out[2], $out[1], $out[3]));

        return ['date_string' => $date, 'date_int' => $date_int];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     * @throws \Exception
     */
    protected function getTeams($td)
    {
        $span = $td->find('span.n');
        if($span->count()) {
            $span->remove();
        }

        $span = $td->find('span.tr');
        if($span->count()) {
            $span->remove();
        }

        $a = $td->find('a');
        if($a->count() == 2) {
            $a->eq(0)->remove();
        }
        $html = $td->html();

        $teams = [];
        foreach (explode('<br>', $html) as $key => $team)
            $teams[$key + 1] = trim(strip_tags($team));

        if(count($teams) != 2 || empty($teams[1]) || empty($teams[2]))
            throw new \Exception('Неудалось найти команды у события');

        return ['team_1' => $teams[1], 'team_2' => $teams[2]];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getForaVal($td)
    {
        $elements = $td->find('b');
        if($elements->count() == 2) {
            return [
                'fora_val_1' => $elements->eq(0)->text(),
                'fora_val_2' => $elements->eq(1)->text(),
            ];
        }

        return [];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getForaRatio($td)
    {
        $elements = $td->find('u');
        if($elements->count() == 2) {
            return [
                'fora_ratio_1' => $elements->eq(0)->find('a')->text(),
                'fora_ratio_2' => $elements->eq(1)->find('a')->text(),
            ];
        }

        return [];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getTotalVal($td)
    {
        return ['total_val' => $td->find('b')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getTotalMore($td)
    {
        return ['total_more' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getTotalLess($td)
    {
        return ['total_less' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatioP1($td)
    {
        return ['ratio_p1' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatioP2($td)
    {
        return ['ratio_p2' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getItotalVal($td)
    {
        $elements = $td->find('b');
        if($elements->count() == 2) {
            return [
                'itotal_val_1' => $elements->eq(0)->text(),
                'itotal_val_2' => $elements->eq(1)->text(),
            ];
        }

        return [];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getItotalMore($td)
    {
        $elements = $td->find('u');
        if($elements->count() == 2) {
            return [
                'itotal_more_1' => $elements->eq(0)->find('a')->text(),
                'itotal_more_2' => $elements->eq(1)->find('a')->text(),
            ];
        }

        return [];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getItotalLess($td)
    {
        $elements = $td->find('u');
        if($elements->count() == 2) {
            return [
                'itotal_less_1' => $elements->eq(0)->find('a')->text(),
                'itotal_less_2' => $elements->eq(1)->find('a')->text(),
            ];
        }

        return [];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatioX($td)
    {
        return ['ratio_x' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatio1x($td)
    {
        return ['ratio_1x' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatio12($td)
    {
        return ['ratio_12' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatioX2($td)
    {
        return ['ratio_x2' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatio20($td)
    {
        return ['ratio_20' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatio21($td)
    {
        return ['ratio_21' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatio02($td)
    {
        return ['ratio_02' => $td->find('u a')->text()];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getRatioPlus15($td)
    {
        $elements = $td->find('u');
        if($elements->count() == 2) {
            return [
                'ratio_plus15_1' => $elements->eq(0)->find('a')->text(),
                'ratio_plus15_2' => $elements->eq(1)->find('a')->text(),
            ];
        }

        return [];
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function run()
    {
        $events = new ArrayCollection();

        $tdMapping = $this->getTdMapping();
        /** @var \phpQueryObject $trList */
        $trList = $this->getDom()->find('form div.wrapper table tbody:not([id]) > tr.bk');
        foreach ($trList as $key => $tr) {
            $data = $this->getPlaceholder();
            $tr = \phpQuery::pq($tr);
            reset($tdMapping);
            $index = -1;
            while(($current = each($tdMapping)) !== false) {
                $index++;
                if($current['value'] === false) continue;
                $td = $tr->find('td')->eq($index);

                if(($count = $td->attr('colspan')) !== null) {
                    $this->nextArray((int)$count-1, $tdMapping);
                    continue;
                }

                $method = $this->prepareMethod($current['value']);
                if(method_exists($this, $method)) {
                    try {
                        $data =  ArrayHelper::merge($data, call_user_func_array([$this, $method], [$td]));
                    } catch (\Exception $ex) {
                        continue 2;
                    }
                }
            }
            foreach ($data as $_n => $_v) {
                $data[$_n] = trim(strip_tags($_v));
            }

            $data = $this->prepareRatioList($data);
            if(!$events->contains($data)) {
                $events->add($data);
            }
        }

        $this->setEvents($events);
        return $this;
    }

    protected function prepareRatioList($data)
    {
        $interest = array_intersect_key($data, array_fill_keys($this->getRatioField(), null));
        foreach ($interest as $key => $value) {
            unset($data[$key]);
        }

        $data['ratio_list'] = $interest;

        return $data;
    }

    protected function nextArray($next_count, &$tdMapping)
    {
        for($i = 0; $i < $next_count; $i++) {
            next($tdMapping);
        }
    }

}