<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    
    private $host = 'db'; 
    private $db_name = 'devdb'; 
    private $username = 'devuser'; 
    private $password = 'devpass'; 
    private $port = '5432'; 

    private function __construct()
    {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            
            die("Erreur de connexion à la base de données: " . $exception->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

 
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    
    private function __clone() {}

    
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}
