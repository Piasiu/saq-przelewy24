<?php
namespace Saq\Przelewy24;

use RuntimeException;
use Saq\Przelewy24\Api\Api;
use Saq\Przelewy24\Api\RegisterResponse;
use Saq\Przelewy24\Api\VerificationResponse;

class Przelewy24
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Api
     */
    private Api $api;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->api = new Api($config);
        $this->config = $config;
    }

    /**
     * @param Transaction $transaction
     * @return RegisterResponse
     */
    public function register(Transaction $transaction): RegisterResponse
    {
        return $this->api->register($transaction);
    }

    /**
     * @return Notification
     */
    public function handleWebhook(): Notification
    {
        return new Notification($this->config);
    }

    /**
     * @param VerificationRequest $verificationRequest
     * @return VerificationResponse
     */
    public function verify(VerificationRequest $verificationRequest): VerificationResponse
    {
        return $this->api->verify($verificationRequest);
    }
}