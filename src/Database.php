<?php

namespace App;

use PDO;
use PDOException;

/**
 * Classe para gerenciar a conexão com o DB.
 * @package App
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
class Database {

    //teoricamente colocar no CONFIG.php
    const
        SERVER = "db",
        USER = "user",
        DB = "maga_project",
        PASSWORD = "21041998";

    /**
     * Cria uma nova conexão com o BD.
     * @return PDO|false
     */
    public static function getConnection() {
        try {
            // Configurações de conexão para o PostgreSQL
            $dsn = "pgsql:host=" . self::SERVER . ";dbname=" . self::DB;
            $connection = new PDO($dsn, self::USER, self::PASSWORD);

            // Define o modo de erro do PDO para lançar exceções
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $connection;
        } catch (PDOException $e) {
            // Captura exceções do PDO
            die("Erro na conexão: " . $e->getMessage());
        }
    }
}
