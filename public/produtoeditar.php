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

// 1. Processar o salvamento (POST)
if (isset($_POST['atualizar'])) {
    $id = (int) $_POST['id'];
    $nome = $_POST['nome'];
    $categoria = (int) $_POST['categoria'];
    $fornecedor = (int) $_POST['fornecedor'];
    $quantidade = (int) $_POST['quantidade'];
    $preco = (float) str_replace(',', '.', $_POST['preco']);

    if ($produto->atualizar($id, $nome, $categoria, $fornecedor, $quantidade, $preco)) {
        header("Location: produto.php?msg=sucesso");
        exit;
    }
}

// 2. Buscar dados para preencher o formulário (GET)
$id = (int) ($_GET['id'] ?? 0);
$dados = $produto->buscar($id);

if (!$dados) {
    die("Produto não encontrado.");
}

// 3. Listas para os selects
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
        .badge-title { font-size: 14px; color: #94a3b8; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8">
        <div class="card-custom">
            <h3 class="mb-4 text-center">✏️ Editar Produto</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= e($dados['id']) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="badge-title">Nome do Produto</label>
                        <input name="nome" class="form-control" value="<?= e($dados['nome']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="badge-title">Quantidade</label>
                        <input type="number" name="quantidade" class="form-control" value="<?= e($dados['quantidade']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="badge-title">Preço</label>
                        <input name="preco" class="form-control" value="<?= e($dados['preco']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="badge-title">Categoria</label>
                        <select name="categoria" class="form-select">
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= e($c['id']) ?>" <?= $dados['categoria_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= e($c['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="badge-title">Fornecedor</label>
                        <select name="fornecedor" class="form-select" required>
                            <?php foreach ($fornecedores as $f): ?>
                                <option value="<?= e($f['id']) ?>" <?= $dados['fornecedor_id'] == $f['id'] ? 'selected' : '' ?>>
                                    <?= e($f['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button name="atualizar" class="btn btn-primary w-100">💾 Salvar Alterações</button>
                    <a href="produto.php" class="btn btn-secondary w-100">⬅ Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>