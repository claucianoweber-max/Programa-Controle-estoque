<?php
session_start();

require '../database/database.php';
require '../src/Auth.php';
require '../src/Movimentacao.php';

//  Proteção
Auth::check();

// Conexão
$db = new Database();
$pdo = $db->conectar();

// Dados
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalCategorias = $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
$totalFornecedores = $pdo->query("SELECT COUNT(*) FROM fornecedores")->fetchColumn();
$totalMovimentacoes = $pdo->query("SELECT COUNT(*) FROM movimentacoes")->fetchColumn();
$totalMovimentacoes = $pdo->query("SELECT COUNT(*) FROM movimentacoes")->fetchColumn();
$totalUsuario = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchcolumn();

//  Produtos com estoque baixo (ex: <= 5)
$estoqueBaixo = $pdo->query("
    SELECT nome, quantidade 
    FROM produtos 
    WHERE quantidade <= 5
    ORDER BY quantidade ASC
")->fetchAll(PDO::FETCH_ASSOC);

//  Últimas movimentações
$movimentacoes = $pdo->query("
    SELECT m.tipo, m.quantidade, p.nome, m.data
    FROM movimentacoes m
    JOIN produtos p ON m.produto_id = p.id
    ORDER BY m.data DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

//  Dados para gráfico
$grafico = $pdo->query("
    SELECT tipo, SUM(quantidade) as total
    FROM movimentacoes
    GROUP BY tipo
")->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$dados = [];

foreach ($grafico as $g) {
    $labels[] = $g['tipo'];
    $dados[] = $g['total'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Controle de Estoque V1.0</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-height: 100vh;
}

.card {
    border-radius: 15px;
    border: none;
    color: #333;
}

.card h2 {
    font-weight: bold;
}

.menu-btn {
    border-radius: 12px;
    padding: 15px;
    font-weight: bold;
}

.header {
    color: white;
}
</style>
</head>

<body>

<div class="container py-5">

    <!-- TOPO -->
    <div class="d-flex justify-content-between align-items-center mb-4 header">
        <h2>📊 Painel de Controle</h2>
        <div>
            <span class="me-3">👤 <?php echo $_SESSION['user_email']; ?></span>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>
    <!-- MENU -->
    <div class="row text-center">

        <div class="col-md-3 mb-3">
            <a href="../public/produto.php" class="btn btn-primary w-100 menu-btn">
                📦 Produtos
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="../public/categoria.php" class="btn btn-success w-100 menu-btn">
                🗂️ Categorias
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="../public/fornecedor.php" class="btn btn-warning w-100 menu-btn">
                🚚 Fornecedores
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="../public/movimentacao.php" class="btn btn-danger w-100 menu-btn">
                🔄 Movimentações
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="../public/usuario.php" class="btn btn-danger w-100 menu-btn">
                👥 Usuarios
            </a>
        </div>

    </div>

    <!-- CARDS -->
     <div class="card shadow mb-4 p-3">
    <h5>⚠️ Estoque Baixo</h5>

    <?php if (count($estoqueBaixo) > 0): ?>
        <ul class="list-group">
            <?php foreach ($estoqueBaixo as $p): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?= $p['nome'] ?>
                    <span class="badge bg-danger"><?= $p['quantidade'] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-success">Tudo em estoque 👍</p>
    <?php endif; ?>
</div>
<br>
<div class="card shadow mb-4 p-3">
    <h5>📋 Últimas Movimentações</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Qtd</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentacoes as $m): ?>
            <tr>
                <td><?= $m['nome'] ?></td>
                <td><?= $m['tipo'] ?></td>
                <td><?= $m['quantidade'] ?></td>
                <td><?= $m['data'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<br>
<div class="card shadow p-3">
    <h5>Movimentações</h5>
    <canvas id="grafico"></canvas>
</div><br>
<script>
const ctx = document.getElementById('grafico');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Quantidade',
            data: <?= json_encode($dados) ?>,
        }]
    }
});
</script>
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center p-3">
                <h5>Produtos</h5>
                <h2><?= $totalProdutos ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center p-3">
                <h5>Categorias</h5>
                <h2><?= $totalCategorias ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center p-3">
                <h5>Fornecedores</h5>
                <h2><?= $totalFornecedores ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center p-3">
                <h5>Movimentações</h5>
                <h2><?= $totalMovimentacoes ?></h2>
            </div>
        </div>

         <div class="col-md-3 mb-3">
            <div class="card shadow text-center p-3">
                <h5>Usuário</h5>
                <h2><?= $totalUsuario ?></h2>
            </div>
        </div>

    </div>
</div>

</body>
</html>