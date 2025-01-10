<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function findAllMe(int $userId):array
    {
        return $this->categoryRepository->findAllMe($userId);
    }

    public function findOneMe(int $categoryId, int $userId):?Category
    {
        return $this->categoryRepository->findOneMe($categoryId, $userId);
    }

    public function saveMe(Category $category): bool
    {
        return $this->categoryRepository->saveMe($category);
    }

    public function deleteMe(int $categoryId, int $userId): bool
    {
        return $this->categoryRepository->deleteMe($categoryId, $userId);
    }

    public function totalCategory(int $userId): int{
        return $this->categoryRepository->countMe($userId);
    }

    public function existsMe(int $categoryId, int $userId): bool{
        return $this->categoryRepository->existsMe($categoryId, $userId);
    }

    public function searchMe(string $query,int $userId): array{
        return $this->categoryRepository->searchMe($query,$userId);
    }
}