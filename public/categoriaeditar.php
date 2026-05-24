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

// 1. Buscar dados da categoria (GET)
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    die("Categoria não encontrada.");
}

// 2. Processar o salvamento (POST)
if (isset($_POST['atualizar'])) {
    $id = (int) $_POST['id'];
    $nome = $_POST['nome'];

    $sql = "UPDATE categorias SET nome = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$nome, $id])) {
        header("Location: categoria.php?msg=sucesso");
        exit;
    } else {
        $erro = "Erro ao atualizar categoria.";
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
    <div class="col-md-6 col-lg-5"> <!-- Reduzi um pouco a largura por ser apenas um campo -->
        <div class="card-custom">
            <h3 class="mb-4 text-center">✏️ Editar Categoria</h3>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-danger"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?= e($dados['id']) ?>">
                
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="badge-title">Nome da Categoria</label>
                        <input name="nome" class="form-control" value="<?= e($dados['nome']) ?>" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button name="atualizar" class="btn btn-primary w-100">💾 Salvar Alterações</button>
                    <a href="categoria.php" class="btn btn-secondary w-100">⬅ Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>