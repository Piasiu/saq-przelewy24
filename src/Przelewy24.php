<?php
namespace Saq\Przelewy24;

use RuntimeException;
use Saq\Przelewy24\Api\Api;
use Saq\Przelewy24\Api\VerificationResponse;

class Przelewy24
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Api
     */
    private Api $api;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->api = new Api($config);
        $this->config = $config;
    }

    /**
     * @param Transaction $transaction
     * @return string
     */
    public function register(Transaction $transaction): string
    {
        $data = $transaction->asArray();
        $data['merchantId'] = $this->config->getPosId();
        $data['posId'] = $this->config->getPosId();
        $data['sign'] = $this->config->getSign([
            $transaction->getSessionId(),
            $this->config->getPosId(),
            $transaction->getAmount(),
            $transaction->getCurrency()
        ]);
        $result = $this->send('/api/v1/transaction/register', 'POST', $data);
        return $this->config->getUrl('/trnRequest/'.$result['token']);
    }

    /**
     * @return Notification
     */
    public function handleWebhook(): Notification
    {
        return new Notification($this->config);
    }

    /**
     * @param VerificationRequest $verificationRequest
     * @return VerificationResponse
     */
    public function verify(VerificationRequest $verificationRequest): VerificationResponse
    {
        $verifyData = [
            'merchantId' => $this->config->getPosId(),
            'posId' => $this->config->getPosId(),
            'sessionId' => array_key_exists('sessionId', $data) ? $data['sessionId'] : '',
            'amount' => array_key_exists('amount', $data) ? $data['amount'] : 0,
            'currency' => array_key_exists('currency', $data) ? $data['currency'] : '',
            'orderId' => array_key_exists('orderId', $data) ? $data['orderId'] : 0
        ];
        $verifyData['sign'] = $this->config->getSign([
            $data['sessionId'],
            $data['orderId'],
            $data['amount'],
            $data['currency']
        ]);

        return $this->api->verify($verificationRequest);
    }

    /**
     * @param string $method
     * @param string $subUrl
     * @param array $data
     * @return array
     */
    private function send(string $method, string $subUrl, array $data): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->config->getUrl($subUrl),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => $this->config->getHeaders(),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false)
        {
            throw new RuntimeException($error);
        }

        $result = json_decode($response, true);

        if (array_key_exists('error', $result))
        {
            throw new RuntimeException($result['error']);
        }

        return $result['data'];
    }
}