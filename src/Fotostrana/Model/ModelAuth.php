<?php


namespace Fotostrana\Model;


use Fotostrana\Enums\EnumsConfig;
use Fotostrana\Enums\EnumsProtocol;
use Fotostrana\Interfaces\IError;
use Fotostrana\Model\ModelError;

class ModelAuth implements IError
{
    /**
     * @var ModelAuth
     */

    private $sessionKey;
    private $viewerId;
    private $authKey;

    /**
     * @var ModelError
     */
    private $error;

    public function __construct()
    {
        $this->sessionKey = $_REQUEST[EnumsProtocol::SESSION_KEY] ?? '';
        $this->viewerId   = $_REQUEST[EnumsProtocol::VIEWER_ID] ?? '';
        $this->authKey    = $_REQUEST[EnumsProtocol::AUTH_KEY] ?? '';

        if (!EnumsConfig::FOTOSTRANA_AUTH_KEY_CHECK) {
            return;
        }

        if (!$this->sessionKey && !$this->viewerId && !$this->authKey) {
            return;
        }

        $ourAuth = md5(ModelCreds::appId() . '_' . $this->viewerId . '_' . ModelCreds::serverKey());
        if (($this->authKey === null || $this->authKey != $ourAuth)) {
            $this->error = new ModelError('002');
            return;
        }
    }

    /**
     * @return ModelError
     */
    public function error()
    {
        return $this->error;
    }
    /**
     * @return mixed
     */
    public function sessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * @return mixed
     */
    public function viewerId()
    {
        return $this->viewerId;
    }

}