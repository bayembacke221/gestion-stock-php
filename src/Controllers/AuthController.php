<?php

namespace App\Controllers;


use App\Core\View;

use App\Services\ProductService;
use Firebase\JWT\JWT;

class AuthController {
    private $user;
    private ProductService $productService;

    public function __construct() {
        $this->user = new \App\Models\User();
        $this->productService = new ProductService(new \App\Repositories\ProductRepository());
    }


    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($this->user->create($data)) {
            http_response_code(201);
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Unable to register user"]);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $this->user->findByEmail($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            $token = $this->generateJWT($user);

            session_start();
            $_SESSION['jwt_token'] = $token;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_username'] = $user['username'];

            http_response_code(200);
            echo json_encode([
                "message" => "Login successful",
                "token" => $token
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Invalid credentials"]);
        }
    }

    private function generateJWT($user) {
        $payload = [
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "data" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "username" => $user['username']
            ]
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }


    public function showLoginPage() {
        $view = new View('login');
        $view->assign('title', 'Connexion - AuthSystem');
        $view->render();
    }

    public function showRegisterPage() {
        $view = new View('register');
        $view->assign('title', 'Inscription - AuthSystem');
        $view->render();
    }

    public function showHomePage() {
        $view = new View('home');
        $view->assign('title', 'Accueil - AuthSystem');
        $view->render();
    }

    public function showDashboard($userData) {
        $products = $this->productService->findAllMe($userData->id);
        $productsArray = array_map(function($product) {
            return $product->toArray();
        }, $products);

        $totalProducts = $this->productService->totalProduct($userData->id);
        $percentageProductUp = $this->productService->percentageProductUp($userData->id);

        $view = new View('dashboard');
        $view->assign('title', 'Dashboard - AuthSystem');
        $view->assign('user', $userData);
        $view->assign('products', $productsArray);
        $view->assign('totalProducts', $totalProducts);
        $view->assign('percentageProductUp', $percentageProductUp);
        $view->disableLayout();
        $view->render();
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login-registration-with-jwt/login');
        exit();
    }
}