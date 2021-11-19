<?php
namespace Fotostrana\Service;

use Fotostrana\Model\ModelError;
use Fotostrana\Enums\EnumsProtocol;
use Fotostrana\Model\ModelRequestResponse;

class ServiceBilling extends ServiceAbstract
{
    const PREBUY_SUCCESS = 'success';
    const PREBUY_ERROR = 'error';

    /**
     * @param int $userId
     * @param float $amount
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function withdrawMoneySafe(int $userId, float $amount) : ModelRequestResponse
    {
        $response['id'] = isset($_REQUEST['item']) ? $_REQUEST['item'] : '';
        $response['message'] = isset($_REQUEST['result']) ? $_REQUEST['result'] : self::PREBUY_ERROR;

        if ($response['message'] != self::PREBUY_SUCCESS) {
            return (new ModelRequestResponse(''))->setError(
                new ModelError('Billing',"Prebuy action was not success.")
            );
        }

        $result = $this->requestFotostranaApi(
            'Billing.withDrawMoneySafe',
            [
                EnumsProtocol::USER_ID => $userId,
                EnumsProtocol::MONEY => $amount
            ],
            false,
            EnumsProtocol::HTTP_QUERY_POST
        );

        if ($result->error()) {
            return $result;
        }

        if (!isset($result->data()['transferred']) || $result->data()['transferred'] <> $amount) {
            $result->setError(
                new ModelError('Billing',"Billing problem: " . serialize($result->error()->getMessage()))
            );
        }

        return $result;
    }

    /**
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getAppBalance() : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'Billing.getAppBalance',
            [],
            false
        );
    }

    /**
     * @var int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getUserBalance(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'Billing.getUserBalanceAny',
            [
                EnumsProtocol::USER_ID => $userId
            ],
            false
        );
    }

}