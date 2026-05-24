<?php
session_start();
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../config/init.php';
require_once '../src/helpers.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();

function e($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

$erro = "";
$sucesso = "";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: usuario.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, nomeusuario, email FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) {
    header("Location: usuario.php?msg=nao_encontrado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
    $nomeusuario = trim($_POST['nomeusuario']);
    // Mantemos o email original para segurança, já que está em readonly
    $email = $u['email']; 
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    try {
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE nomeusuario = ? AND id != ?");
        $stmtCheck->execute([$nomeusuario, $id]);
        
        if ($stmtCheck->fetch()) {
            $erro = "Este nome de usuário já está em uso.";
        } else {
            if (!empty($senha)) {
                if ($senha !== $confirmar_senha) {
                    $erro = "As senhas não coincidem!";
                } elseif (strlen($senha) < 6) {
                    $erro = "A nova senha deve ter pelo menos 6 caracteres.";
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $sql = "UPDATE usuarios SET nomeusuario = ?, senha = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nomeusuario, $senha_hash, $id]);
                    $sucesso = "Usuário e senha atualizados com sucesso!";
                }
            } else {
                $sql = "UPDATE usuarios SET nomeusuario = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nomeusuario, $id]);
                $sucesso = "Dados atualizados com sucesso!";
            }
            $u['nomeusuario'] = $nomeusuario;
        }
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; min-height: 100vh; display: flex; align-items: center; }
        .card-custom { background: #1e293b; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .form-control { background: #0f172a; border: 1px solid #334155; color: #fff; }
        .form-control:focus { background: #0f172a; border-color: #3b82f6; box-shadow: none; color: #fff; }
        .form-control[readonly] { background: #161e2d; color: #94a3b8; cursor: not-allowed; border-style: dashed; }
        .btn-primary { background: #3b82f6; border: none; }
        .btn-secondary { background: #64748b; border: none; }
        .badge-title { font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .link-topo { color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .link-topo:hover { color: #3b82f6; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="mb-3">
                <a href="usuario.php" class="link-topo">⬅️ Voltar para a lista de usuários</a>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-3">⚠️ <?= $erro ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert alert-success border-0 shadow-sm mb-3">✅ <?= $sucesso ?></div>
            <?php endif; ?>

            <div class="card-custom">
                <h3 class="mb-4 text-center">✏️ Editar Perfil</h3>
                
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="badge-title">Nome de Usuário</label>
                            <input name="nomeusuario" class="form-control" required value="<?= e($u['nomeusuario']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="badge-title">E-mail (Identificador)</label>
                            <input type="email" class="form-control" value="<?= e($u['email']) ?>" readonly title="O e-mail não pode ser alterado para garantir a integridade do login">
                        </div>
                        
                        <hr class="my-4" style="border-color: #334155;">
                        <p class="text-center text-muted mb-2" style="font-size: 14px;">Segurança: Alterar Senha</p>

                        <div class="col-md-6">
                            <label class="badge-title">Nova Senha</label>
                            <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="badge-title">Confirmar Senha</label>
                            <input type="password" name="confirmar_senha" class="form-control" placeholder="Repita a nova senha">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button name="atualizar" type="submit" class="btn btn-primary w-100 py-2 fw-bold">💾 Salvar Alterações</button>
                        <a href="usuario.php" class="btn btn-secondary w-100 py-2">Cancelar</a>
                    </div>
                </form>
            </div>
            
            <p class="text-center mt-3 text-muted" style="font-size: 12px;">Editando Usuário ID: #<?= $u['id'] ?></p>
        </div>
    </div>
</div>
</body>
</html>