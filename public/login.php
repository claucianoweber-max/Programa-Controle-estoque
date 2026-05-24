<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require '../database/database.php';
require '../src/Auth.php';

$db = new Database();
$pdo = $db->conectar();

$auth = new Auth($pdo);

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $resultado = $auth->login($email, $senha);

    if ($resultado === true) {
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = $resultado;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login - Controle de Estoque V1.0</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
}

.login-container {
    height: 100vh;
}

.card {
    border-radius: 15px;
    border: none;
}

.card h3 {
    font-weight: bold;
}

.btn-primary {
    background: #667eea;
    border: none;
}

.btn-primary:hover {
    background: #5a67d8;
}

.form-control {
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center login-container">

    <div class="col-md-4">
        <div class="card shadow p-4">

            <h3 class="text-center mb-4">🔐 Login</h3>

            <form method="POST">

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Digite seu email" required>
                </div>

                <div class="mb-3">
                    <label>Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Entrar</button>

            </form>

            <?php if ($erro): ?>
                <div class="alert alert-danger mt-3 text-center">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>