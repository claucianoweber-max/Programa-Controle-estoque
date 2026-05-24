<?php
session_start();
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../src/helpers.php';
require_once '../config/init.php';

Auth::check();

Auth::check();

$id_logado = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? $_SESSION['user_id'] ?? 0;

$db = new Database();
$pdo = $db->conectar();

// ❌ Excluir Usuário
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    
    // Impede que o usuário logado exclua a si próprio
    if ($id === $_SESSION['usuario_id']) {
        header("Location: usuario.php?msg=erro_autoexclusao");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    try {
        $stmt->execute([$id]);
        header("Location: usuario.php?msg=excluido");
    } catch (PDOException $e) {
        header("Location: usuario.php?msg=erro_vinculo");
    }
    exit;
}

// 🔍 Busca por Nome ou Email
$busca = $_GET['busca'] ?? '';

if (!empty($busca)) {
    $stmt = $pdo->prepare("SELECT id, nomeusuario, email, failed_attempts, lock_until FROM usuarios WHERE nomeusuario LIKE ? OR email LIKE ? ORDER BY nomeusuario ASC");
    $stmt->execute(["%$busca%", "%$busca%"]);
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("SELECT id, nomeusuario, email, failed_attempts, lock_until FROM usuarios ORDER BY nomeusuario ASC");
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
        .table { color: #fff; border-color: #334155; vertical-align: middle; }
        .table-dark { --bs-table-bg: #0f172a; }
        .form-control { background: #0f172a; border: 1px solid #334155; color: #fff; }
        .form-control:focus { background: #0f172a; border-color: #3b82f6; color: #fff; box-shadow: none; }
        .btn-primary { background: #3b82f6; border: none; }
        .btn-danger { background: #ef4444; border: none; }
        .btn-warning { background: #f59e0b; border: none; color: #000; }
        .badge-locked { background-color: #ef4444; color: #fff; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card-custom">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>👥 Usuários do Sistema</h2>
            <a href="usuarionovo.php" class="btn btn-primary">➕ Novo Usuário</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-dismissible fade show <?php 
                echo in_array($_GET['msg'], ['excluido']) ? 'alert-success' : 'alert-danger'; 
            ?>">
                <?php
                    if ($_GET['msg'] == 'excluido') echo "✅ Usuário removido com sucesso!";
                    if ($_GET['msg'] == 'erro_autoexclusao') echo "⚠️ Você não pode excluir sua própria conta logada.";
                    if ($_GET['msg'] == 'erro_vinculo') echo "⚠️ Erro: Existem registros (ex: movimentações) vinculados a este usuário.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="busca" class="form-control" 
                       placeholder="🔍 Buscar por nome ou e-mail..." value="<?= htmlspecialchars($busca) ?>">
                <button class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th width="5%">ID</th>
                    <th>Nome de Usuário</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th width="20%" class="text-center">Ações</th>
                </tr>
            </thead>
        <tbody>
    <?php if (empty($lista)): ?>
        <tr>
            <td colspan="5" class="text-center text-muted p-4">Nenhum usuário encontrado.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($lista as $u): 
            $is_locked = ($u['lock_until'] && strtotime($u['lock_until']) > time());
        ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><strong><?= htmlspecialchars($u['nomeusuario']) ?></strong></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
                <?php if ($is_locked): ?>
                    <span class="badge badge-locked">Bloqueado até <?= date('d/H:i', strtotime($u['lock_until'])) ?></span>
                <?php else: ?>
                    <span class="badge bg-success">Ativo</span>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <a href="usuarioeditar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                
                <?php if ($u['id'] == $id_logado): ?>
                    <button class="btn btn-sm btn-danger opacity-50" title="Você não pode excluir seu próprio usuário" disabled>
                        Excluir
                    </button>
                <?php else: ?>
                    <a href="?excluir=<?= $u['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Tem certeza que deseja excluir o usuário <?= htmlspecialchars($u['nomeusuario']) ?>?')">
                        Excluir
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
        </table>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Voltar ao Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>