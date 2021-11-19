<?php


namespace Fotostrana\Request;


use Fotostrana\Enums\EnumsConfig;

class RequestLog
{
    private static $logPath;

    static function setLogPath(string $logPath)
    {
        self::$logPath = $logPath;
    }

    static function toLog(string $method, array $params, $requestAnswer)
    {
        if (!self::$logPath) {
            return;
        }

        if (!EnumsConfig::FOTOSTRANA_REQUESTS_LOGGER_ENABLED) {
            return;
        }

        file_put_contents(
            self::$logPath,

            date('r') .
            ' cache: ' . $method .
            ' ' . serialize($params) .
            ' ' . serialize($requestAnswer) .
            "\n\n",

            FILE_APPEND)
        ;
    }

}