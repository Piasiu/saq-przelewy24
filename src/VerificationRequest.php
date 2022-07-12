<?php
namespace Saq\Przelewy24;

use Saq\Przelewy24\Api\ApiRequest;
use Saq\Przelewy24\Api\ApiRequestInterface;

class VerificationRequest extends ApiRequest implements ApiRequestInterface
{
    protected array $signatureAttributes = [
        'merchantId',
        'posId',
        'sessionId',
        'amount',
        'currency',
        'orderId'
    ];

    public function __construct(string $sessionId, int $amount, string $currency, int $orderId)
    {
        $this->data = $data;
    }
}