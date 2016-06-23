<?php
namespace console\controllers;
use common\factories\problem\event\FactoryProblemEvent;
use common\helpers\SportHelper;
use common\models\BookmakerProxy;
use common\models\event\Event;
use common\models\EventBookmakerVersion;
use common\models\EventOdds;
use common\models\EventProblem;
use common\models\Proxy;
use common\models\ProxySettings;
use common\models\sport\Sport;
use common\models\SportAlias;
use GuzzleHttp\Client;
use Jobby\Jobby;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Json;

/**
 * Site controller
 */
class ProxyController extends Controller
{
    public function actionJobs()
    {
        $jobby = new Jobby();
        $ProxySources = ProxySettings::find()
            ->active()
            ->all();
        foreach ($ProxySources as $Proxy) {
            $adapter = str_replace(' ', '', ucwords(str_replace('_', ' ', $Proxy->adapter)));

            $command = sprintf('/usr/bin/php %s/console/yii proxy %s', ROOT_DIR, $adapter);

            $jobby->add(sprintf('Proxy_%s', $adapter), array(
                'command' => $command,
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/update_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yii proxy check --adapter=%s', $Proxy->adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));
        }

        $jobby->run();
    }

    public function actionCheck($adapter)
    {
        try {
            $ProxyList = Proxy::find()
                ->select(['id', 'ip', 'port', 'attemt'])
                ->andWhere('source = :source', [':source' => $adapter])
                ->andWhere('attemt < 25')
                ->asArray()
                ->all();
            if(!$ProxyList) {
                $this->log('Proxy %s is empty', $adapter);
                return;
            }

            foreach ($ProxyList as $Proxy) {
                $Bookmakers = \Yii::$app->bookmaker->getList();
                foreach ($Bookmakers as $Bookmaker) {
                    if($Bookmaker->isProxyUse() === false) {
                        continue;
                    }

                    $BookmakerProxy = BookmakerProxy::find()
                        ->andWhere('bookmaker_id = :bookmaker_id', [':bookmaker_id' => $Bookmaker->getId()])
                        ->andWhere('proxy_id = :proxy_id', [':proxy_id' => $Proxy['id']])
                        ->one();

                    $proxy = sprintf('%s:%s', $Proxy['ip'], $Proxy['port']);
                    if($Bookmaker->connect($proxy) === false) {
                        if($BookmakerProxy) {
                            $BookmakerProxy->delete();
                        }
                        Proxy::updateAll([
                            'attemt' => $Proxy['attemt'] + 1,
                            'updated_at' => time()
                        ], 'id = :id', $Proxy['id']);

                        continue;
                    }

                    if(!$BookmakerProxy) {
                        $BookmakerProxy = new BookmakerProxy();
                        $BookmakerProxy->bookmaker_id = $Bookmaker->getId();
                        $BookmakerProxy->proxy_id = $Proxy['id'];
                    }
                    $BookmakerProxy->save();

                    Proxy::updateAll([
                        'attemt' => 0,
                        'updated_at' => time()
                    ], 'id = :id', $Proxy['id']);
                }
            }
        } catch (\Exception $ex) {
            $this->log($ex->getMessage());
        }
    }

    public function actionBestProxies()
    {
        try {
            $ProxySource = ProxySettings::find()
                ->andWhere('adapter = :adapter', [':adapter' => 'best_proxies'])
                ->one();

            $client = new Client();
            $response = $client->request('GET', $ProxySource->link);

            $content = $response->getBody()->getContents();
            if(!$content) {
                $this->log('ProxySource %s is empty', $ProxySource->adapter);
                return;
            }

            $allready = [];
            $ProxyList = Proxy::find()
                ->select('id, ip, port')
                ->andWhere('source = :source', [':source' => $ProxySource->adapter])
                ->asArray()
                ->all();
            foreach ($ProxyList as $Proxy) {
                $allready[] = sprintf('%s:%s', $Proxy['ip'], $Proxy['port']);
            }

            $rows = explode("\n", $content);
            foreach ($rows as $row) {
                $proxy = explode(':', $row);
                $ip = trim($proxy[0], " \n");
                $port = (int)trim($proxy[1], " \n");
                $keyPair = sprintf('%s:%s', $ip, $port);
                if(in_array($keyPair, $allready)) {
                    continue;
                }

                $model = new Proxy();
                $model->ip = $ip;
                $model->port = $port;
                $model->source = $ProxySource->adapter;
                $model->is_enabled = false;
                $model->save();

                $allready[] = $keyPair;
            }
        } catch (\Exception $ex) {
            $this->log($ex->getMessage());
        }
    }

    public function actionHideMe()
    {
        try {
            $ProxySource = ProxySettings::find()
                ->andWhere('adapter = :adapter', [':adapter' => 'hide_me'])
                ->one();

            $client = new Client();
            $response = $client->request('GET', $ProxySource->link);

            $content = $response->getBody()->getContents();
            if(!$content) {
                $this->log('Proxy %s is empty', $ProxySource->adapter);
                return;
            }

            $allready = [];
            $ProxyList = Proxy::find()
                ->select('id, ip, port')
                ->andWhere('source = :source', [':source' => $ProxySource->adapter])
                ->asArray()
                ->all();
            foreach ($ProxyList as $Proxy) {
                $allready[] = sprintf('%s:%s', $Proxy['ip'], $Proxy['port']);
            }

            $rows = Json::decode($content);
            foreach ($rows as $row) {
                $ip = $row['ip'];
                $port = $row['port'];
                $keyPair = sprintf('%s:%s', $ip, $port);
                if(in_array($keyPair, $allready)) {
                    continue;
                }

                $model = new Proxy();
                $model->ip = $ip;
                $model->port = $port;
                $model->delay = $row['delay'];
                $model->country_code = $row['country_code'];
                $model->country_name = $row['country_name'];
                $model->source = $ProxySource->adapter;
                $model->is_enabled = false;
                $model->save();

                $allready[] = $keyPair;
            }
        } catch (\Exception $ex) {
            $this->log($ex->getMessage());
        }
    }

    protected function log($string)
    {
        $args = func_get_args();
        array_shift($args);

        $this->stdout(vsprintf($string, $args)."\n", Console::FG_RED);
    }
}
