<?php
// Certifique-se de que session_start() é a primeira coisa no arquivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/database.php';
require_once '../src/Auth.php';
require_once '../config/init.php';
require_once '../src/helpers.php';

Auth::check();

$db = new Database();
$pdo = $db->conectar();

// Captura o ID vindo do botão "Movimentar" da página anterior
$produto_selecionado = $_GET['id'] ?? '';

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo']; 
    $quantidade = (int)$_POST['quantidade'];
    
    // CORREÇÃO DO ERRO: Tenta capturar o ID da sessão de diferentes formas comuns
    $usuarioid = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? null;

    if (!$usuarioid) {
        $erro = "Sessão inválida. Por favor, saia e faça login novamente.";
    } elseif ($quantidade <= 0) {
        $erro = "A quantidade deve ser maior que zero.";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. Alimenta a tabela movimentacoes
            $sqlMov = "INSERT INTO movimentacoes (produto_id, tipo, quantidade, data, usuarioid) 
                       VALUES (?, ?, ?, NOW(), ?)";
            $stmtMov = $pdo->prepare($sqlMov);
            $stmtMov->execute([$produto_id, $tipo, $quantidade, $usuarioid]);

            // 2. Atualiza o saldo na tabela produtos
            if ($tipo === 'entrada') {
                $sqlProd = "UPDATE produtos SET quantidade = quantidade + ? WHERE id = ?";
            } else {
                // Validação de estoque para saída
                $stmtCheck = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
                $stmtCheck->execute([$produto_id]);
                $atual = $stmtCheck->fetchColumn();

                if ($atual < $quantidade) {
                    throw new Exception("Estoque insuficiente! Saldo disponível: $atual");
                }
                $sqlProd = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?";
            }
            
            $stmtProd = $pdo->prepare($sqlProd);
            $stmtProd->execute([$quantidade, $produto_id]);

            $pdo->commit();
            $sucesso = "Movimentação de " . strtoupper($tipo) . " registrada com sucesso!";
            
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $erro = "Erro: " . $e->getMessage();
        }
    }
}

// Busca a lista atualizada de produtos para o Select
$stmt = $pdo->query("SELECT id, nome, quantidade FROM produtos ORDER BY nome ASC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-5">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 text-center">🔄 Registrar Movimentação</h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($erro): ?>
                        <div class="alert alert-danger border-0 shadow-sm"><?= $erro ?></div>
                    <?php endif; ?>

                    <?php if ($sucesso): ?>
                        <div class="alert alert-success border-0 shadow-sm">✅ <?= $sucesso ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Produto</label>
                            <select name="produto_id" class="form-select form-select-lg" required>
                                <option value="" disabled <?= empty($produto_selecionado) ? 'selected' : '' ?>>Selecione o item...</option>
                                <?php foreach ($produtos as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($produto_selecionado == $p['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nome']) ?> (Estoque: <?= $p['quantidade'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Tipo de Operação</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipo" id="entrada" value="entrada" checked>
                                <label class="btn btn-outline-success py-2 fw-bold" for="entrada">⬆ ENTRADA</label>

                                <input type="radio" class="btn-check" name="tipo" id="saida" value="saida">
                                <label class="btn btn-outline-danger py-2 fw-bold" for="saida">⬇ SAÍDA</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Quantidade a movimentar</label>
                            <input type="number" name="quantidade" class="form-control form-control-lg" min="1" required placeholder="Digite o valor">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Confirmar e Gravar</button>
                            <hr>
                            <a href="produto.php" class="btn btn-secondary">⬅ Lista de Produtos</a>
                            <a href="movimentacao.php" class="btn btn-link text-decoration-none text-muted">Ver Histórico Geral</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <div class="badge bg-white text-dark p-2 shadow-sm">
                    👤 Operador: <strong><?= htmlspecialchars($_SESSION['nomeusuario'] ?? 'Não Identificado') ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>