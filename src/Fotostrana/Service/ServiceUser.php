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
                EnumsProtocol::FIELDS   =>'user_name,time_out,user_is_hidden,user_lastname,user_link,sex,birthday,photo_small,photo_97,photo_192,photo_big,photo_box,city_id,city_name,slogan,vip_end,is_payable'
            ]
        );
    }
    /**
     * @param int[] $userIds
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getUsersProfiles(array $userIds) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.getProfiles',
            [
                EnumsProtocol::USER_IDS => implode(',',$userIds),
                EnumsProtocol::FIELDS   =>'user_name,time_in,time_out,user_is_hidden,user_lastname,user_link,sex,birthday,photo_small,photo_97,photo_192,photo_big,photo_box,city_id,city_name,slogan,vip_end,is_payable'
            ]
        );
    }


    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getLastOnline(int $userId)
    {
        return $this->requestFotostranaApi(
            'User.lastOnline',
            [
                EnumsProtocol::USER_IDS => $userId,
            ]
        );
    }

    /**
     * @param array $userIds
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getLastOnlineArray(array $userIds)
    {
        return $this->requestFotostranaApi(
            'User.lastOnline',
            [
                EnumsProtocol::USER_IDS => implode(',',$userIds),
            ]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function isOnline(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.isOnline',
            [
                EnumsProtocol::USER_IDS => $userId,
            ]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getUserBlackList(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.blackList', [EnumsProtocol::USER_IDS => $userId,]
        );
    }

    public function getUserInterests(int $userId)
    {
        return $this->requestFotostranaApi(
            'User.interests',
            [EnumsProtocol::USER_ID => $userId,]
        );
    }

    public function getUserNewGuests(int $userId)
    {
        return $this->requestFotostranaApi(
            'User.newGuests',
            [EnumsProtocol::USER_ID => $userId,]
        );
    }

    public function getUsersHolidays(array $userIds)
    {
        return $this->requestFotostranaApi(
            'User.holidays',
            [EnumsProtocol::USER_IDS => implode(',', $userIds),]
        );
    }

    /**
     * @param array $userIds
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function getUsersBlackList(array $userIds) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.blackList',
            [
                EnumsProtocol::USER_IDS => implode(',', $userIds),
            ]
        );
    }

    /**
     * @param array $userIds
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function areUsersOnline(array $userIds) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.isOnline',
            [
                EnumsProtocol::USER_IDS => implode(',',$userIds),
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
            'User.getRegistrationDate', [EnumsProtocol::USER_ID => $userId]
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
            'User.getFriendsAny', [EnumsProtocol::USER_ID => $userId]
        );
    }

    /**
     * @param int $userId
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function isUserAppInstalled(int $userId) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.isAppInstalled', [EnumsProtocol::USER_IDS => $userId]
        );
    }

    /**
     * @param int[] $userIds
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function areUsersAppInstalled(array $userIds) : ModelRequestResponse
    {
        return $this->requestFotostranaApi(
            'User.isAppInstalled', [EnumsProtocol::USER_IDS => implode(',', $userIds)]
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
            'User.getUserSettingsAny', [EnumsProtocol::USER_ID, $userId]
        );
    }

    /**
     * @param int $userId
     * @param string $text
     * @param string $urlParams
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function sendNotification(int $userId, string $text, string $urlParams) : ModelRequestResponse
    {
        return  $this->sendNotificationMulti([$userId], $text, $urlParams);
    }

    /**
     * Users Ids limit is 1000 per request
     *
     * @param array $userIds
     * @param string $text
     * @param string $urlParams
     * @return ModelRequestResponse
     * @throws ModelError
     */
    public function sendNotificationMulti(array $userIds, string $text, string $urlParams) : ModelRequestResponse
    {
        return  $this->requestFotostranaApi(
            'User.sendNotification',
            [
                EnumsProtocol::USER_IDS => implode(',',$userIds),
                EnumsProtocol::TEXT => $text,
                EnumsProtocol::PARAMS => $urlParams,
            ]
        );
    }

    public function traceEvent(int $userId, string $actionId)
    {
        return $this->requestFotostranaApi(
            'User.traceAction',
            [EnumsProtocol::USER_ID => $userId,EnumsProtocol::ACTION_ID => $actionId,],
            true,
            EnumsProtocol::HTTP_QUERY_POST
        );
    }
}