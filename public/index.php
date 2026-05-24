<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("refresh:1;url=dashboard.php");
} else {
    header("refresh:1;url=login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque V1.0</title>
</head>
<body style="text-align:center; margin-top:100px;">
    <h2>Carregando sistema...</h2>
</body>
</html>