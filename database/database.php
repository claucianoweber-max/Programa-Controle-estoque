<?php

class Database {
    private $host = "localhost";
    private $db_name = "nome_do_seu_banco";
    private $username = "seu_usuario";
    private $password = "sua_senha_aqui";
    private $charset = "utf8mb4";

    public $pdo;

    public function conectar() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

            $this->pdo = new PDO($dsn, $this->username, $this->password);

            // Configurações importantes
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $this->pdo;

        } catch (PDOException $e) {
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }
}
