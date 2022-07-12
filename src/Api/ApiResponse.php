<?php
namespace Saq\Przelewy24\Api;

abstract class ApiResponse
{
    /**
     * @var string|null
     */
    private ?string $error = null;

    public function __construct(array $response)
    {
        if (array_key_exists('error', $response))
        {
            $this->error = $response['error'];
        }
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error !== null;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}