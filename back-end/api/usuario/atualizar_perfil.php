<?php
// Inicia a sessão para podermos acessar o ID do usuário logado
session_start();

// Inclui seu arquivo de conexão com o banco de dados
// Garanta que o caminho para o arquivo config.php está correto
require_once '../database/config.php'; 

// --- 1. VERIFICAÇÕES DE SEGURANÇA ---

// Se não houver um 'user_id' na sessão, significa que o usuário não está logado.
// Redirecionamos para a página de login por segurança.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit(); // Encerra o script para garantir que nada mais seja executado
}

// Verificamos se a requisição foi feita usando o método POST, que é como o formulário envia os dados.
// Isso evita que o script seja acessado diretamente pela URL.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: menu.php'); // Redireciona para a página de perfil
    exit();
}


// --- 2. COLETA E VALIDAÇÃO DOS DADOS ---

$userId = $_SESSION['user_id'];
// Usamos trim() para remover espaços em branco no início e no fim dos campos de texto
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$dataNascimento = $_POST['data_nascimento']; // O campo type="date" já ajuda na formatação

// Validação simples para garantir que os campos não estão vazios
if (empty($nome) || empty($email) || empty($dataNascimento)) {
    // Se algo estiver vazio, redireciona de volta com uma mensagem de erro
    header('Location: menu.php?status=erro&msg=' . urlencode('Todos os campos devem ser preenchidos.'));
    exit();
}

// Valida se o e-mail tem um formato válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: menu.php?status=erro&msg=' . urlencode('O formato do e-mail é inválido.'));
    exit();
}


// --- 3. ATUALIZAÇÃO NO BANCO DE DADOS ---

try {
    // A query SQL para atualizar os dados na tabela 'usuarios'
    // Usamos 'placeholders' (?) para proteger contra SQL Injection
    $sql = "UPDATE usuarios SET nome = ?, email = ?, data_nascimento = ? WHERE id_usuario = ?";

    // Prepara a declaração SQL para ser executada
    $stmt = $conn->prepare($sql);

    // Executa a query, passando os valores para os placeholders na ordem correta
    $stmt->execute([$nome, $email, $dataNascimento, $userId]);

    // Se tudo deu certo, redireciona de volta para o perfil com uma mensagem de sucesso
    header('Location: menu.php?status=sucesso_update');
    exit();

} catch (PDOException $e) {
    // Se ocorrer um erro durante a comunicação com o banco de dados...
    // Em um projeto real, você poderia registrar o erro em um arquivo de log: error_log($e->getMessage());
    // E redireciona o usuário com uma mensagem de erro genérica.
    header('Location: menu.php?status=erro&msg=' . urlencode('Ocorreu um erro no servidor. Tente novamente mais tarde.'));
    exit();
}