<?php
require_once '../../../includes/cors.php';

require_once '../database/config.php';

// O restante do seu código PHP continua aqui...
header('Content-Type: application/json');

$response = []; 
$conexao = new mysqli('localhost', 'root', '', 'bioedu_usuario');

if ($conexao->connect_error) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = "Falha na conexão: " . $conexao->connect_error;
    echo json_encode($response);
    exit();
}

// --- VALIDAÇÃO DE TODOS OS CAMPOS VINDOS DO HTML ---
// Usando os 'names' que corrigimos no HTML
$required_fields = ['nome', 'email', 'usuario', 'senha'];
$errors = [];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        $errors[] = "O campo '$field' é obrigatório.";
    }
}

if (!empty($errors)) {
    http_response_code(400); // Bad Request
    $response['success'] = false;
    $response['message'] = implode(' ', $errors);
    echo json_encode($response);
    exit();
}

// Pega todos os dados do POST
$nome = $_POST['nome'];
$email = $_POST['email'];
$data_nascimento = !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null; // Data pode ser opcional
$usuario = $_POST['usuario'];
$senhaPlana = $_POST['senha'];

// Cria o hash seguro da senha
$senhaHash = password_hash($senhaPlana, PASSWORD_DEFAULT);

// --- SQL E BIND ATUALIZADOS PARA INCLUIR TODOS OS CAMPOS ---
// A ordem dos '?' deve bater com a ordem das colunas e dos parâmetros no bind_param
$sql = "INSERT INTO usuarios (nome, email, data_nascimento, usuario, senha) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);

if ($stmt) {
    // 'sssss' -> 5 parâmetros, todos do tipo string.
    // A ordem aqui deve bater com a ordem dos '?' no SQL
    $stmt->bind_param('sssss', $nome, $email, $data_nascimento, $usuario, $senhaHash);
    
    if ($stmt->execute()) {
        http_response_code(201); // Created
        $response['success'] = true;
        $response['message'] = "Usuário cadastrado com sucesso!";
    } else {
        http_response_code(500);
        $response['success'] = false;
        if ($conexao->errno === 1062) { // Erro de entrada duplicada
             $response['message'] = "Erro: O nome de usuário ou e-mail já está em uso.";
        } else {
             $response['message'] = "Erro ao cadastrar o usuário: " . $stmt->error;
        }
    }
    $stmt->close();
} else {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = "Erro na preparação da consulta: " . $conexao->error;
}

$conexao->close();

echo json_encode($response);
?>
