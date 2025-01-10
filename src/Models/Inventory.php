<?php

namespace App\Models;

class Inventory
{
    private ?int $id=null;
    private \DateTime $date;
    private string $type;
    private string $status;
    private int $conductedBy;
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
                if (in_array($key, ['date', 'createdAt', 'updatedAt']) && !($value instanceof \DateTime)) {
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

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getConductedBy(): int {
        return $this->conductedBy;
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

    public function setDate(\DateTime $date): self {
        $this->date = $date;
        return $this;
    }

    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function setConductedBy(int $conductedBy): self {
        $this->conductedBy = $conductedBy;
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
            'date' => $this->date->format('Y-m-d H:i:s'),
            'type' => $this->type,
            'status' => $this->status,
            'conductedBy' => $this->conductedBy,
            'notes' => $this->notes,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}