<?php
namespace Saq\Przelewy24;

use Saq\Przelewy24\Api\ApiRequest;
use Saq\Przelewy24\Api\ApiRequestInterface;

class VerificationRequest extends ApiRequest implements ApiRequestInterface
{
    protected array $signatureAttributes = [
        'sessionId',
        'orderId',
        'amount',
        'currency',
        'crc'
    ];

    public function __construct(string $sessionId, int $amount, string $currency, int $orderId)
    {
        $this->data = [
            'sessionId' => $sessionId,
            'amount' => $amount,
            'currency' => $currency,
            'orderId' => $orderId
        ];
    }
}