<?php
namespace Saq\Przelewy24;

use Saq\Przelewy24\Api\ApiRequest;
use Saq\Przelewy24\Api\ApiRequestInterface;

class Transaction extends ApiRequest implements ApiRequestInterface
{
    public const CHANNEL_CARD = 1;
    public const CHANNEL_TRANSFERS = 2;
    public const CHANNEL_TRADITIONAL_TRANSFER = 4;
    public const CHANNEL_ALL = 16;
    public const CHANNEL_PREPAYMENT = 32;
    public const CHANNEL_PAY_BY_LINK = 64;
    public const CHANNEL_INSTALLMENTS = 128;
    public const CHANNEL_WALLETY = 256;

    public const ENCODING_ISO_8859_2 = 'ISO-8859-2';
    public const ENCODING_UTF_8 = 'UTF-8';
    public const ENCODING_WINDOWS_1250 = 'Windows-1250';

    public function __construct(string $returnUrl, string $email, string $sessionId, float $amount, ?string $description = null, string $currency = 'PLN', $country = 'PL', $language = 'pl')
    {
        $this->data = [
            'sessionId' => $sessionId,
            'amount' => (int)($amount * 100),
            'currency' => $currency,
            'country' => $country,
            'language' => $language,
            'description' => $description ?? $sessionId,
            'email' => $email,
            'urlReturn' => $returnUrl,
            'channel' => self::CHANNEL_TRANSFERS
        ];
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->data['sessionId'];
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->data['amount'];
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->data['currency'];
    }

    /**
     * @param string $name
     * @param string|null $address
     * @param string|null $zipCode
     * @param string|null $city
     * @param string|null $phoneNumber
     */
    public function setClient(string $name, ?string $address = null, ?string $zipCode = null, ?string $city = null, ?string $phoneNumber = null): void
    {
        $this->data['client'] = $name;

        if ($address !== null)
        {
            $this->data['address'] = $address;
        }

        if ($zipCode !== null)
        {
            $this->data['zip'] = $zipCode;
        }

        if ($city !== null)
        {
            $this->data['city'] = $city;
        }

        if ($phoneNumber !== null)
        {
            $this->data['phone'] = $phoneNumber;
        }
    }

    /**
     * @param string $url
     */
    public function setStatusUrl(string $url): void
    {
        $this->data['urlStatus'] = $url;
    }

    /**
     * @param int $channel
     */
    public function setChannel(int $channel): void
    {
        $this->data['channel'] = $channel;
    }

    /**
     * @param TransactionProduct $product
     */
    public function addProduct(TransactionProduct $product): void
    {
        if (!array_key_exists('cart', $this->data))
        {
            $this->data['cart'] = [];
        }

        $this->data['cart'][] = $product->asArray();
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->data;
    }
}