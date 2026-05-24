<?php
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../src/helpers.php';
require_once '../config/init.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();

// ❌ Excluir Fornecedor
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM fornecedores WHERE id = ?");
    
    try {
        $stmt->execute([$id]);
        header("Location: fornecedor.php?msg=excluido");
    } catch (PDOException $e) {
        // Erro caso existam produtos vinculados a este fornecedor
        header("Location: fornecedor.php?msg=erro_vinculo");
    }
    exit;
}

// 🔍 Busca por Nome ou CNPJ/Contato
$busca = $_GET['busca'] ?? '';

if (!empty($busca)) {
    $stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE nome LIKE ? OR contato LIKE ? ORDER BY nome ASC");
    $stmt->execute(["%$busca%", "%$busca%"]);
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Caso não tenha uma função específica no helpers, usamos uma query simples
    $stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY nome ASC");
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; min-height: 100vh; }
        .card-custom { background: #1e293b; border-radius: 15px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .table { color: #fff; border-color: #334155; }
        .table-dark { --bs-table-bg: #0f172a; }
        .form-control { background: #0f172a; border: 1px solid #334155; color: #fff; }
        .form-control:focus { background: #0f172a; border-color: #3b82f6; color: #fff; box-shadow: none; }
        .btn-primary { background: #3b82f6; border: none; }
        .btn-danger { background: #ef4444; border: none; }
        .btn-warning { background: #f59e0b; border: none; color: #000; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card-custom">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🚚 Fornecedores</h2>
            <a href="fornecedornovo.php" class="btn btn-primary">➕ Novo Fornecedor</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'excluido'): ?>
                <div class="alert alert-success">✅ Fornecedor removido com sucesso!</div>
            <?php elseif ($_GET['msg'] == 'erro_vinculo'): ?>
                <div class="alert alert-danger">⚠️ Erro: Existem produtos vinculados a este fornecedor.</div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="busca" class="form-control" 
                       placeholder="🔍 Buscar por nome ou contato..." value="<?= htmlspecialchars($busca) ?>">
                <button class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th width="5%">ID</th>
                    <th>Nome / Razão Social</th>
                    <th>Nome Contato / Telefone</th>
                    <th width="20%" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nenhum fornecedor encontrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lista as $forn): ?>
                    <tr>
                        <td><?= $forn['id'] ?></td>
                        <td><strong><?= htmlspecialchars($forn['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($forn['contato'] ?? 'N/A') ?></td>
                        <td class="text-center">
                            <a href="fornecedoreditar.php?id=<?= $forn['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            
                            <a href="javascript:void(0)" 
                               class="btn btn-sm btn-danger" 
                               style="opacity: 0.4; pointer-events: none;"
                               title="Exclusão desativada nesta vista">
                               Excluir
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Voltar ao Painel de controle</a>
            <a href="produto.php" class="btn btn-outline-light">📦 Ver Produtos</a>
        </div>
    </div>
</div>

</body>
</html>