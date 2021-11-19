<?php
namespace Fotostrana\Request;

use Fotostrana\Enums\EnumsConfig;

class RequestCache
{
    private static $cachePath;

    static function setCachePath(string $path)
    {
        self::$cachePath = $path;
    }

    static function storeCache($params, $data)
    {
        if (!self::$cachePath) {
            return;
        }

        if (!$params) {
            return;
        }

        file_put_contents(
            self::$cachePath . self::makeCacheKey($params),
            self::encryptData($data)
        );
    }

    /**
     * @param $params
     * @return mixed|null
     */
    static function loadCache($params)
    {
        if (!$params) {
            return null;
        }

        $f = self::$cachePath . self::makeCacheKey($params);
        if (!file_exists($f)) {
            return null;
        }

        if (filemtime($f) < (time() - EnumsConfig::FOTOSTRANA_REQUESTS_CACHE_TIMEOUT)) {
            @unlink($f);
            return null;
        }

        return self::decryptData(file_get_contents($f));
    }

    /**
     * @param $params
     * @return string
     */
    private static function makeCacheKey($params)
    {
        if (!$params) {
            return '';
        }

        unset($params['timestamp']);
        unset($params['rand']);
        return md5(serialize($params));
    }

    private static function encryptData($data)
    {
        return serialize($data);
    }
    private static function decryptData($data)
    {
        return unserialize($data);
    }

}