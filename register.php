<?php

// Inclui o arquivo de conexão com o banco de dados
require 'connect.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username'])); // Sanitiza o nome de usuário
    $password = trim($_POST['password']); // Sanitiza a senha

    // Gera o hash da senha para armazenamento seguro
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepara uma consulta SQL para inserir o novo usuário
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    if ($stmt->execute([$username, $hashedPassword])) {
        // Redireciona para a página de login após o registro bem-sucedido
        header("Location: login.php");
        exit;
    } else {
        // Define uma mensagem de erro caso ocorra algum problema
        $error = "Erro ao criar conta. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade para dispositivos móveis -->
    <title>Registro de Usuário</title> <!-- Título da página -->
    <link rel="stylesheet" href="style.css"> <!-- Link para o arquivo de estilo CSS -->
    <script src="https://kit.fontawesome.com/ee747140e4.js" crossorigin="anonymous"></script> <!-- Biblioteca de ícones -->
</head>
<body>
    <!-- Título com ícone no topo da página -->
    <div style="text-align: center; margin-top: 80px;">
        <div class="container" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <!-- Ícone ao lado esquerdo -->
            <i class="fas fa-folder-open" style="font-size: 24px; color: #4a4a4a;"></i>
            <!-- Título -->
            <h1 style="font-family: Arial, sans-serif; color: #4a4a4a; margin: 0;">Gerenciador de Tarefas</h1>
        </div>
    </div>

    <!-- Formulário de registro -->
    <div class="container">
        <h2 class="header">Registro de Usuário</h2> <!-- Cabeçalho do formulário -->
        <div class="form">
            <form method="POST" action="register.php"> <!-- O formulário envia os dados para este arquivo -->
                <label for="username">Usuário:</label>
                <input type="text" name="username" placeholder="Usuário" required> <!-- Campo obrigatório para o nome de usuário -->

                <label for="password">Senha:</label>
                <input type="password" name="password" placeholder="Senha" required> <!-- Campo obrigatório para a senha -->

                <button type="submit">Registrar</button> <!-- Botão de envio -->
            </form>
            <?php 
            // Exibe a mensagem de erro, se houver
            if (isset($error)) { 
                echo "<p class='alert_error'>$error</p>"; 
            } 
            ?>
        </div>

        <!-- Botão para a página de login -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="login.php" style="text-decoration: none;">
                <button style="background-color: #6a0dad; color: white; border: none; padding: 10px 20px; font-size: 13px; border-radius: 5px; cursor: pointer;">
                    Já tem uma conta? Faça login
                </button>
            </a>
        </div>
    </div>
</body>
<div class="bubbles">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
</html>
