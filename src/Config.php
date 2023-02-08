<?php
namespace Saq\Przelewy24;

class Config
{
    /**
     * @var int
     */
    private int $merchantId;

    /**
     * @var int
     */
    private int $posId;

    /**
     * @var string
     */
    private string $secretId;

    /**
     * @var string
     */
    private string $crc;

    /**
     * @var bool
     */
    private bool $live;

    /**
     * @param int $merchantId
     * @param int $posId
     * @param string $secretId
     * @param string $crc
     * @param bool $live
     */
    public function __construct(int $merchantId, int $posId, string $secretId, string $crc, bool $live = false)
    {
        $this->merchantId = $merchantId;
        $this->posId = $posId;
        $this->secretId = $secretId;
        $this->crc = $crc;
        $this->live = $live;
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * @return int
     */
    public function getPosId(): int
    {
        return $this->posId;
    }

    /**
     * @return string
     */
    public function getSecretId(): string
    {
        return $this->secretId;
    }

    /**
     * @return string
     */
    public function getCrc(): string
    {
        return $this->crc;
    }

    /**
     * @return bool
     */
    public function isLive(): bool
    {
        return $this->live;
    }
}