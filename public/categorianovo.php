<?php
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

// 1. Processar o cadastro (POST)
if (isset($_POST['cadastrar'])) {
    $nome = trim($_POST['nome']);

    if (!empty($nome)) {
        $sql = "INSERT INTO categorias (nome) VALUES (?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nome])) {
            header("Location: categoria.php?msg=sucesso");
            exit;
        } else {
            $erro = "Erro ao cadastrar categoria.";
        }
    } else {
        $erro = "O nome da categoria não pode estar vazio.";
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
        body { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; }
        .card-custom { background: #1e293b; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .form-control { background: #0f172a; border: 1px solid #334155; color: #fff; }
        .form-control:focus { background: #0f172a; border-color: #3b82f6; box-shadow: none; color: #fff; }
        .btn-primary { background: #3b82f6; border: none; }
        .badge-title { font-size: 14px; color: #94a3b8; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6 col-lg-5"> <!-- Mantendo o tamanho exato da sua amostra -->
        <div class="card-custom">
            <h3 class="mb-4 text-center">📁 Nova Categoria</h3>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-danger py-2 text-center"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="badge-title">Nome da Categoria</label>
                        <input name="nome" class="form-control" placeholder="Ex: Eletrônicos" required autofocus>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button name="cadastrar" class="btn btn-primary w-100">💾 Salvar Categoria</button>
                    <a href="categoria.php" class="btn btn-secondary w-100">⬅ Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>