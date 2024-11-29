<?php
// Inicia a sessão para verificar o usuário logado
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o ID do usuário logado
    $userId = $_SESSION['user_id'];

    // Exclui o usuário do banco de dados
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Destroi a sessão e redireciona para a página de login
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-cancel {
            background-color: gray;
            color: white;
            border: none;
        }
        .btn-delete {
            background-color: red;
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>Tem certeza de que deseja excluir sua conta?</h1>
    <p>Esta ação é irreversível e todos os seus dados serão perdidos.</p>
    <form method="POST">
        <button class="btn btn-delete" type="submit">Sim, Excluir Minha Conta</button>
        <a href="index.php" class="btn btn-cancel">Cancelar</a>
    </form>
</body>
</html>
