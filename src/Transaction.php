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

    public const LANGUAGE_BG = 'bg';
    public const LANGUAGE_CS = 'cs';
    public const LANGUAGE_DE = 'de';
    public const LANGUAGE_EN = 'en';
    public const LANGUAGE_ES = 'es';
    public const LANGUAGE_FR = 'fr';
    public const LANGUAGE_HR = 'hr';
    public const LANGUAGE_HU = 'hu';
    public const LANGUAGE_IT = 'it';
    public const LANGUAGE_NL = 'nl';
    public const LANGUAGE_PL = 'pl';
    public const LANGUAGE_PT = 'pt';
    public const LANGUAGE_SE = 'se';
    public const LANGUAGE_SK = 'sk';

    protected array $signatureAttributes = [
        'sessionId',
        'merchantId',
        'amount',
        'currency',
        'crc'
    ];

    /**
     * @param string $sessionId
     * @param float|int $amount
     * @param string $email
     * @param string $returnUrl
     */
    public function __construct(string $sessionId, float|int $amount, string $email, string $returnUrl)
    {
        $this->data = [
            'sessionId' => $sessionId,
            'amount' => is_int($amount) ? $amount : (int)($amount * 100),
            'currency' => 'PLN',
            'country' => 'PL',
            'language' => self::LANGUAGE_PL,
            'description' => $sessionId,
            'email' => $email,
            'urlReturn' => $returnUrl,
            'channel' => self::CHANNEL_TRANSFERS,
            'encoding' => self::ENCODING_UTF_8
        ];
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): Transaction
    {
        $this->data['description'] = $description;
        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): Transaction
    {
        $this->data['currency'] = $currency;
        return $this;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): Transaction
    {
        $this->data['country'] = $country;
        return $this;
    }

    /**
     * @param int ...$channels
     */
    public function setChannel(... $channels): void
    {
        $this->data['channel'] = array_sum($channels);
    }

    /**
     * @param string $url
     */
    public function setStatusUrl(string $url): void
    {
        $this->data['urlStatus'] = $url;
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
}