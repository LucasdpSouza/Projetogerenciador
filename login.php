<?php

// Inclui o arquivo de conexão com o banco de dados
require 'connect.php';

// Inicia a sessão para gerenciar o login do usuário
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Verifica se o método da requisição é POST (envio do formulário de login)

    $username = htmlspecialchars(trim($_POST['username'])); // Sanitiza o nome de usuário enviado pelo formulário
    $password = trim($_POST['password']); // Sanitiza a senha enviada pelo formulário

    // Prepara uma consulta SQL para buscar o usuário pelo nome
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]); // Executa a consulta com o nome de usuário fornecido
    $user = $stmt->fetch(); // Recupera os dados do usuário como um array associativo

    if ($user && password_verify($password, $user['password'])) { 
        // Verifica se o usuário foi encontrado e se a senha fornecida é válida

        $_SESSION['user_id'] = $user['id']; // Salva o ID do usuário na sessão para autenticação
        header("Location: index.php"); // Redireciona para a página principal
        exit; // Encerra o script após o redirecionamento
    } else {
        // Caso as credenciais sejam inválidas, define uma mensagem de erro
        $error = "Usuário ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade para dispositivos móveis -->
    <title>Login de Usuário</title> <!-- Título da página -->
    <link rel="stylesheet" href="style.css">
 <!-- Link para o arquivo de estilo CSS -->
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

    <!-- Formulário de login -->
    <div class="container">
        <h2 class="header">Login de Usuário</h2> <!-- Cabeçalho do formulário -->
        <div class="form">
            <form method="POST" action="login.php"> <!-- O formulário envia os dados para este arquivo -->
                <label for="username">Usuário:</label>
                <input type="text" name="username" placeholder="Usuário" required> <!-- Campo obrigatório para o nome de usuário -->

                <label for="password">Senha:</label>
                <input type="password" name="password" placeholder="Senha" required> <!-- Campo obrigatório para a senha -->

                <button type="submit">Login</button> <!-- Botão de envio -->
            </form>
            <?php 
            // Exibe a mensagem de erro, se houver
            if (isset($error)) { 
                echo "<p class='alert_error'>$error</p>"; 
            } 
            ?>
        </div>

        <!-- Botão para a página de registro -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="register.php" style="text-decoration: none;">
                <button style="background-color: #6a0dad; color: white; border: none; padding: 10px 20px; font-size: 13px; border-radius: 5px; cursor: pointer;">
                    Não tem uma conta? Registre-se
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
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
</html>
