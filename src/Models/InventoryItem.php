<?php

namespace App\Models;

class InventoryItem
{
    private ?int $id=null;
    private int $inventoryId;
    private int $productId;
    private float $expectedQuantity;
    private float $actualQuantity;
    private float $discrepancy;
    private ?string $notes;
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

    public function getInventoryId(): int {
        return $this->inventoryId;
    }

    public function getProductId(): int {
        return $this->productId;
    }

    public function getExpectedQuantity(): float {
        return $this->expectedQuantity;
    }

    public function getActualQuantity(): float {
        return $this->actualQuantity;
    }

    public function getDiscrepancy(): float {
        return $this->discrepancy;
    }

    public function getNotes(): ?string {
        return $this->notes;
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

    public function setInventoryId(int $inventoryId): self {
        $this->inventoryId = $inventoryId;
        return $this;
    }

    public function setProductId(int $productId): self {
        $this->productId = $productId;
        return $this;
    }

    public function setExpectedQuantity(float $expectedQuantity): self {
        $this->expectedQuantity = $expectedQuantity;
        return $this;
    }

    public function setActualQuantity(float $actualQuantity): self {
        $this->actualQuantity = $actualQuantity;
        return $this;
    }

    public function setDiscrepancy(float $discrepancy): self {
        $this->discrepancy = $discrepancy;
        return $this;
    }

    public function setNotes(?string $notes): self {
        $this->notes = $notes;
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
            'inventoryId' => $this->inventoryId,
            'productId' => $this->productId,
            'expectedQuantity' => $this->expectedQuantity,
            'actualQuantity' => $this->actualQuantity,
            'discrepancy' => $this->discrepancy,
            'notes' => $this->notes,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}