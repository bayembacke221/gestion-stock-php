<?php

namespace App\Models;

class StockReturn
{
    private ?int $id=null;
    private \DateTime $date;
    private string $reason;
    private string $status;
    private int $approvedBy;
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
                if (in_array($key, ['date', 'createdAt', 'updatedAt']) && !($value instanceof \DateTime)) {
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

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function getReason(): string {
        return $this->reason;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getApprovedBy(): int {
        return $this->approvedBy;
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

    public function setDate(\DateTime $date): self {
        $this->date = $date;
        return $this;
    }

    public function setReason(string $reason): self {
        $this->reason = $reason;
        return $this;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function setApprovedBy(int $approvedBy): self {
        $this->approvedBy = $approvedBy;
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
            'date' => $this->date->format('Y-m-d H:i:s'),
            'reason' => $this->reason,
            'status' => $this->status,
            'approvedBy' => $this->approvedBy,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}