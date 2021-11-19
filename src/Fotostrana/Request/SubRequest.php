<?php
namespace Fotostrana\Request;

use Fotostrana\Enums\EnumsConfig;
use Fotostrana\Enums\EnumsProtocol;

use Fotostrana\Model\ModelAuth;
use Fotostrana\Model\ModelCreds;

/**
 * This class will generate URL and sig for api request
 */
class SubRequest
{
    private $server_methods = array(
        'User.sendNotification',
        'User.sendAppEmail',
        'User.giveAchievment',

        'Userphoto.checkAccess',

        'Billing.getUserBalanceAny',
        'Billing.withDrawMoneySafe',

    );

    /** @var ModelAuth */
    private $authParams;

    public function __construct(ModelAuth $authParams)
    {
        $this->authParams = $authParams;
    }

    private function makeSig(array $params) {
        ksort($params);
        $p_string = '';
        if (!in_array($params['method'], $this->server_methods)) {
            $p_string = $this->authParams->viewerId();
        }

        foreach ($params as $k => $v)
        {
            if ($k && $v) {
                $p_string .= is_array($v) ? str_replace('&', '', urldecode(http_build_query([$k => $v])))
                                              : $k . '=' . $v;
            }
        }

        $p_string .= in_array($params['method'], $this->server_methods) ? ModelCreds::serverKey()
                                                                                      : ModelCreds::clientKey();

        if (EnumsConfig::FOTOSTRANA_DEBUG) {
            echo "p_string: " . $p_string . "<br/><br/>\n";
        }

        return  md5($p_string);
    }

    function urlencodeArray($params)
    {
        $res = array();
        foreach ($params as $key => $value) {
            $res[$key] = is_array($value) ? $this->urlencodeArray($value)
                                              : urlencode($value);
        }
        return $res;
    }

    /**
     * @param array $params
     * @param string $mode EnumsProtocol::HTTP_QUERY_*
     * @return array [string api.url, array params for request]
     */
    function prepareApiRequest(array $params, string $mode)
    {
        switch ($mode)
        {
            case EnumsProtocol::HTTP_QUERY_POST:
                return $this->preparePOST($params);
            default:
                return $this->prepareGET($params);
        }
    }

    private function preparePOST(array $params)
    {
        $paramsForSig = [
            EnumsProtocol::APP_ID => ModelCreds::appId(),
            EnumsProtocol::TIMESTAMP => time(),
            EnumsProtocol::FORMAT => 1,
            EnumsProtocol::RAND => rand(1,999999),
            EnumsProtocol::SESSION_KEY => $this->authParams->sessionKey(),
            EnumsProtocol::VIEWER_ID => $this->authParams->viewerId(),
            EnumsProtocol::METHOD => $params[EnumsProtocol::METHOD],
        ];

        ksort($paramsForSig);
        $sig = $this->makeSig($paramsForSig);
        $e_params = $this->urlencodeArray($paramsForSig);
        $url = EnumsConfig::FOTOSTRANA_API_BASEURL . '?' . EnumsProtocol::SIG . '=' . $sig;

        foreach ($e_params as $k => $v) {
            if ($k && $v) {
                $url .= is_array($v) ? '&' . urldecode(http_build_query(array($k => $v)))
                                         : '&' . $k . '=' . $v;
            }
        }

        return [$url, $params];
    }

    private function prepareGET(array $params)
    {
        if (!array_key_exists(EnumsProtocol::APP_ID, $params)) {
            $params[EnumsProtocol::APP_ID] = ModelCreds::appId();
        }

        if (!array_key_exists(EnumsProtocol::TIMESTAMP, $params)) {
            $params[EnumsProtocol::TIMESTAMP] = time();
        }

        if (!array_key_exists(EnumsProtocol::FORMAT, $params)) {
            $params[EnumsProtocol::FORMAT] = 1;
        }

        if (!array_key_exists(EnumsProtocol::RAND, $params)) {
            $params[EnumsProtocol::RAND] = rand(1,999999);
        }

        if (!in_array($params[EnumsProtocol::METHOD], $this->server_methods)) {
            $params[EnumsProtocol::SESSION_KEY] = $this->authParams->sessionKey();
            $params[EnumsProtocol::VIEWER_ID] = $this->authParams->viewerId();
        }

        ksort($params);
        $sig = $this->makeSig($params);
        $e_params = $this->urlencodeArray($params);
        $url = EnumsConfig::FOTOSTRANA_API_BASEURL . '?' . EnumsProtocol::SIG . '=' . $sig;

        foreach ($e_params as $k => $v) {
            if ($k && $v) {
                $url .= is_array($v) ? '&' . urldecode(http_build_query(array($k => $v)))
                                         : '&' . $k . '=' . $v;
            }
        }

        if (EnumsConfig::FOTOSTRANA_DEBUG) {
            echo "URL: " . htmlspecialchars($url) . "<br/><br/>\n";
        }

        return [$url, $e_params];
    }

}