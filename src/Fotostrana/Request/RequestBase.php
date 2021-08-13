<?php
namespace Fotostrana\Request;

use Fotostrana\Enums\EnumsConfig;
use Fotostrana\Enums\EnumsProtocol;

use Fotostrana\Model\ModelAuth;
use Fotostrana\Model\ModelError;

use Fotostrana\Model\ModelRequestResponse;

/**
 * Base class for api-requst forming
 */
class RequestBase
{

    private $mode= EnumsProtocol::HTTP_QUERY_GET;
    private $method;
    private $params = [];
    private $resultRaw;
    private $cache;
    private $cacheAllowed = true;
    private $oldCacheState = null;
    private $logDir  = '/log/';
    private $logFileName = 'requests.log';
    private $logFilePath;
    private $authParams;

    function __construct(ModelAuth $authParams)
    {
        $this->authParams = $authParams;
        $this->flushResult();
        $this->cache = new RequestCache();
        $this->logDir = dirname(__FILE__) . $this->logDir;

        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }

        if ($this->logDir) {
            $this->logFilePath = $this->logDir . $this->logFileName;
        }
    }

    /**
     * @param $method
     * @return $this
     */
    function setMethod($method)
    {
        $this->flushResult();
        $this->method=$method;
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    function setParam($name,$value)
    {
        if ($value) {
            $this->params[$name] = $value;
        }

        return $this;
    }

    /**
     * Now awailable GET | POST
     * @param $mode
     * @return $this
     */
    function setMode($mode)
    {
        $this->mode = strtoupper($mode)==EnumsProtocol::HTTP_QUERY_GET ? EnumsProtocol::HTTP_QUERY_GET : EnumsProtocol::HTTP_QUERY_POST;
        return $this;
    }

    /**
     * @param bool $toSet
     * @return $this
     */
    function setCache(bool $toSet)
    {
        $this->oldCacheState = $this->cacheAllowed;
        $this->cacheAllowed = $toSet;
        return $this;
    }

    /**
     * @return $this
     */
    function restoreCache()
    {
        if ($this->oldCacheState === null) {
            return $this;
        }
        $this->setCache($this->oldCacheState);
        $this->oldCacheState = null;
        return $this;
    }

    /**
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function sendRequest()
    {
        $this->runRequest();
        return new ModelRequestResponse($this->resultRaw);
    }

    private function runRequest()
    {
        $r = new SubRequest($this->authParams);
        $p = array_merge($this->params, array('method' => $this->method));

        if ($this->cacheAllowed && $cached_result = $this->cache->loadCache($p)) {
            $this->resultRaw = $cached_result;
            if ($this->logFilePath && EnumsConfig::FOTOSTRANA_REQUESTS_LOGGER_ENABLED) {
                file_put_contents($this->logFilePath, date('r') . ' cache: ' . $this->method . ' ' . serialize($this->params) . ' ' . serialize($cached_result) . "\n\n", FILE_APPEND);
            }
            return;
        }

        // await for requests lock, look at:
        // \Fotostrana\Request\RequestCounter::MAX_QUERIES
        // \Fotostrana\Request\RequestCounter::PER_TIME
        RequestCounter::addQuery();
        RequestCounter::wait();

        $url = $r->makeApiRequestUrl($p);

        if (EnumsConfig::FOTOSTRANA_DEBUG) {
            echo "Fetching URL " . htmlspecialchars($url) . " by " . $this->mode . "<br>\n";
        }

        if (strtoupper($this->mode) == EnumsProtocol::HTTP_QUERY_GET) {
            $this->resultRaw = file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $this->resultRaw = curl_exec($ch);
            curl_close($ch);
        }

        if ($this->logFilePath && EnumsConfig::FOTOSTRANA_REQUESTS_LOGGER_ENABLED) {
            file_put_contents($this->logFilePath, date('r') . ' request: ' . $this->method . ' ' . serialize($this->params) . ' ' . $this->resultRaw . "\n\n", FILE_APPEND);
        }

        if ($this->cacheAllowed) {
            $this->cache->storeCache($p, $this->resultRaw);
        }

        if (EnumsConfig::FOTOSTRANA_DEBUG) {
            var_dump($this->resultRaw);
        }

    }

    private function flushResult()
    {
        $this->method = false;
        $this->params = [];
        $this->resultRaw = false;
        $this->mode = EnumsProtocol::HTTP_QUERY_GET;
        $this->setCache(true);
    }

}