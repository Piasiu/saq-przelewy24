<?php
namespace Saq\Przelewy24\Api;

use RuntimeException;
use Saq\Przelewy24\Config;

class Api
{
    public const URL_LIVE = 'https://secure.przelewy24.pl/';
    public const URL_SANDBOX = 'https://sandbox.przelewy24.pl/';

    public const ENDPOINT_REGISTER = 'api/v1/trnRegister';
    public const ENDPOINT_VERIFY = 'api/v1/trnVerify';
    public const ENDPOINT_PAYMENT_GATEWAY = 'trnRequest/';

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * @var string[]
     */
    private array $headers;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->baseUrl = $config->isLive() ? self::URL_LIVE : self::URL_SANDBOX;
        $this->headers = [
            'Authorization: Basic '.base64_encode($config->getPosId().':'.$config->getSecretId()),
            'Content-Type: application/json',
            'cache-control: no-cache'
        ];
    }

    public function register(ApiRequestInterface $request): RegisterResponse
    {
        return new RegisterResponse(
            $this->baseUrl.self::ENDPOINT_PAYMENT_GATEWAY,
            $this->send('POST', self::ENDPOINT_REGISTER, $request->getData())
        );
    }

    public function verify(ApiRequestInterface $request): VerificationResponse
    {
        return new VerificationResponse(
            $this->send('PUT', self::ENDPOINT_VERIFY, $request->getData())
        );
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $parameters
     * @return array
     */
    private function send(string $method, string $endpoint, array $parameters): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($parameters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => $this->headers,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false)
        {
            return ['error' => $error];
        }

        return json_decode($response, true);
    }
}