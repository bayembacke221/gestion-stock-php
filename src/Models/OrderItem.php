<?php

namespace App\Models;

class OrderItem
{
    private ?int $id=null;
    private int $purchaseOrderId;
    private int $productId;
    private int $quantity;
    private float $unitPrice;
    private float $totalPrice;
    private string $status;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(array $data = []) {
        $this->hydrate($data);
    }

    public function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method) && $value !== null) {
                if (in_array($key, ['createdAt', 'updatedAt']) && !($value instanceof \DateTime)) {
                    $value = new \DateTime($value);
                }
                $this->$method($value);
            }
        }
    }

    // Getters

    public function getId(): ?int {
        return $this->id;
    }

    public function getPurchaseOrderId(): int {
        return $this->purchaseOrderId;
    }

    public function getProductId(): int {
        return $this->productId;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getUnitPrice(): float {
        return $this->unitPrice;
    }

    public function getTotalPrice(): float {
        return $this->totalPrice;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    // Setters

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function setPurchaseOrderId(int $purchaseOrderId): self {
        $this->purchaseOrderId = $purchaseOrderId;
        return $this;
    }

    public function setProductId(int $productId): self {
        $this->productId = $productId;
        return $this;
    }

    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function setUnitPrice(float $unitPrice): self {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    public function setTotalPrice(float $totalPrice): self {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function toArray(): array {
        $data= [
            'purchaseOrderId' => $this->purchaseOrderId,
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'totalPrice' => $this->totalPrice,
            'status' => $this->status,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}