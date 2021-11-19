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
                throw new ModelError('API Request error', 'Error: ' . (isset($decodedData[EnumsProtocol::ERROR]['error_subcode']) ? $decodedData[EnumsProtocol::ERROR]['error_subcode'] . ' (subcode)' : $decodedData[EnumsProtocol::ERROR]['error_code'] . ' (code)') . ': ' . $decodedData[EnumsProtocol::ERROR]['error_msg']);
            }

            $this->data = $decodedData[EnumsProtocol::RESPONSE] ?? null;
            if ($this->data === null) {
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