<?php

namespace App\Models;

class Product {
    private int $id;
    private string $name;
    private string $description;
    private string $barcode;
    private float $price;
    private int $categoryId;
    private float $minStock;
    private float $maxStock;
    private string $unit;
    private int $userId;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(array $data = []) {
        $this->hydrate($data);
    }

    public function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                if (in_array($key, ['createdAt', 'updatedAt']) && !($value instanceof \DateTime)) {
                    $value = new \DateTime($value);
                }
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    // Setters
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getBarcode(): string {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self {
        $this->barcode = $barcode;
        return $this;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): self {
        $this->price = $price;
        return $this;
    }

    public function getCategoryId(): int {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getMinStock(): float {
        return $this->minStock;
    }

    public function setMinStock(float $minStock): self {
        $this->minStock = $minStock;
        return $this;
    }

    public function getMaxStock(): float {
        return $this->maxStock;
    }

    public function setMaxStock(float $maxStock): self {
        $this->maxStock = $maxStock;
        return $this;
    }

    public function getUnit(): string {
        return $this->unit;
    }

    public function setUnit(string $unit): self {
        $this->unit = $unit;
        return $this;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $userId): self {
        $this->userId = $userId;
        return $this;
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'barcode' => $this->getBarcode(),
            'price' => $this->getPrice(),
            'category_id' => $this->getCategoryId(),
            'min_stock' => $this->getMinStock(),
            'max_stock' => $this->getMaxStock(),
            'unit' => $this->getUnit(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),
            'user_id' => $this->getUserId()
        ];
    }

}