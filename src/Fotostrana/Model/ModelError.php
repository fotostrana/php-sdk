<?php
namespace Fotostrana\Model;

class ModelError extends \Exception
{
    private $errorCode;
    private $errorTexts = array(
        '001' => 'Check your configuration: you must disable safe_mode and enable allow_url_fopen in php.ini, and install CURL extension to PHP.',
        '002' => 'Incoming request authorization failed.',
        '003' => 'API object is not loaded.',
    );

    /**
     * FotostranaError constructor.
     * @param $errorCode
     * @param null $errorMessage
     */
    public function __construct($errorCode, $errorMessage = null)
    {
        $this->errorCode = $errorCode;
        if ($errorMessage !== null) {
            $this->errorTexts[ (string) $this->errorCode] = $errorMessage;
        }
        $this->message = $this->errorTexts[$this->errorCode];
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorTexts[(string)$this->errorCode];
    }
}
