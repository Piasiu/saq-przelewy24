<?php
namespace Saq\Przelewy24\Api;

use Saq\Przelewy24\Config;

abstract class ApiRequest
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var Config|null
     */
    protected ?Config $config = null;

    /**
     * @var array
     */
    protected array $signatureAttributes = [];

    /**
     * @param Config $config
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
        $this->data = array_merge($this->data, [
            'merchantId' => $this->config->getMerchantId(),
            'posId' => $this->config->getPosId(),
            'crc' => $this->config->getCrc(),
        ]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        $data = [];

        foreach ($this->signatureAttributes as $name)
        {
            $data[$name] = array_key_exists($name, $this->data) ? $this->data[$name] : '';
        }

        $data['crc'] = $this->config->getCrc();
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return hash('sha384', $json);
    }
}