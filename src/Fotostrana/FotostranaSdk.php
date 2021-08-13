<?php
namespace Fotostrana;

// Different
use Fotostrana\Interfaces\IError;
use Fotostrana\Request\RequestBase;

// Services
use Fotostrana\Service\ServiceUser;
use Fotostrana\Service\ServiceBilling;

// Models
use Fotostrana\Model\ModelError;
use Fotostrana\Model\ModelAuth;

/**
 * Main class Fotostrana SDK
 */
class FotostranaSdk implements IError
{
    private $cache = [];
    private $error = null;

    /** @var ModelAuth */
    private $authParams;

    public function __construct()
    {
        $this->selfTest();
        $this->checkAuth();
        $this->flushCache();
    }

    /**
     * @return ModelError|null
     */
    public function error()
    {
        return $this->error;
    }
    /**
     * @return ServiceUser
     */
    public function getServiceUser()
    {
        if ($this->error) {
            return null;
        }

        if (empty($this->cache[ServiceUser::class])) {
            $this->cache[ServiceUser::class] = new ServiceUser(new RequestBase($this->authParams));
        }

        return $this->cache[ServiceUser::class];
    }

    public function getServiceBilling()
    {
        if ($this->error) {
            return null;
        }

        if (empty($this->cache[ServiceBilling::class])) {
            $this->cache[ServiceBilling::class] = new ServiceBilling(new RequestBase($this->authParams));
        }

        return $this->cache[ServiceBilling::class];
    }

    public function flushCache()
    {
        $this->cache = [];
    }

    private function selfTest()
    {
        //  file_get_contents & CURL should be allowed

        if (
            !ini_get('allow_url_fopen')
            || ini_get('safe_mode')
            || !in_array('curl', get_loaded_extensions())
        ) {
            $this->error = new ModelError('001');
            return;
        }
    }


    private function checkAuth()
    {
        $this->authParams = new ModelAuth();
        $this->error = $this->authParams->error();
    }

}
