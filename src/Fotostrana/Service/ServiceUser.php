<?php
namespace Fotostrana\Service;

use Fotostrana\Enums\EnumsProtocol;
use Fotostrana\Model\ModelError;
use Fotostrana\Model\ModelRequestResponse;

/**
 * Service for USER api
 * all public methods should return PetrovDAUtils\Model\ModelRequestResponse
 */
class ServiceUser extends ServiceAbstract
{
    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getUserProfile(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.getProfiles',
            [
                EnumsProtocol::USER_IDS => $userId,
                EnumsProtocol::FIELDS   => 'user_name,user_lastname,user_link,sex,birthday,photo_small,photo_97,photo_192,photo_big,photo_box,city_id,city_name,slogan,vip_end,is_payable'
            ]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function getRegistrationDate(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.getRegistrationDate',
            [EnumsProtocol::USER_ID => $userId]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function getFriendsIds(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.getFriendsAny',
            [EnumsProtocol::USER_ID => $userId]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function getIsAppInstall(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.isAppWidgetUser',
            [EnumsProtocol::USER_ID => $userId]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function getUserSettings(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.getUserSettingsAny',
            [EnumsProtocol::USER_ID => $userId]
        );
    }

    /**
     * @param int $userId
     * @param string $text
     * @param array $params
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function sendNotification(int $userId, string $text, array $params) : ModelRequestResponse
    {
        return  $this->requestFotostranaApi(
            'User.sendNotification',
            [
                EnumsProtocol::USER_IDS => $userId,
                EnumsProtocol::TEXT => $text,
                EnumsProtocol::PARAMS => $params,
            ]
        );
    }
}