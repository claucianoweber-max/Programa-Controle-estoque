<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];

    // 🔍 Buscar estoque atual
    $stmt = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        die("Produto não encontrado");
    }

    $estoqueAtual = $produto['quantidade'];

    // 📊 Calcular novo estoque
    if ($tipo === 'entrada') {
        $novoEstoque = $estoqueAtual + $quantidade;
    } else {
        $novoEstoque = $estoqueAtual - $quantidade;

        // 🚫 Não permitir estoque negativo
        if ($novoEstoque < 0) {
            die("Estoque insuficiente!");
        }
    }

    // 💾 Inserir movimentação
    $stmt = $pdo->prepare("
        INSERT INTO movimentacoes (produto_id, tipo, quantidade, data)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$produto_id, $tipo, $quantidade]);

    // 🔄 Atualizar estoque do produto
    $stmt = $pdo->prepare("
        UPDATE produtos SET quantidade = ? WHERE id = ?
    ");
    $stmt->execute([$novoEstoque, $produto_id]);

    echo "Movimentação registrada com sucesso!";
}
?>