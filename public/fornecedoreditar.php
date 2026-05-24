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

// 1. Buscar dados do fornecedor (GET)
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    die("Fornecedor não encontrado.");
}

// 2. Processar o salvamento (POST)
if (isset($_POST['atualizar'])) {
    $id = (int) $_POST['id'];
    $nome = trim($_POST['nome']);
    $contato = trim($_POST['contato']);

    if (!empty($nome)) {
        $sql = "UPDATE fornecedores SET nome = ?, contato = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nome, $contato, $id])) {
            header("Location: fornecedor.php?msg=sucesso");
            exit;
        } else {
            $erro = "Erro ao atualizar fornecedor.";
        }
    } else {
        $erro = "O nome do fornecedor não pode estar vazio.";
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
    <div class="col-md-6 col-lg-5">
        <div class="card-custom">
            <h3 class="mb-4 text-center">✏️ Editar Fornecedor</h3>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-danger py-2 text-center"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?= e($dados['id']) ?>">
                
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="badge-title">Nome / Razão Social</label>
                        <input name="nome" class="form-control" value="<?= e($dados['nome']) ?>" required autofocus>
                    </div>

                    <div class="col-md-12">
                        <label class="badge-title">Contato (Telefone/E-mail)</label>
                        <input name="contato" class="form-control" value="<?= e($dados['contato']) ?>">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button name="atualizar" class="btn btn-primary w-100">💾 Salvar Alterações</button>
                    <a href="fornecedor.php" class="btn btn-secondary w-100">⬅ Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>