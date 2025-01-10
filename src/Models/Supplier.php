<?php

namespace App\Models;

class Supplier
{
    private ?int $id=null;
    private string $name;
    private ?string $contact;
    private ?string $email;
    private ?string $phone;
    private ?string $address;
    private ?float $rating;
    private ?string $paymentTerms;
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

    public function getName(): string {
        return $this->name;
    }

    public function getContact(): ?string {
        return $this->contact;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function getRating(): ?float {
        return $this->rating;
    }

    public function getPaymentTerms(): ?string {
        return $this->paymentTerms;
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

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function setContact(?string $contact): self {
        $this->contact = $contact;
        return $this;
    }

    public function setEmail(?string $email): self {
        $this->email = $email;
        return $this;
    }

    public function setPhone(?string $phone): self {
        $this->phone = $phone;
        return $this;
    }

    public function setAddress(?string $address): self {
        $this->address = $address;
        return $this;
    }

    public function setRating(?float $rating): self {
        $this->rating = $rating;
        return $this;
    }

    public function setPaymentTerms(?string $paymentTerms): self {
        $this->paymentTerms = $paymentTerms;
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
            'name' => $this->getName(),
            'contact' => $this->getContact(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress(),
            'rating' => $this->getRating(),
            'paymentTerms' => $this->getPaymentTerms(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];

        if ($this->id !== null) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}