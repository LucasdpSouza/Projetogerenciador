<?php

// Verifica se a sessão ainda não foi iniciada, e a inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado, verificando a existência de 'user_id' na sessão
// Caso não esteja logado, redireciona para a página de login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
require __DIR__ . '/connect.php';

// Obtém o ID do usuário logado da sessão
$user_id = $_SESSION['user_id'];

// Prepara uma consulta SQL para buscar as tarefas do usuário logado
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id); // Vincula o ID do usuário logado
$stmt->execute(); // Executa a consulta
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém as tarefas como um array associativo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gerenciador de Tarefas</title>
</head>
<body>
<div class="container">

    <!-- Cabeçalho com título e botão de logout -->
    <div class="header">
        <h1>Gerenciador de Tarefas</h1>
        <button class="logout-button" onclick="openModal()">Logout</button>

<div class="modal" id="logoutModal">
    <div class="modal-content">
        <h3>O que você deseja fazer?</h3>
        <button class="btn-logout" onclick="window.location.href='logout.php'">Sair da Conta</button>
        <button class="btn-delete" onclick="window.location.href='deletar_conta.php'">Excluir Conta</button>
    </div>
</div>

    </div>

    <!-- Exibição de mensagens de sucesso ou erro -->
    <?php if (isset($_SESSION['success'])): ?>
        <!-- Mensagem de sucesso -->
        <div class="alert_success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <!-- Mensagem de erro -->
        <div class="alert_error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Formulário para adicionar novas tarefas -->
    <div class="form">
        <form action="task.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="insert" value="insert"> <!-- Campo oculto para indicar operação de inserção -->
            <label for="task_name">Tarefa:</label>
            <input type="text" name="task_name" placeholder="Nome da tarefa" required> <!-- Campo obrigatório -->
            <label for="task_description">Descrição:</label>
            <input type="text" name="task_description" placeholder="Descrição da tarefa" required> <!-- Campo obrigatório -->
            <label for="task_date">Data:</label>
            <input type="date" name="task_date" required> <!-- Campo obrigatório -->
            <label for="task_image">Imagem:</label>
            <input type="file" name="task_image"> <!-- Opcional -->
            <button type="submit">Cadastrar</button> <!-- Botão para enviar o formulário -->
        </form>
    </div>

    <div class="separator"></div> <!-- Separador visual -->

    <!-- Lista de tarefas do usuário -->
    <div class="list-tasks">
        <ul>
            <?php foreach ($tarefas as $task): ?>
                <!-- Exibe o nome da tarefa como link para a página de detalhes -->
                <li>
                    <a href="details.php?key=<?php echo $task['id']; ?>"><?php echo $task['task_name']; ?></a>
                    <!-- Botão para remover a tarefa, chamando a função deleteTask -->
                    <button type="button" class="btn-clear" onclick="deleteTask(<?php echo $task['id']; ?>)">Remover</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Script para confirmar exclusão de tarefas -->
    <script>
        function deleteTask(taskId) {
            // Exibe uma caixa de confirmação antes de excluir
            if (confirm('Confirmar remoção?')) {
                // Redireciona para a URL com o ID da tarefa a ser excluída
                window.location = `task.php?key=${taskId}`;
            }
        }
    </script>
 
     <!-- Rodapé da página -->
    <div class="footer">
        <p>Desenvolvido por Lucas e Alanna </p>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('logoutModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>




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
