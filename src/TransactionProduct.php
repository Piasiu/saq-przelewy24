<?php
namespace Saq\Przelewy24;

class TransactionProduct
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @param string $sellerId
     * @param string $sellerCategory
     */
    public function __construct(string $sellerId, string $sellerCategory)
    {
        $this->data = [
            'sellerId' => $sellerId,
            'sellerCategory' => $sellerCategory
        ];
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->data['name'] = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->data['description'] = $description;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->data['price'] = (int)($price * 100);
    }

    /**
     * @param int $quantity
     */
    public function seQuantity(int $quantity): void
    {
        $this->data['quantity'] = $quantity;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->data['number'] = $number;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->data;
    }
}