<?php
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../src/Produto.php'; 
require_once '../config/init.php';
require_once '../src/helpers.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();

$categorias = listarCategorias($pdo);
$fornecedores = listarFornecedores($pdo);

$produto = new Produto($pdo);

// ❌ Excluir
if (isset($_GET['excluir'])) {
    $produto->excluir((int)$_GET['excluir']);
    header("Location: " . BASE_URL . "produtos.php?msg=sucesso");
    exit;
}

// 🔍 Busca
$busca = $_GET['busca'] ?? '';

// 📄 PAGINAÇÃO
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($pagina < 1) $pagina = 1;

$offset = ($pagina - 1) * $limite;

// 📊 TOTAL DE REGISTROS
if (!empty($busca)) {
    $stmtTotal = $pdo->prepare("
        SELECT COUNT(*)
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
        WHERE p.nome LIKE ? 
           OR f.nome LIKE ? 
           OR c.nome LIKE ?
    ");
    $stmtTotal->execute(["%$busca%", "%$busca%", "%$busca%"]);
} else {
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM produtos");
}

$total = $stmtTotal->fetchColumn();
$totalPaginas = ceil($total / $limite);

// 📦 LISTAGEM COM PAGINAÇÃO
if (!empty($busca)) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome AS categoria, f.nome AS fornecedor
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
        WHERE p.nome LIKE ? 
           OR f.nome LIKE ? 
           OR c.nome LIKE ?
        ORDER BY p.id DESC
        LIMIT $limite OFFSET $offset
    ");
    $stmt->execute(["%$busca%", "%$busca%", "%$busca%"]);
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("
        SELECT p.*, c.nome AS categoria, f.nome AS fornecedor
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
        ORDER BY p.id DESC
        LIMIT $limite OFFSET $offset
    ");
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 Gestão de Produtos</h2>
        <div class="d-flex gap-2">
            <a href="movimentacaocontrole.php" class="btn btn-warning">
                🔄 Movimentar Estoque
            </a>
            <a href="produtonovo.php" class="btn btn-primary">
                ➕ Novo Produto
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            ✅ Operação realizada com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="busca" class="form-control"
                   placeholder="🔍 Buscar por produto, fornecedor ou categoria"
                   value="<?= htmlspecialchars($busca) ?>">
            <button class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-hover shadow-sm bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
            <tr>
                <td colspan="7" class="text-center text-muted p-4">Nenhum resultado encontrado 😢</td>
            </tr>
            <?php else: ?>
                <?php foreach ($lista as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                    <td>
                        <span class="badge <?= $p['quantidade'] <= 5 ? 'bg-danger' : 'bg-success' ?>">
                            <?= $p['quantidade'] ?>
                        </span>
                    </td>
                    <td><span class="badge bg-primary"><?= $p['categoria'] ?? '—' ?></span></td>
                    <td><span class="badge bg-secondary"><?= $p['fornecedor'] ?? '—' ?></span></td>
                    <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                    <td>
                        <a href="produtoeditar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="movimentacaocontrole.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info text-white">
            ↕️ Movimentar
        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if ($total > $limite): ?>

<nav class="d-flex justify-content-center mt-4">
    <ul class="pagination">

        <!-- ⬅ ANTERIOR -->
        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
            <a class="page-link"
               href="?pagina=<?= $pagina - 1 ?>&busca=<?= urlencode($busca) ?>">
                ⬅ Anterior
            </a>
        </li>

        <!-- INFO -->
        <li class="page-item disabled">
            <span class="page-link">
                Página <?= $pagina ?> de <?= $totalPaginas ?>
            </span>
        </li>
        

        <!-- ➡ PRÓXIMO -->
        <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link"
               href="?pagina=<?= $pagina + 1 ?>&busca=<?= urlencode($busca) ?>">
                Próximo ➡
            </a>
        </li>

    </ul>
</nav>

<?php endif; ?>

    <div class="mt-4 mb-5">
        <a href="dashboard.php" class="btn btn-secondary">⬅ Voltar ao Dashboard</a>
        <a href="movimentacao.php" class="btn btn-outline-info">📋 Ver Relatório Geral</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>