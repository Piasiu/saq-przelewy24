<?php
namespace Saq\Przelewy24\Api;

use Saq\Przelewy24\Config;

interface ApiRequestInterface
{
    /**
     * @param Config $config
     */
    public function setConfig(Config $config): void;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return string
     */
    public function getSignature(): string;
}