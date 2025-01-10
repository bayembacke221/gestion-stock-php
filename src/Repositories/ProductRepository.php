<?php

namespace App\Repositories;

use App\Models\Product;
use PDO;

class ProductRepository {
    private $db;
    private $table = 'product';

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
                'description' => $row['description'],
                'barcode' => $row['barcode'],
                'price' => $row['price'],
                'categoryId' => $row['category_id'],
                'minStock' => $row['min_stock'],
                'maxStock' => $row['max_stock'],
                'unit' => $row['unit'],
                'userId' => $row['user_id'],
                'createdAt' => $row['createdAt'],
                'updatedAt' => $row['updatedAt']
            ];

            return new Product($mappedRow);
        }, $results);
    }

    public function findOneMe(int $productId, int $userId): ?Product
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $productId, 'user_id' => $userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        // Use the same mapping logic as findAllMe for consistency
        if (isset($result['created_at'])) {
            $result['createdAt'] = new \DateTime($result['created_at']);
            unset($result['created_at']);
        }
        if (isset($result['updated_at'])) {
            $result['updatedAt'] = new \DateTime($result['updated_at']);
            unset($result['updated_at']);
        }

        $mappedRow = [
            'id' => $result['id'],
            'name' => $result['name'],
            'description' => $result['description'],
            'barcode' => $result['barcode'],
            'price' => (float)$result['price'],
            'categoryId' => $result['category_id'],
            'minStock' => $result['min_stock'],
            'maxStock' => $result['max_stock'],
            'unit' => $result['unit'],
            'userId' => $result['user_id'],
            'createdAt' => $result['createdAt'] ?? null,
            'updatedAt' => $result['updatedAt'] ?? null
        ];

        return new Product($mappedRow);
    }

    public function saveMe(Product $product): bool {
        if ($product->getId()) {
            return $this->update($product);
        }
        return $this->insert($product);
    }

    private function insert(Product $product): bool {
        $sql = 'INSERT INTO '. $this->table .' (name, description, barcode, price, category_id, min_stock, max_stock, unit, created_at,user_id)
         VALUES (:name, :description, :barcode, :price, :category_id, :min_stock, :max_stock, :unit, NOW(),:user_id)';


        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'barcode' => $product->getBarcode(),
            'price' => $product->getPrice(),
            'category_id' => $product->getCategoryId(),
            'min_stock' => $product->getMinStock(),
            'max_stock' => $product->getMaxStock(),
            'unit' => $product->getUnit(),
            'user_id' => $product->getUserId()
        ]);
    }

    private function update(Product $product): bool {
        $sql = 'UPDATE '. $this->table .' SET 
        name = :name, 
        description = :description, 
        barcode = :barcode, 
        price = :price, 
        category_id = :category_id, 
        min_stock = :min_stock, 
        max_stock = :max_stock, 
        unit = :unit, 
        updated_at = NOW() 
        WHERE id = :id AND user_id = :user_id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'barcode' => $product->getBarcode(),
            'price' => $product->getPrice(),
            'category_id' => $product->getCategoryId(),
            'min_stock' => $product->getMinStock(),
            'max_stock' => $product->getMaxStock(),
            'unit' => $product->getUnit(),
            'user_id' => $product->getUserId()
        ]);
    }

    public function deleteMe(int $productId,int $userId): bool {
        $sql = 'DELETE FROM '. $this->table .' WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $productId, 'user_id' => $userId]);
    }

    public function searchMe(string $query,int $userId): array {
        $sql = 'SELECT * FROM '. $this->table .' WHERE name LIKE :query AND user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => '%'. $query .'%', 'user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Product::class);
    }

    public function findByCategory(int $categoryId,int $userId): array {
        $sql = 'SELECT * FROM '. $this->table .' WHERE category_id = :category_id AND user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $categoryId, 'user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Product::class);
    }


    public  function totalProduct(int $userId): int{
        $sql = 'SELECT COUNT(*) FROM '. $this->table .' WHERE user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function percentageProductUp(int $userId): int{
        $sql = 'SELECT COUNT(*) FROM '. $this->table .' WHERE user_id = :user_id AND price > 0';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $totalProduct = $stmt->fetchColumn();
        $sql = 'SELECT COUNT(*) FROM '. $this->table .' WHERE user_id = :user_id AND price > 0 AND price < 100';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $totalProductUnder100 = $stmt->fetchColumn();
        return ($totalProductUnder100 / $totalProduct) * 100;
    }
}