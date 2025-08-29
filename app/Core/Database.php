<?php
namespace App\Core;

use PDO;
use RuntimeException;

class Database
{
    /** @var PDO|null */
    private static $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=127.0.0.1;port=3307;dbname=clients_app;charset=utf8mb4",
                    "root",   // usuario
                    ""        // contraseña
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                throw new RuntimeException("DB connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
