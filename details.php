<?php
// Inicia a sessão para permitir o uso de variáveis de sessão
session_start();

// Verifica se o usuário está logado (variável 'user_id' na sessão)
// Caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit; // Encerra a execução do script
}

// Inclui o arquivo de conexão com o banco de dados
require __DIR__ . '/connect.php';

// Verifica se a chave 'key' foi passada na URL e se é um número inteiro válido
if (isset($_GET['key']) && filter_var($_GET['key'], FILTER_VALIDATE_INT)) {
    // Prepara a consulta SQL para buscar a tarefa específica associada ao usuário logado
    $stmt = $conn->prepare('SELECT * FROM tasks WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $_GET['key'], PDO::PARAM_INT); // Vincula o ID da tarefa (GET)
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); // Vincula o ID do usuário (sessão)
    $stmt->execute(); // Executa a consulta

    // Obtém os dados da tarefa em forma de array associativo
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se os dados da tarefa foram encontrados, exibe os detalhes
    if ($data) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <title>Detalhes da Tarefa</title>
            <style>
                /* Estilos adicionais para a página de detalhes */
                .details-container {
                    max-width: 800px;
                    margin: 20px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                }
                .header h1 {
                    margin-bottom: 20px;
                }
                .row {
                    display: flex;
                    gap: 20px;
                }
                .details dl dt {
                    font-weight: bold;
                }
                .details dl dd {
                    margin-bottom: 10px;
                }
                .image img {
                    max-width: 100%;
                    height: auto;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                }
                .btn-back {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                    text-align: center;
                    transition: background-color 0.3s;
                }
                .btn-back:hover {
                    background-color: #45a049;
                }
                .footer {
                    margin-top: 20px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
        <div class="details-container">
            <div class="header">
                <!-- Exibe o nome da tarefa -->
                <h1><?php echo htmlspecialchars($data['task_name']); ?></h1>
            </div>
            <div class="row">
                <div class="details">
                    <!-- Exibe a descrição e a data da tarefa -->
                    <dl>
                        <dt>Descrição da tarefa: </dt>
                        <dd><?php echo htmlspecialchars($data['task_description']); ?></dd>
                        <dt>Data da tarefa: </dt>
                        <dd><?php echo htmlspecialchars($data['task_date']); ?></dd>
                    </dl>
                </div>
                <div class="image">
                    <!-- Exibe a imagem da tarefa, caso exista -->
                    <?php if (!empty($data['task_image'])): ?>
                        <img src="<?php echo htmlspecialchars($data['task_image']); ?>" alt="Imagem da tarefa">
                    <?php else: ?>
                        <p><em>Sem imagem associada.</em></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer">
                <!-- Botão para voltar à página inicial -->
                <a href="index.php" class="btn-back">Voltar</a>
                <p>Desenvolvido por Lucas e Alanna</p>
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
        <?php
    } else {
        // Caso a tarefa não seja encontrada, define uma mensagem de erro e redireciona
        $_SESSION['message'] = "Tarefa não encontrada!";
        header('Location: index.php');
        exit();
    }
} else {
    // Caso o ID seja inválido, define uma mensagem de erro e redireciona
    $_SESSION['message'] = "ID inválido!";
    header('Location: index.php');
    exit();
}
?>
