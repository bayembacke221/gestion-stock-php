<?php

namespace App\Services;
use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function findAllMe(int $userId):array
    {
        return $this->productRepository->findAllMe($userId);
    }

    public function findOneMe(int $productId, int $userId):?Product
    {
        return $this->productRepository->findOneMe($productId, $userId);
    }

    public function saveMe(Product $product): bool
    {
        return $this->productRepository->saveMe($product);
    }

    public function deleteMe(int $productId, int $userId): bool
    {
        return $this->productRepository->deleteMe($productId, $userId);
    }

   public function searchMe(string $query,int $userId): array{
        return $this->productRepository->searchMe($query,$userId);
   }

    public function findByCategory(int $categoryId,int $userId): array{
        return $this->productRepository->findByCategory($categoryId,$userId);
    }

    public function totalProduct(int $userId): int{
        return $this->productRepository->totalProduct($userId);
    }

    public function percentageProductUp(int $userId): int{
        return $this->productRepository->percentageProductUp($userId);
    }
}