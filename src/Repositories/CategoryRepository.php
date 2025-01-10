<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    private $db;
    private $table = 'category';

    public function __construct() {
        $this->db = \App\Config\Database::getInstance()->getConnection();
    }

    public function findAllMe(int $userId): array {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($row) {
            if (isset($row['created_at'])) {
                $row['createdAt'] = new \DateTime($row['created_at']);
                unset($row['created_at']);
            }
            if (isset($row['updated_at'])) {
                $row['updatedAt'] = new \DateTime($row['updated_at']);
                unset($row['updated_at']);
            }

            $mappedRow = [
                'id' => $row['id'],
                'name' => $row['name'],
                'userId' => $row['user_id'],
                'createdAt' => $row['createdAt'],
                'updatedAt' => $row['updatedAt']
            ];

            return new Category($mappedRow);
        }, $results);
    }

    public function findOneMe(int $categoryId, int $userId): ?Category
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $categoryId, 'user_id' => $userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        if (isset($result['created_at'])) {
            $result['createdAt'] = new \DateTime($result['created_at']);
            unset($result['created_at']);
        }
        if (isset($result['updated_at'])) {
            $result['updatedAt'] = new \DateTime($result['updated_at']);
            unset($result['updated_at']);
        }

        $mappedResult = [
            'id' => $result['id'],
            'name' => $result['name'],
            'userId' => $result['user_id'],
            'createdAt' => $result['createdAt'],
            'updatedAt' => $result['updatedAt']
        ];

        return new Category($mappedResult);
    }

    public function saveMe(Category $category): bool {
        if ($category->getId()) {
            return $this->update($category);
        }
        return $this->insert($category);
    }

    private function insert(Category $category): bool {
        $query = "INSERT INTO " . $this->table . " (name,description, user_id) VALUES (:name,:description, :user_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'user_id' => $category->getUserId()
        ]);
    }

    private function update(Category $category): bool {
        $query = "UPDATE " . $this->table . " SET name = :name, description = :description WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'user_id' => $category->getUserId()
        ]);
    }

    public function deleteMe(int $categoryId, int $userId): bool {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $categoryId, 'user_id' => $userId]);
    }

    public function existsMe(int $categoryId, int $userId): bool {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $categoryId, 'user_id' => $userId]);
        return (bool) $stmt->fetchColumn();
    }

    public function searchMe(string $query,int $userId): array{
        $query = "SELECT * FROM " . $this->table . " WHERE name LIKE :query AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['query' => "%$query%", 'user_id' => $userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($row) {
            if (isset($row['created_at'])) {
                $row['createdAt'] = new \DateTime($row['created_at']);
                unset($row['created_at']);
            }
            if (isset($row['updated_at'])) {
                $row['updatedAt'] = new \DateTime($row['updated_at']);
                unset($row['updated_at']);
            }

            $mappedRow = [
                'id' => $row['id'],
                'name' => $row['name'],
                'userId' => $row['user_id'],
                'createdAt' => $row['createdAt'],
                'updatedAt' => $row['updatedAt']
            ];

            return new Category($mappedRow);
        }, $results);
    }

    public function countMe(int $userId): int {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }
}