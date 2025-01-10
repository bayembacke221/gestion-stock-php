<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    public static function verifyToken() {
        session_start();

        if (!isset($_SESSION['jwt_token'])) {
            if (self::isAjaxRequest()) {
                http_response_code(401);
                echo json_encode(["message" => "Token required"]);
                exit();
            } else {
                header('Location: /login-registration-with-jwt/public/login');
                exit();
            }
        }

        try {
            $token = $_SESSION['jwt_token'];
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            session_destroy();

            if (self::isAjaxRequest()) {
                http_response_code(401);
                echo json_encode(["message" => "Invalid token: " . $e->getMessage()]);
                exit();
            } else {
                header('Location: /login-registration-with-jwt/public/login');
                exit();
            }
        }
    }

    private static function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
            (!empty($_SERVER['HTTP_ACCEPT']) &&
                str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }
}