<?php


namespace Fotostrana\Model;

use Fotostrana\Enums\EnumsProtocol;
use Fotostrana\Interfaces\IError;

class ModelRequestResponse implements IError
{
    private $error;
    private $data;

    public function __construct(string $dataString)
    {
        try {

            if (!$dataString) throw new ModelError('API Request error', 'Empty API response');

            if (!$decodedData = json_decode($dataString, true)) {
                throw new ModelError('API Request error', 'Invalid API response: decode error');
            }

            if ($decodedData[EnumsProtocol::ERROR] ?? null) {
                throw new ModelError('API Request error', 'Error: ' . (isset($this->error['error_subcode']) ? $this->error['error_subcode'] . ' (subcode)' : $this->error['error_code'] . ' (code)') . ': ' . $this->error['error_msg']);
            }

            if (!$this->data = $decodedData[EnumsProtocol::RESPONSE] ?? null) {
                throw new ModelError('API Request error', 'Invalid API response: no response field');
            }

        } catch (ModelError $error) {
            $this->error = $error;
        }

    }

    public function setError(ModelError $error)
    {
        $this->error = $error;
        return $this;
    }

    public function error()
    {
        return $this->error;
    }

    public function data()
    {
        return $this->data;
    }
}