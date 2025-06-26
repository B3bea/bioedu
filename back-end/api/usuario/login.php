<?php

require_once '../../../includes/cors.php';

require_once '../database/config.php';

header('Content-Type: application/json');

// --- PREPARAÇÃO ---
$response = [];
$conexao = new mysqli('localhost', 'root', '', 'bioedu_usuario');

// Verifica a conexão com o banco de dados
if ($conexao->connect_error) {
    http_response_code(500); // Erro de servidor
    $response['success'] = false;
    $response['message'] = "Falha na conexão: " . $conexao->connect_error;
    echo json_encode($response);
    exit();
}

// --- PASSO 1: RECEBER E VALIDAR OS DADOS DO LOGIN ---

// Valida se 'usuario' e 'senha' foram enviados
if (!isset($_POST['usuario']) || empty($_POST['usuario']) || !isset($_POST['senha']) || empty($_POST['senha'])) {
    http_response_code(400); // Bad Request
    $response['success'] = false;
    $response['message'] = "Os campos 'usuario' e 'senha' são obrigatórios.";
    echo json_encode($response);
    exit();
}

// Pega os dados do POST
$usuario = $_POST['usuario'];
$senhaPlana = $_POST['senha']; // A senha que o usuário digitou no formulário de login

// --- PASSO 2: BUSCAR O USUÁRIO NO BANCO ---

// Preparamos uma consulta segura para buscar o usuário pelo usuario.
// É crucial selecionar a coluna 'senha' que contém o HASH.
$sql = "SELECT id_usuario, usuario, senha, tipo_usuario FROM usuarios WHERE usuario = ?";
$stmt = $conexao->prepare($sql);

if ($stmt) {
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result(); // Pega o resultado da consulta

    if ($result->num_rows === 1) {
        // Se encontrou o usuário, pega os dados dele
        $user = $result->fetch_assoc();

        
    if (password_verify($senhaPlana, $user['senha'])) {
        // Se a senha estiver CORRETA...

        session_start();                           
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_type'] = $user['tipo_usuario']; 

        // PREPARA A RESPOSTA JSON (código que você já tem)
        http_response_code(200); // OK
        $response['success'] = true;
        $response['message'] = "Login bem-sucedido!";
        $response['user_type'] = $user['tipo_usuario'];
        $response['user'] = [
            'id' => $user['id_usuario'],
            'usuario' => $user['usuario']
        ];
    } else {
        // Se a senha estiver INCORRETA...
        http_response_code(401); // Unauthorized
        $response['success'] = false;
        $response['message'] = "Usuário ou senha inválidos.";
    }
    } else {
        // Se o usuário NÃO FOI ENCONTRADO no banco...
        http_response_code(401); // Unauthorized
        $response['success'] = false;
        $response['message'] = "Usuário ou senha inválidos."; // Mensagem genérica por segurança
    }
    $stmt->close();
} else {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = "Erro na preparação da consulta: " . $conexao->error;
}

$conexao->close();

// Envia a resposta final em formato JSON
echo json_encode($response);
?>