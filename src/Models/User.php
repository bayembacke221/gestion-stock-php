<?php

namespace App\Models;

class User {
    private $db;
    private $table = 'user';

    public function __construct() {
        $this->db = \App\Config\Database::getInstance()->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (username, email,name, password) VALUES (:username, :email,:name, :password)";
        $stmt = $this->db->prepare($query);

        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':password', $password_hash);

        return $stmt->execute();
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
