<?php
namespace Fotostrana\Model;

class ModelCreds
{
    private static $appId;
    private static $serverKey;
    private static $clientKey;

    public function __construct(
        string $appId,
        string $serverKey,
        string $clientKey
    )
    {
        self::$appId = $appId;
        self::$serverKey = $serverKey;
        self::$clientKey = $clientKey;
    }

    public static function appId()
    {
        return self::$appId;
    }

    public static function serverKey()
    {
        return self::$serverKey;
    }

    public static function clientKey()
    {
        return self::$clientKey;
    }
}