<?php

namespace App\Controllers;

use App\Models\Product;
use App\Services\ProductService;

class ProductController {
    private ProductService $productService;

    public function __construct() {
        $this->productService = new ProductService(new \App\Repositories\ProductRepository());
    }

    public function index() {
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $products = $this->productService->findAllMe($decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $products
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function show($productId) {
        header('Content-Type: application/json');

        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $product = $this->productService->findOneMe($productId, $decoded->data->id);

            if (!$product) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Product not found"
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $product->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create() {
        try {
            error_log("Début de la méthode create");

            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $rawData = file_get_contents("php://input");
            error_log("Données reçues: " . $rawData);

            $data = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Erreur JSON: " . json_last_error_msg());
                throw new \Exception("Invalid JSON data");
            }

            error_log("Données décodées: " . print_r($data, true));

            // Ajouter les timestamps
            $data['createdAt'] = new \DateTime();
            $data['updatedAt'] = new \DateTime();

            // Créer une nouvelle instance de Product
            $product = new Product();
            $product->hydrate($data);

            error_log("Produit après hydratation: " . print_r($product->toArray(), true));

            if ($this->productService->saveMe($product)) {
                http_response_code(201);
                $response = [
                    "status" => "success",
                    "message" => "Product created successfully"
                ];
                error_log("Succès: " . json_encode($response));
                echo json_encode($response);
            } else {
                http_response_code(400);
                $response = [
                    "status" => "error",
                    "message" => "Unable to create product"
                ];
                error_log("Échec: " . json_encode($response));
                echo json_encode($response);
            }
        } catch (\Exception $e) {
            error_log('Exception complète: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function update($productId) {
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $data = json_decode(file_get_contents("php://input"), true);

            // Log des données reçues
            error_log('Données reçues : ' . print_r($data, true));

            $product = $this->productService->findOneMe($productId, $decoded->data->id);
            if (!$product) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Product not found"
                ]);
                return;
            }

            // Log de l'état du produit avant hydratation
            error_log('Produit avant hydratation : ' . print_r($product, true));

            $data['updatedAt'] = new \DateTime();
            $product->hydrate($data);

            // Log de l'état du produit après hydratation
            error_log('Produit après hydratation : ' . print_r($product, true));

            if ($this->productService->saveMe($product)) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Product updated successfully"
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Unable to update product"
                ]);
            }
        } catch (\Exception $e) {
            error_log('Exception : ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function delete($productId) {
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();

            if ($this->productService->deleteMe($productId, $decoded->data->id)) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Product deleted successfully"
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Unable to delete product"
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function search() {
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $query = $_GET['q'] ?? '';

            $products = $this->productService->searchMe($query, $decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $products
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function getByCategory($categoryId) {
        try {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            $products = $this->productService->findByCategory($categoryId, $decoded->data->id);

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $products
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}