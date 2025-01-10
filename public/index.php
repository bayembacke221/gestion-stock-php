<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\ProductController;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/login-registration-with-jwt';

$clean_path = str_replace($base_path, '', $request_uri);
$clean_path = str_replace('/public', '', $clean_path);
$path = strtolower(parse_url($clean_path, PHP_URL_PATH));

$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();
$productController = new ProductController();

if ($method === 'POST' || strpos($path, '/protected') === 0) {
    header('Content-Type: application/json');
}

switch ($path) {
    case '/':
    case '/home':
    case '':
        if ($method === 'GET') {
            $authController->showHomePage();
        }
        break;

    case '/login':
        if ($method === 'GET') {
            $authController->showLoginPage();
        } elseif ($method === 'POST') {
            $authController->login();
        }
        break;

    case '/register':
        if ($method === 'GET') {
            $authController->showRegisterPage();
        } elseif ($method === 'POST') {
            $authController->register();
        }
        break;

    case '/protected':
        if ($method === 'GET') {
            $decoded = \App\Middleware\AuthMiddleware::verifyToken();
            echo json_encode(["message" => "Protected route accessed", "user" => $decoded->data]);
        }
        break;
    case '/dashboard':
        if ($method === 'GET') {
            try {
                $decoded = \App\Middleware\AuthMiddleware::verifyToken();
                if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                    echo json_encode(["success" => true, "user" => $decoded->data]);
                    exit;
                }
                $authController->showDashboard($decoded->data);
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(["message" => $e->getMessage()]);
                exit;
            }
        }
        break;
    case '/api/products':
        if ($method === 'GET') {
            $productController->index();
        } elseif ($method === 'POST') {
            $productController->store();
        }
        break;

    case (preg_match('/^\/api\/products\/(\d+)$/', $path, $matches) ? true : false):
        $productId = $matches[1];
        if ($method === 'GET') {
            $productController->show($productId);
        } elseif ($method === 'PUT' || $method === 'PATCH') {
            $productController->update($productId);
        } elseif ($method === 'DELETE') {
            $productController->delete($productId);
        }
        break;

    case '/api/products/search':
        if ($method === 'GET') {
            $productController->search();
        }
        break;

    case (preg_match('/^\/api\/products\/category\/(\d+)$/', $path, $matches) ? true : false):
        if ($method === 'GET') {
            $productController->getByCategory($matches[1]);
        }
        break;

    case '/logout':
        if ($method === 'GET') {
            $authController->logout();
        }
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
}