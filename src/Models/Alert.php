<?php

namespace App\Models;

class Alert
{
    private ?int $id=null;
    private string $type;
    private string $severity;
    private string $message;
    private bool $isRead;
    private ?int $productId;
    private ?int $stockId;
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

    public function getType(): string {
        return $this->type;
    }

    public function getSeverity(): string {
        return $this->severity;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getIsRead(): bool {
        return $this->isRead;
    }

    public function getProductId(): ?int {
        return $this->productId;
    }

    public function getStockId(): ?int {
        return $this->stockId;
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

    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    public function setSeverity(string $severity): self {
        $this->severity = $severity;
        return $this;
    }

    public function setMessage(string $message): self {
        $this->message = $message;
        return $this;
    }

    public function setIsRead(bool $isRead): self {
        $this->isRead = $isRead;
        return $this;
    }

    public function setProductId(?int $productId): self {
        $this->productId = $productId;
        return $this;
    }

    public function setStockId(?int $stockId): self {
        $this->stockId = $stockId;
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
            'type' => $this->type,
            'severity' => $this->severity,
            'message' => $this->message,
            'isRead' => $this->isRead,
            'productId' => $this->productId,
            'stockId' => $this->stockId,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}