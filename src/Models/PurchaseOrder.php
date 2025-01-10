<?php

namespace App\Models;

class PurchaseOrder
{
    private ?int $id=null;
    private int $supplierId;
    private \DateTime $orderDate;
    private ?\DateTime $expectedDeliveryDate;
    private ?float $totalAmount;
    private string $status;
    private string $paymentStatus;
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
                if (in_array($key, ['orderDate', 'expectedDeliveryDate', 'createdAt', 'updatedAt']) && !($value instanceof \DateTime)) {
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

    public function getSupplierId(): int {
        return $this->supplierId;
    }

    public function getOrderDate(): \DateTime {
        return $this->orderDate;
    }

    public function getExpectedDeliveryDate(): ?\DateTime {
        return $this->expectedDeliveryDate;
    }

    public function getTotalAmount(): ?float {
        return $this->totalAmount;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getPaymentStatus(): string {
        return $this->paymentStatus;
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

    public function setSupplierId(int $supplierId): self {
        $this->supplierId = $supplierId;
        return $this;
    }

    public function setOrderDate(\DateTime $orderDate): self {
        $this->orderDate = $orderDate;
        return $this;
    }

    public function setExpectedDeliveryDate(?\DateTime $expectedDeliveryDate): self {
        $this->expectedDeliveryDate = $expectedDeliveryDate;
        return $this;
    }

    public function setTotalAmount(?float $totalAmount): self {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function setPaymentStatus(string $paymentStatus): self {
        $this->paymentStatus = $paymentStatus;
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
            'supplierId' => $this->supplierId,
            'orderDate' => $this->orderDate->format('Y-m-d H:i:s'),
            'expectedDeliveryDate' => $this->expectedDeliveryDate?->format('Y-m-d H:i:s'),
            'totalAmount' => $this->totalAmount,
            'status' => $this->status,
            'paymentStatus' => $this->paymentStatus,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}