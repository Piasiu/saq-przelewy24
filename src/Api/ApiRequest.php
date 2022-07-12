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
            'merchantId' => $this->config->getPosId(),
            'posId' => $this->config->getPosId(),
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

        foreach ($this->data as $name => $value)
        {
            if (array_key_exists($name, $this->data))
            {
                $data[] = $value;
            }
        }

        $data[] = $this->config->getCrc();
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return hash('sha384', $json);
    }
}