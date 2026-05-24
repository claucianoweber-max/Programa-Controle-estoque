<?php
session_start();
require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../config/init.php';
require_once '../src/helpers.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();

// Função para sanitizar HTML
function e($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

$erro = "";

// 1. Processar o cadastro (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $nomeusuario = trim($_POST['nomeusuario']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações básicas
    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        // Verificar se o e-mail ou nome de usuário já existem
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE nomeusuario = ? OR email = ?");
        $stmt->execute([$nomeusuario, $email]);
        
        if ($stmt->fetch()) {
            $erro = "Nome de usuário ou e-mail já estão em uso.";
        } else {
            // Criptografar a senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            try {
                $sql = "INSERT INTO usuarios (nomeusuario, email, senha) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nomeusuario, $email, $senha_hash]);
                
                header("Location: usuario.php?msg=sucesso");
                exit;
            } catch (PDOException $e) {
                $erro = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
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
        .btn-primary { background: #3b82f6; border: none; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #64748b; border: none; }
        .badge-title { font-size: 14px; color: #94a3b8; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 20px 0;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if ($erro): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    ⚠️ <?= $erro ?>
                </div>
            <?php endif; ?>

            <div class="card-custom">
                <h3 class="mb-4 text-center">👥 Novo Usuário</h3>
                
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="badge-title">Nome de Usuário (Login)</label>
                            <input name="nomeusuario" class="form-control" placeholder="Ex: joao.silva" required value="<?= isset($_POST['nomeusuario']) ? e($_POST['nomeusuario']) : '' ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="badge-title">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" required value="<?= isset($_POST['email']) ? e($_POST['email']) : '' ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="badge-title">Senha</label>
                            <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="badge-title">Confirmar Senha</label>
                            <input type="password" name="confirmar_senha" class="form-control" placeholder="Repita a senha" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button name="cadastrar" type="submit" class="btn btn-primary w-100 py-2 fw-bold">💾 Salvar Usuário</button>
                        <a href="usuario.php" class="btn btn-secondary w-100 py-2">⬅ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>