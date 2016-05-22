<?php
namespace common\components\bookmaker\parimatch\factories\validators;
use common\components\bookmaker\parimatch\factories\validators\_interface\iValidator;

/**
 * Created by PhpStorm.
 */
abstract class Base implements iValidator
{
    /** @var null */
    public $html = null;

    /** @var \phpQueryObject */
    public $dom = null;

    /**
     * @return null
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param null $html
     *
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    public function populate($array)
    {
        foreach ($array as $field => $value) {
            if(!property_exists($this, $field)) {
                continue;
            }

            switch ($field) {
                case 'html':
                    $this->html = $value;
                    $this->dom = \phpQuery::newDocument($value);
                    break;
                default:
                    $this->{$field} = $value;
                    break;
            }
        }
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
     *
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }

    public function getTemplateName()
    {
        $reflect = new \ReflectionClass($this);
        return strtolower($reflect->getShortName());
    }
}