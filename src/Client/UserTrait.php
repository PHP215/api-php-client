<?php

/**
 * @package Nekofar\Nobitex
 *
 * @author Milad Nekofar <milad@nekofar.com>
 */

declare(strict_types=1);

namespace Nekofar\Nobitex\Client;

use Exception;
use InvalidArgumentException;
use Nekofar\Nobitex\Model\Profile;

/**
 * Trait User
 */
trait UserTrait
{

    /**
     * @var \Http\Client\Common\HttpMethodsClient
     */
    private $httpClient;

    /**
     * @var \JsonMapper
     */
    private $jsonMapper;

    /**
     * Return a Profile object on success or false on unexpected errors
     *
     * @return \Nekofar\Nobitex\Model\Profile|false
     *
     * @throws \Http\Client\Exception
     * @throws \JsonMapper_Exception
     * @throws \Exception
     */
    public function getUserProfile()
    {
        $resp = $this->httpClient->post('/users/profile');
        $json = json_decode((string) $resp->getBody());

        if (isset($json->message) && 'failed' === $json->status) {
            throw new Exception($json->message);
        }

        if (isset($json->profile) && 'ok' === $json->status) {
            $this->jsonMapper->undefinedPropertyHandler = [
                Profile::class,
                'setUndefinedProperty',
            ];

            return $this->jsonMapper->map($json->profile, new Profile());
        }

        return false;
    }

    /**
     * Return an array on success or false on unexpected errors.
     *
     * @return array<string,string|array>|false
     *
     * @throws \Http\Client\Exception
     * @throws \Exception
     */
    public function getUserLoginAttempts()
    {
        $resp = $this->httpClient->post('/users/login-attempts');
        $json = json_decode((string) $resp->getBody());

        if (isset($json->message) && 'failed' === $json->status) {
            throw new Exception($json->message);
        }

        if (isset($json->attempts) && 'ok' === $json->status) {
            return (array) $json->attempts;
        }

        return false;
    }

    /**
     * @return string|false
     *
     * @throws \Http\Client\Exception
     * @throws \Exception
     */
    public function getUserReferralCode()
    {
        $resp = $this->httpClient->post('/users/get-referral-code');
        $json = json_decode((string) $resp->getBody());

        if (isset($json->message) && 'failed' === $json->status) {
            throw new Exception($json->message);
        }

        if (isset($json->referralCode) && 'ok' === $json->status) {
            return $json->referralCode;
        }

        return false;
    }

    /**
     * @param array<string, integer|string> $args
     *
     * @throws \Http\Client\Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function addUserCard(array $args): bool
    {
        if (
            !array_key_exists('bank', $args) ||
            in_array($args['bank'], [null, ''], true)
        ) {
            throw new InvalidArgumentException("Bank name is invalid.");
        }

        if (
            !array_key_exists('number', $args) ||
            1 !== preg_match('/^\d{16}$/', (string) $args['number'])
        ) {
            throw new InvalidArgumentException("Card number is invalid.");
        }

        $data = json_encode($args);
        $resp = $this->httpClient->post('/users/cards-add', [], $data);
        $json = json_decode((string) $resp->getBody());

        if (isset($json->message) && 'failed' === $json->status) {
            throw new Exception($json->message);
        }

        return isset($json->status) && 'ok' === $json->status;
    }

    /**
     * @param array<string, integer|string> $args
     *
     * @throws \Http\Client\Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function addUserAccount(array $args): bool
    {
        if (
            !array_key_exists('bank', $args) ||
            in_array($args['bank'], [null, ''], true)
        ) {
            throw new InvalidArgumentException("Bank name is invalid.");
        }

        if (
            !array_key_exists('number', $args) ||
            1 !== preg_match('/^\d+$/', (string) $args['number'])
        ) {
            throw new InvalidArgumentException("Account number is invalid.");
        }

        if (
            !array_key_exists('shaba', $args) ||
            1 !== preg_match('/^IR\d{24}$/', (string) $args['shaba'])
        ) {
            throw new InvalidArgumentException("Account shaba is invalid.");
        }

        $data = json_encode($args);
        $resp = $this->httpClient->post('/users/account-add', [], $data);
        $json = json_decode((string) $resp->getBody());

        if (isset($json->message) && 'failed' === $json->status) {
            throw new Exception($json->message);
        }

        return isset($json->status) && 'ok' === $json->status;
    }

    /**
     * Return an array on success or false on unexpected errors.
     *
     * @return array<string,string|array>|false
     *
     * @throws \Http\Client\Exception
     * @throws \Exception
     */
    public function getUserLimitations()
    {
        $resp = $this->httpClient->post('/users/get-referral-code');
        $json = json_decode((string) $resp->getBody());

        if (isset($json->message) && 'failed' === $json->status) {
            throw new Exception($json->message);
        }

        if (isset($json->limitations) && 'ok' === $json->status) {
            return json_decode(
                json_encode($json->limitations, JSON_THROW_ON_ERROR),
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        }

        return false;
    }
}
