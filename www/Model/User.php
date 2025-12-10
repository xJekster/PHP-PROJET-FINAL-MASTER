<?php

namespace App\Model;
use App\Core\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT id, password, is_active FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }


    public function register(string $firstname, string $lastname, string $email, string $password_hash, string $confirmation_token): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (firstname, lastname, email, password, token) VALUES (:firstname, :lastname, :email, :password, :token)");
        return $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password_hash,
            'token' => $confirmation_token
        ]);
    }


    public function emailExists(string $email): bool
        {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        }
    
        public function saveResetToken(string $email, string $token): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, reset_expires = NOW() + INTERVAL '1 hour' WHERE email = :email");
        return $stmt->execute(['token' => $token, 'email' => $email]);
    }
    
    public function getUserByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare("SELECT id, email FROM users WHERE reset_token = :token AND reset_expires > NOW()");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        return $user ?: null;
    }
    
    public function updatePassword(int $id, string $newHash): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
        return $stmt->execute(['password' => $newHash, 'id' => $id]);
    }


}