<?php

namespace App\Models;

class Stock
{
    private ?int $id=null;
    private int $productId;
    private int $warehouseId;
    private float $quantity;
    private ?string $status;
    private \DateTime $lastCheckDate;
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
                if (in_array($key, ['createdAt', 'updatedAt','lastCheckDate']) && !($value instanceof \DateTime)) {
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

    public function getProductId(): int {
        return $this->productId;
    }

    public function getWarehouseId(): int {
        return $this->warehouseId;
    }

    public function getQuantity(): float {
        return $this->quantity;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function getLastCheckDate(): \DateTime {
        return $this->lastCheckDate;
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

    public function setProductId(int $productId): self {
        $this->productId = $productId;
        return $this;
    }

    public function setWarehouseId(int $warehouseId): self {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    public function setQuantity(float $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function setLastCheckDate(\DateTime $lastCheckDate): self {
        $this->lastCheckDate = $lastCheckDate;
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
            'productId' => $this->productId,
            'warehouseId' => $this->warehouseId,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'lastCheckDate' => $this->lastCheckDate->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}