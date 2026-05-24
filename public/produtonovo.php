<?php
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../src/Produto.php'; 
require_once '../config/init.php';
require_once '../src/helpers.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();
$produto = new Produto($pdo);

// Função para sanitizar HTML
function e($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

// 1. Processar o cadastro (POST)
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $categoria = (int) $_POST['categoria'];
    $fornecedor = (int) $_POST['fornecedor'];
    $quantidade = (int) $_POST['quantidade'];
    $preco = (float) str_replace(',', '.', $_POST['preco']);

    // Certifique-se de que o método criar() existe na sua classe Produto
    if ($produto->criar($nome, $categoria, $fornecedor, $quantidade, $preco)) {
        header("Location: produto.php?msg=sucesso");
        exit;
    }
}

// 2. Listas para os selects
$categorias = listarCategorias($pdo);
$fornecedores = listarFornecedores($pdo);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; }
        .card-custom { background: #1e293b; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .form-control, .form-select { background: #0f172a; border: 1px solid #334155; color: #fff; }
        .form-control:focus, .form-select:focus { background: #0f172a; border-color: #3b82f6; box-shadow: none; color: #fff; }
        .btn-primary { background: #3b82f6; border: none; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #64748b; border: none; }
        .badge-title { font-size: 14px; color: #94a3b8; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8">
        <div class="card-custom">
            <h3 class="mb-4 text-center">📦 Novo Produto</h3>
            <form method="POST">
                <div class="row g-3">
                    <!-- Nome: 50% da largura (col-md-6) -->
                    <div class="col-md-6">
                        <label class="badge-title">Nome do Produto</label>
                        <input name="nome" class="form-control" placeholder="Digite o nome..." required>
                    </div>
                    
                    <!-- Quantidade: 25% da largura (col-md-3) -->
                    <div class="col-md-3">
                        <label class="badge-title">Quantidade</label>
                        <input type="number" name="quantidade" class="form-control" value="0" required>
                    </div>
                    
                    <!-- Preço: 25% da largura (col-md-3) -->
                    <div class="col-md-3">
                        <label class="badge-title">Preço</label>
                        <input name="preco" class="form-control" placeholder="0,00" required>
                    </div>
                    
                    <!-- Categoria: 50% da largura (col-md-6) -->
                    <div class="col-md-6">
                        <label class="badge-title">Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= e($c['id']) ?>"><?= e($c['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Fornecedor: 50% da largura (col-md-6) -->
                    <div class="col-md-6">
                        <label class="badge-title">Fornecedor</label>
                        <select name="fornecedor" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($fornecedores as $f): ?>
                                <option value="<?= e($f['id']) ?>"><?= e($f['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button name="cadastrar" class="btn btn-primary w-100">💾 Salvar Produto</button>
                    <a href="produto.php" class="btn btn-secondary w-100">⬅ Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>