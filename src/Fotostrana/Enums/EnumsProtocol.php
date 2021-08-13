<?php


namespace Fotostrana\Enums;


class EnumsProtocol
{
    const HTTP_QUERY_GET = 'GET',
        HTTP_QUERY_POST = 'POST';

    const USER_ID   = 'userId',
        MONEY       = 'money',
        USER_IDS    = 'userIds',
        FIELDS      = 'fields',
        TEXT        = 'text',
        PARAMS      = 'params',
        SESSION_KEY = 'sessionKey',
        AUTH_KEY    = 'authKey',
        VIEWER_ID   = 'viewerId',
        METHOD      = 'method',
        APP_ID      = 'appId',
        RAND        = 'rand',
        FORMAT      = 'format',
        TIMESTAMP   = 'timestamp',
        ERROR       = 'error',
        RESPONSE    = 'response';
}