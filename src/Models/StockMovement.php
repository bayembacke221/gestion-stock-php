<?php

namespace App\Models;

class StockMovement
{
    private ?int $id=null;
    private int $productId;
    private int $userId;
    private string $type;
    private float $quantity;
    private \DateTime $date;
    private ?string $reason;
    private ?string $referenceDocument;
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
                if (in_array($key, ['createdAt', 'updatedAt','date']) && !($value instanceof \DateTime)) {
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

    public function getUserId(): int {
        return $this->userId;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getQuantity(): float {
        return $this->quantity;
    }

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function getReason(): ?string {
        return $this->reason;
    }

    public function getReferenceDocument(): ?string {
        return $this->referenceDocument;
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

    public function setProductId(int $productId): self {
        $this->productId = $productId;
        return $this;
    }

    public function setUserId(int $userId): self {
        $this->userId = $userId;
        return $this;
    }

    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    public function setQuantity(float $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function setDate(\DateTime $date): self {
        $this->date = $date;
        return $this;
    }

    public function setReason(string $reason): self {
        $this->reason = $reason;
        return $this;
    }

    public function setReferenceDocument(string $referenceDocument): self {
        $this->referenceDocument = $referenceDocument;
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
            'productId' => $this->productId,
            'userId' => $this->userId,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'reason' => $this->reason,
            'referenceDocument' => $this->referenceDocument,
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