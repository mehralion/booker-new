<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components;


use common\models\Proxy;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
use yii\base\Component;
use JonnyW\PhantomJs\Client;

class Phantom extends Component
{
    /** @var Client */
    private $_client = null;
    protected $proxy_list = [];
    protected $cookieFile = null;
    protected $defaultOptions = [];
    protected $referer = null;

    public $useProxy;

    public $isDelay = false;
    public $delay;
    public $pathToConfig;
    public $pathToPhantomJS;

    public function init()
    {
        /** @var ServiceContainer $serviceContainer */
        $serviceContainer = ServiceContainer::getInstance();
        $procedureLoader = $serviceContainer->get('procedure_loader_factory')
            ->createProcedureLoader(\Yii::getAlias('@localVendor').'/phantomjs/partial');

        $this->_client = Client::getInstance();
        $this->_client->getProcedureLoader()->addLoader($procedureLoader);

        $this->populateProxyList();

        $this->cookieFile = sprintf('%s/cookie/cookie.txt', ROOT_DIR);
        if($this->pathToConfig !== null) {
            $this->_client->getEngine()->addOption(sprintf('--config=%s', $this->pathToConfig));
        }

        $this->_client->getEngine()->setPath($this->pathToPhantomJS);
        $this->defaultOptions = $this->_client->getEngine()->getOptions();
    }

    /**
     * @param $referer
     * @return $this
     */
    public function referer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * @param bool $useProxy
     * @return $this
     */
    public function useProxy($useProxy = true)
    {
        $this->useProxy = $useProxy;
        return $this;
    }

    /**
     * @param int $delay
     * @return $this
     */
    public function delay($delay = 0)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return string
     */
    protected function getProxy()
    {
        return $this->proxy_list[array_rand($this->proxy_list)];
    }

    /**
     * @return $this
     */
    protected function populateProxyList()
    {
        $this->proxy_list = [];
        $List = Proxy::find()
            ->active()
            ->select(['ip', 'port'])
            ->asArray()
            ->all();
        foreach ($List as $_item) {
            $this->proxy_list[] = sprintf('%s:%s', $_item['ip'], $_item['port']);
        }

        return $this;
    }

    protected function prepareOptions()
    {
        $this->_client->getEngine()->setOptions($this->defaultOptions);
        /*if($this->useProxy && ($proxy = $this->getProxy())) {
            $this->cookieFile = sprintf('%s/cookie/%s.txt', ROOT_DIR, $proxy);
            $this->_client->getEngine()->addOption(sprintf('--proxy=%s', $proxy));
        }*/
        $this->_client->getEngine()->addOption(sprintf('--proxy=%s', '192.168.33.1:8888'));

        if($this->cookieFile) {
            $this->_client->getEngine()->addOption(sprintf('--cookies-file=%s', $this->cookieFile));
        }

        return $this;
    }

    public function get($link, $isDelay = false)
    {
        $response = $this->request($link);
        if($isDelay === true && preg_match('/challenge-form/ui', $response)) {
            $this->isDelay = true;
            $response = $this->request($link);
        }
        
        return $response;
    }

    public function post($link, $data)
    {
        return $this->request($link, $data, 'POST');
    }

    /**
     * @param $link
     * @param array $data
     * @param string $request
     * @return string
     */
    protected function request($link, $data = [], $request = 'GET')
    {
        $this->prepareOptions();
        /**
         * @see JonnyW\PhantomJs\Message\Request
         **/
        $request = $this->_client->getMessageFactory()->createRequest($link, $request);
        if($this->isDelay) {
            //$request->setDelay($this->delay);
        }
        //$request->setTimeout(10);
        if($data) {
            $request->setRequestData($data);
        }
        $request->addHeaders(array(
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36',
            'Accept-charset' => 'utf-8',
            'Accept-Encoding' => 'gzip, deflate',
        ));
        if($this->referer) {
            $request->addHeader('Referer', $this->referer);
        }

        /**
         * @see JonnyW\PhantomJs\Message\Response
         **/
        $response = $this->_client->getMessageFactory()->createResponse();

        // Send the request
        $this->_client->send($request, $response);
        $content = $response->getContent();

        return $content;
    }
}