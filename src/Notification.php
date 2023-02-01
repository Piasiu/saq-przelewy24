<?php
namespace Saq\Przelewy24;

class Notification
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var array
     */
    private array $data = [];

    /**
     * @var bool|null
     */
    private ?bool $correct = null;

    /**
     * @var array|string[]
     */
    private array $signatureAttributes = [
        'merchantId',
        'posId',
        'sessionId',
        'amount',
        'originAmount',
        'currency',
        'orderId',
        'methodId',
        'statement'
    ];

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        if (!$this->handle())
        {
            $this->correct = false;
        }
    }

    /**
     * @return bool
     */
    private function handle(): bool
    {
        $json = file_get_contents('php://input');

        if ($json !== false)
        {
            $data = json_decode($json, true);

            if (is_array($data))
            {
                $this->data = $data;
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function validate(): bool
    {
        $data = [];

        foreach ($this->signatureAttributes as $attribute)
        {
            if (array_key_exists($attribute, $this->data))
            {
                $data[$attribute] = $this->data[$attribute];
            }
            else
            {
                return false;
            }
        }

        if ($this->data['merchantId'] !== $this->config->getPosId() || $this->data['posId'] !== $this->config->getPosId())
        {
            return false;
        }

        $data['crc'] = $this->config->getCrc();
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return hash('sha384', $json) === $this->data['sign'];
    }

    /**
     * @return bool
     */
    public function isCorrect(): bool
    {
        if ($this->correct === null)
        {
            $this->correct = $this->validate();
        }

        return $this->correct;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->isCorrect() ? $this->data['sessionId'] : '';
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->isCorrect() ? $this->data['amount'] : 0;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->isCorrect() ? $this->data['currency'] : '';
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->isCorrect() ? $this->data['orderId'] : 0;
    }

    /**
     * @return VerificationRequest|null
     */
    public function getRequest(): ?VerificationRequest
    {
        if (!$this->isCorrect())
        {
            return null;
        }

        return new VerificationRequest(
            $this->getSessionId(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getOrderId()
        );
    }
}