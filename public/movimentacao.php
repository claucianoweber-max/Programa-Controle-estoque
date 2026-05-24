<?php
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../config/init.php';
require_once '../src/helpers.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();

$busca = $_GET['busca'] ?? '';

// SQL AJUSTADO: u.nomeusuario (da tabela usuarios) e m.usuarioid (da tabela movimentacoes)
$sql = "SELECT m.id, m.tipo, m.quantidade, m.data, 
               p.nome AS produto_nome, 
               u.nomeusuario AS operador
        FROM movimentacoes m
        INNER JOIN produtos p ON m.produto_id = p.id
        LEFT JOIN usuarios u ON m.usuarioid = u.id"; 

try {
    if (!empty($busca)) {
        $sql .= " WHERE p.nome LIKE ? OR u.nomeusuario LIKE ? ORDER BY m.data DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$busca%", "%$busca%"]);
    } else {
        $sql .= " ORDER BY m.data DESC";
        $stmt = $pdo->query($sql);
    }
    $movimentacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro Crítico no Sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .entrada { border-left: 5px solid #28a745; }
        .saida { border-left: 5px solid #dc3545; }
        .badge-user { background-color: #f8f9fa; color: #333; border: 1px solid #ddd; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Relatório de Movimentação</h2>
        <a href="produto.php" class="btn btn-secondary">⬅ Voltar</a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="busca" class="form-control" 
                           placeholder="Buscar por produto ou usuário..." 
                           value="<?= htmlspecialchars($busca) ?>">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Pesquisar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Tipo</th>
                    <th>Qtd</th>
                    <th>Usuário (Operador)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($movimentacoes)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted p-4">Nenhum registro encontrado.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($movimentacoes as $m): ?>
                    <tr class="<?= $m['tipo'] == 'entrada' ? 'entrada' : 'saida' ?>">
                        <td><?= date('d/m/Y H:i', strtotime($m['data'])) ?></td>
                        <td><strong><?= htmlspecialchars($m['produto_nome']) ?></strong></td>
                        <td>
                            <?php if ($m['tipo'] == 'entrada'): ?>
                                <span class="badge bg-success">ENTRADA</span>
                            <?php else: ?>
                                <span class="badge bg-danger">SAÍDA</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?= $m['quantidade'] ?></td>
                        <td>
                            <span class="badge badge-user">
                                👤 <?= htmlspecialchars($m['operador'] ?? 'Desconhecido') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>