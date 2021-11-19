<?php


namespace Fotostrana\Enums;


class EnumsConfig
{
    const FOTOSTRANA_API_BASEURL = 'http://www.fotostrana.ru/apifs.php';

    // diff debugging params
    const FOTOSTRANA_DEBUG = 0;
    const FOTOSTRANA_REQUESTS_CACHE_TIMEOUT = 30;
    const FOTOSTRANA_REQUESTS_LOGGER_ENABLED = true;

    // should be true for current work on production SDK
    const FOTOSTRANA_AUTH_KEY_CHECK = true;

    // bits mask appSettings
    const FOTOSTRANA_MASK_DEFAULT = 1;
    const FOTOSTRANA_MASK_USERWALL = 2;
    const FOTOSTRANA_MASK_USERCOMMUNITIES = 4;
    const FOTOSTRANA_MASK_USERFORUM = 8;
    const FOTOSTRANA_MASK_USERINVITE = 16;
    const FOTOSTRANA_MASK_USERNOTIFY = 32;
    const FOTOSTRANA_MASK_SILENT_BILLING = 64;
    const FOTOSTRANA_MASK_USERPHOTO = 128;
    const FOTOSTRANA_MASK_USEREMAIL = 512;
}