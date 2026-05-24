<?php
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../src/Produto.php';
require_once '../config/init.php';
require_once '../src/helpers.php';
Auth::check();
class Produto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $sql = "SELECT 
                    p.*, 
                    c.nome AS categoria, 
                    f.nome AS fornecedor
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
                ORDER BY p.id DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Renomeado para 'buscar' para coincidir com o que você usou no seu arquivo de edição
    public function buscar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }

    public function atualizar($id, $nome, $categoria_id, $fornecedor_id, $quantidade, $preco) {
        try {
            $sql = "UPDATE produtos SET 
                        nome = :nome, 
                        categoria_id = :categoria, 
                        fornecedor_id = :fornecedor, 
                        quantidade = :quantidade, 
                        preco = :preco 
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute([
                ':nome'        => $nome,
                ':categoria'   => $categoria_id,
                ':fornecedor'  => $fornecedor_id,
                ':quantidade'  => (int)$quantidade,
                ':preco'       => (float)$preco,
                ':id'          => (int)$id
            ]);
        } catch (PDOException $e) {
            die("Erro no banco de dados: " . $e->getMessage());
        }
    }
    public function criar($nome, $categoria_id, $fornecedor_id, $quantidade, $preco) {
    $sql = "INSERT INTO produtos (nome, categoria_id, fornecedor_id, quantidade, preco) 
            VALUES (:nome, :categoria, :fornecedor, :quantidade, :preco)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':nome'       => $nome,
        ':categoria'  => $categoria_id,
        ':fornecedor' => $fornecedor_id,
        ':quantidade' => $quantidade,
        ':preco'      => $preco
    ]);
}

}