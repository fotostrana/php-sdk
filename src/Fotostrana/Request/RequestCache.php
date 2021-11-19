<?php
namespace Fotostrana\Request;

use Fotostrana\Enums\EnumsConfig;

class RequestCache
{
    private $cacheDir;

    function __construct()
    {
        $this->setCacheDir(dirname(__FILE__) . '/cache/');
    }

    function setCacheDir(string $path)
    {
        $this->cacheDir = $path;
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    function storeCache($params, $data)
    {
        if ($params) {
            file_put_contents($this->cacheDir . $this->makeCacheKey($params), $this->encryptData($data));
            chmod($this->cacheDir . $this->makeCacheKey($params), 0777);
        }
    }

    /**
     * @param $params
     * @return mixed|null
     */
    function loadCache($params)
    {
        if (!$params) {
            return null;
        }

        $f = $this->cacheDir . $this->makeCacheKey($params);
        if (!file_exists($f)) {
            return null;
        }

        if (filemtime($f) < (time() - EnumsConfig::FOTOSTRANA_REQUESTS_CACHE_TIMEOUT)) {
            @unlink($f);
            return null;
        }

        return $this->decryptData(file_get_contents($f));
    }

    /**
     * @param $params
     * @return string
     */
    private function makeCacheKey($params)
    {
        if (!$params) {
            return '';
        }

        unset($params['timestamp']);
        unset($params['rand']);
        return md5(serialize($params));
    }

    private function encryptData($data)
    {
        return serialize($data);
    }
    private function decryptData($data)
    {
        return unserialize($data);
    }

}