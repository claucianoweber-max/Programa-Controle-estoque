<?php
require '../config/database.php';
require '../config/init.php';

$stmt = $pdo->query("
    SELECT p.nome, p.quantidade, c.nome AS categoria
    FROM produtos p
    JOIN categorias c ON p.categoria_id = c.id
");

$dados = $stmt->fetchAll();
?>

<h2>Relatório de Estoque</h2>

<table border="1">
<tr>
    <th>Produto</th>
    <th>Categoria</th>
    <th>Quantidade</th>
</tr>

<?php foreach ($dados as $d): ?>
<tr>
    <td><?= $d['nome'] ?></td>
    <td><?= $d['categoria'] ?></td>
    <td><?= $d['quantidade'] ?></td>
</tr>
<?php endforeach; ?>
</table>