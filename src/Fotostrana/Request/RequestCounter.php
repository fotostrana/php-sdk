<?php
namespace Fotostrana\Request;


use Fotostrana\Enums\EnumsConfig;

/**
 * Support class for counting api-requests
 */
class RequestCounter
{
    static private $queries = [];

    const MAX_QUERIES = 20;
    const PER_TIME = 10;

    static function addQuery()
    {
        self::$queries[time()] = '';
    }

    public static function removeQuery($t)
    {
        unset (self::$queries[$t]);
    }

    public static function countQueries()
    {
        return count(self::$queries);
    }

    public static function agingQueries()
    {
        foreach (self::$queries as $q => $v) {
            if ($q < (time() - self::PER_TIME)) {
                unset(self::$queries[$q]);
            }
        }
    }

    public static function wait()
    {
        if (EnumsConfig::FOTOSTRANA_DEBUG) {
            echo ("Query timeout check: query count " . self::countQueries() . ", max queries " . self::MAX_QUERIES . " <br>\n");

        }

        while (self::countQueries() >= self::MAX_QUERIES) {
            if (EnumsConfig::FOTOSTRANA_DEBUG) {
                echo ("MAX_QUERIES reached (query count " . self::countQueries() . "), wait.. <br/>\n");
            }

            self::agingQueries();
            sleep(1);
        }
    }
}