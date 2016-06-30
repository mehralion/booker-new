<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 28.06.2016
 */

namespace common\components\bookmaker;


use common\components\bookmaker\_interface\iClient;

class Connector
{
    protected $host;
    protected $proxy;
    protected $delay;
    protected $referer;

    /** @var iClient */
    protected $client;

    protected $is_connect = false;
    
    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param mixed $proxy
     *
     * @return $this
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param mixed $delay
     *
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    public function getOptions()
    {
        return [
            'delay' => $this->delay,
            'proxy' => $this->proxy,
        ];
    }

    /**
     * @return boolean
     */
    public function isConnect()
    {
        return $this->is_connect;
    }

    /**
     * @param boolean $is_connect
     *
     * @return $this
     */
    public function setIsConnect($is_connect)
    {
        $this->is_connect = $is_connect;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param mixed $referer
     *
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * @return iClient
     */
    public function getClient()
    {
        $options = [
            'delay'     => $this->delay,
            'proxy'     => $this->proxy,
            'referer'   => $this->referer,
        ];

        $this->client->clearOptions()
            ->setOptions($options);

        return $this->client;
    }

    /**
     * @param iClient $client
     *
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }
}