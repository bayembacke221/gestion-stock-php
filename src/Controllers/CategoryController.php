<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\CategoryService;

class CategoryController
{

    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService(new \App\Repositories\CategoryRepository());
    }

    public function index(){

        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $categories = $this->categoryService->findAllMe($decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $categories
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function show($categoryId){
        header('Content-Type: application/json');

        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $category = $this->categoryService->findOneMe($categoryId, $decoded->data->id);

            if (!$category) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Category not found"
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $category->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create(){
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $data['userId'] = $decoded->data->id;
            $category = new \App\Models\Category($data);

            if ($this->categoryService->saveMe($category)) {
                http_response_code(201);
                echo json_encode(["message" => "Category created successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Unable to create category"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function update($categoryId){
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $data['userId'] = $decoded->data->id;
            $data['id'] = $categoryId;
            $category = new \App\Models\Category($data);

            if ($this->categoryService->saveMe($category)) {
                http_response_code(200);
                echo json_encode(["message" => "Category updated successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Unable to update category"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function delete($categoryId){
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();

            if ($this->categoryService->deleteMe($categoryId, $decoded->data->id)) {
                http_response_code(200);
                echo json_encode(["message" => "Category deleted successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Unable to delete category"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function search(){
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $query = $_GET['q'] ?? '';

            $categories = $this->categoryService->searchMe($query, $decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $categories
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function total(){
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $total = $this->categoryService->totalCategory($decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $total
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function exists($categoryId){
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $exists = $this->categoryService->existsMe($categoryId, $decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $exists
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function showHomePage() {
        session_start();
        $categories = $this->categoryService->findAllMe($_SESSION['user_id']);
        $categoriesArray = array_map(function($category) {
            return $category->toArray();
        }, $categories);
        $view = new View('settings/categories/index');
        $view->assign('title', 'Categories - Settings');
        $view->assign('categories', $categoriesArray);
        $view->render();
    }


}