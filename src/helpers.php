<?php

function listarCategorias($pdo) {
    $stmt = $pdo->query("SELECT id, nome FROM categorias");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function listarFornecedores($pdo) {
    $stmt = $pdo->query("SELECT id, nome FROM fornecedores");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>