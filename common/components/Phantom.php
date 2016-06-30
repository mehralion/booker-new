<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components;


use Campo\UserAgent;
use common\components\bookmaker\_interface\iClient;
use common\models\ProxyCaptcha;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
use yii\base\Component;
use JonnyW\PhantomJs\Client;
use yii\helpers\ArrayHelper;

class Phantom extends Component implements iClient
{
    /** @var Client */
    private $_client = null;
    protected $defaultClientOptions = [];

    public $pathToConfig;
    public $pathToPhantomJS;

    private $isDelay = false;

    protected $defaultOptions = [
        'delay'     => 0,
        'proxy'     => null,
        'referer'   => null,
        'cookie'    => null,
    ];
    protected $options = [];

    public function init()
    {
        /** @var ServiceContainer $serviceContainer */
        $serviceContainer = ServiceContainer::getInstance();
        $procedureLoader = $serviceContainer->get('procedure_loader_factory')
            ->createProcedureLoader(\Yii::getAlias('@localVendor').'/phantomjs/partial');

        $this->_client = Client::getInstance();
        $this->_client->getProcedureLoader()->addLoader($procedureLoader);

        if($this->pathToConfig !== null) {
            $this->_client->getEngine()->addOption(sprintf('--config=%s', $this->pathToConfig));
        }

        $this->_client->getEngine()->setPath($this->pathToPhantomJS);
        $this->defaultClientOptions = $this->_client->getEngine()->getOptions();
    }

    /**
     * @param $proxy
     * @return $this
     */
    public function proxy($proxy)
    {
        $this->options['proxy'] = $proxy;
        return $this;
    }

    /**
     * @param $delay
     * @return $this
     */
    public function delay($delay)
    {
        $this->options['delay'] = $delay;
        return $this;
    }

    /**
     * @param $referer
     * @return $this
     */
    public function referer($referer)
    {
        $this->options['referer'] = $referer;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = [];
        return $this;
    }

    /**
     * @param $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function get($link, $repeat = false)
    {
        var_dump($link);
        $response = $this->request($link);
        if(preg_match('/Why do I have to complete a CAPTCHA/ui', $response)) {
            if(isset($this->options['proxy'])) {
                $proxy = explode(':', $this->options['proxy']);
                $ProxyCaptcha = ProxyCaptcha::find()
                    ->alias('t')
                    ->andWhere('`t`.ip = :ip and `t`.port = :port and `t`.link = :link',
                        [
                            ':ip'   => $proxy[0],
                            ':port' => $proxy[1],
                            ':link' => $link
                        ])
                    ->count();
                if(!$ProxyCaptcha) {
                    $ProxyCaptcha = new ProxyCaptcha();
                    $ProxyCaptcha->ip = $proxy[0];
                    $ProxyCaptcha->port = $proxy[1];
                    $ProxyCaptcha->link = $link;
                    $ProxyCaptcha->save();
                }
            }

            var_dump('CAPTCHA');
            return null;
        }

        var_dump($response);
        if($repeat === false && preg_match('/challenge-form/ui', $response)) {
            $this->isDelay = true;
            $response = $this->get($link, true);
            $this->isDelay = false;
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
        $options = ArrayHelper::merge($this->defaultOptions, $this->options);
        $this->_client->getEngine()->setOptions($this->defaultClientOptions);
        if($options['proxy']) {
            $options['cookie'] = sprintf('%s/cookie/%s.txt', ROOT_DIR, str_replace(['.', ':'], '_', $options['proxy']));
            $this->_client->getEngine()->addOption(sprintf('--proxy=%s', $options['proxy']));
        }

        if($options['cookie']) {
            $this->_client->getEngine()->addOption(sprintf('--cookies-file=%s', $options['cookie']));
        }
        /**
         * @see JonnyW\PhantomJs\Message\Request
         **/
        $request = $this->_client->getMessageFactory()->createRequest($link, $request);
        if($this->isDelay) {
            $request->setDelay($options['delay']);
        }

        if($data) {
            $request->setRequestData($data);
        }
        $request->addHeaders(array(
            'User-Agent'        => UserAgent::random(),
            'Accept-charset'    => 'utf-8',
            //'Accept-Encoding'   => 'gzip, deflate',
        ));
        if($options['referer']) {
            $request->addHeader('Referer', $options['referer']);
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