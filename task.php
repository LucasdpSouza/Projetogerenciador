<?php

// Inicia a sessão 
session_start();

// Inclui o arquivo de conexão com o banco de dados
require __DIR__ . '/connect.php';

// ------------------ INSERIR NOVA TAREFA ------------------ //
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['insert'])) { 
    // Verifica se a requisição é POST e se a operação é de inserção (campo oculto 'insert' no formulário)

    // Obtém os dados do formulário, sanitizando-os para evitar ataques XSS
    $task_name = htmlspecialchars($_POST['task_name'], ENT_QUOTES, 'UTF-8');
    $task_description = htmlspecialchars($_POST['task_description'], ENT_QUOTES, 'UTF-8');
    $task_date = $_POST['task_date']; // Data da tarefa
    $task_image = $_FILES['task_image']; // Arquivo enviado pelo usuário (imagem)
    $user_id = $_SESSION['user_id']; // ID do usuário logado (obtido da sessão)

    // ------------------ SALVAR IMAGEM ------------------ //
    $file_name = ''; // Inicializa o nome do arquivo como vazio
    if ($task_image['size'] > 0) { // Verifica se uma imagem foi enviada
        // Obtém a extensão do arquivo enviado
        $ext = strtolower(pathinfo($task_image['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; // Extensões permitidas

        if (in_array($ext, $allowed_extensions)) { 
            // Verifica se a extensão do arquivo é permitida
            $file_name = 'uploads/' . md5(uniqid()) . '.' . $ext; // Gera um nome único para o arquivo
            move_uploaded_file($task_image['tmp_name'], $file_name); // Salva a imagem no servidor
        } else {
            // Se a extensão não for permitida, exibe mensagem de erro e redireciona
            $_SESSION['error'] = "Formato de arquivo não permitido.";
            header("Location: index.php");
            exit;
        }
    }

    // ------------------ INSERIR TAREFA NO BANCO ------------------ //
    $stmt = $conn->prepare('INSERT INTO tasks (task_name, task_description, task_date, task_image, user_id) VALUES (:name, :description, :date, :image, :user_id)');
    $stmt->bindParam(':name', $task_name); // Vincula o nome da tarefa
    $stmt->bindParam(':description', $task_description); // Vincula a descrição
    $stmt->bindParam(':date', $task_date); // Vincula a data
    $stmt->bindParam(':image', $file_name); // Vincula o caminho da imagem
    $stmt->bindParam(':user_id', $user_id); // Vincula o ID do usuário

    if ($stmt->execute()) {
        // Se a execução foi bem-sucedida, define uma mensagem de sucesso
        $_SESSION['success'] = "Tarefa adicionada com sucesso!";
    } else {
        // Caso contrário, define uma mensagem de erro
        $_SESSION['error'] = "Erro ao adicionar a tarefa.";
    }
    header("Location: index.php"); // Redireciona para a página inicial
    exit;
}

// ------------------ EXCLUIR TAREFA ------------------ //
if (isset($_GET['key'])) { 
    // Verifica se foi fornecido um ID de tarefa (via query string)

    $task_id = $_GET['key']; // ID da tarefa a ser excluída
    $user_id = $_SESSION['user_id']; // ID do usuário logado

    // ------------------ OBTER CAMINHO DA IMAGEM ------------------ //
    $stmt = $conn->prepare('SELECT task_image FROM tasks WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $task_id); // Vincula o ID da tarefa
    $stmt->bindParam(':user_id', $user_id); // Vincula o ID do usuário
    $stmt->execute(); // Executa a consulta
    $image = $stmt->fetchColumn(); // Obtém o caminho da imagem

    if ($image && file_exists($image)) { 
        // Se a imagem existe no servidor, exclui o arquivo
        unlink($image);
    }

    // ------------------ EXCLUIR TAREFA DO BANCO ------------------ //
    $stmt = $conn->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $task_id); // Vincula o ID da tarefa
    $stmt->bindParam(':user_id', $user_id); // Vincula o ID do usuário

    if ($stmt->execute()) {
        // Se a exclusão foi bem-sucedida, define uma mensagem de sucesso
        $_SESSION['success'] = "Tarefa removida com sucesso!";
    } else {
        // Caso contrário, define uma mensagem de erro
        $_SESSION['error'] = "Erro ao remover a tarefa.";
    }
    header("Location: index.php"); // Redireciona para a página inicial
    exit;
}

