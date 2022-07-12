<?php
namespace Saq\Przelewy24\Api;

class RegisterResponse extends ApiResponse
{
    /**
     * @var string|null
     */
    private ?string $token;

    /**
     * @var string
     */
    private string $gatewayUrl;

    /**
     * @param string $gatewayUrl
     * @param array $response
     */
    public function __construct(string $gatewayUrl, array $response)
    {
        parent::__construct($response);
        $this->gatewayUrl = $gatewayUrl;

        if (!$this->hasError() && array_key_exists('token', $response))
        {
            $this->token = $response['token'];
        }
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->gatewayUrl.$this->token;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
}