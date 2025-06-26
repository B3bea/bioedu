<?php

ini_set('display_errors', 1); // Mantenha em 1 para desenvolvimento, mude para 0 em produção
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Headers para permitir requisições de outras origens (CORS)
header("Access-Control-Allow-Origin: *"); // Em produção, troque '*' pelo seu domínio front-end
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Responde a requisições OPTIONS (pre-flight) que o navegador envia
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../database/config.php'; // Sua conexão PDO ($conn)

// --- ROTEAMENTO SIMPLES ---
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$uriParts = explode('/', trim($uri, '/'));

// Ex: /api/usuario/1 -> $resource = 'usuario', $id = 1
// Ex: /api/usuario/login -> $resource = 'usuario', $action = 'login'
$resource = $uriParts[2] ?? null; // 'usuario'
$id = isset($uriParts[3]) && is_numeric($uriParts[3]) ? (int)$uriParts[3] : 0;
$action = isset($uriParts[3]) && !is_numeric($uriParts[3]) ? $uriParts[3] : null;

// --- CORREÇÃO: Lógica do Switch corrigida ---
switch ($method) {
    case 'GET':
        if ($id > 0) {
            getById($conn, $id);
        } else {
            getAll($conn);
        }
        break;

    case 'POST':
        // Se a URL for /api/usuario/login, chama a função de login
        if ($action === 'login') {
            login($conn);
        } else { // Senão, é um cadastro de novo usuário
            post($conn);
        }
        break;

    case 'PUT':
        if ($id > 0) {
            put($conn, $id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID do usuário é obrigatório para atualização.']);
        }
        break;

    case 'DELETE':
        if ($id > 0) {
            delete($conn, $id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID do usuário é obrigatório para exclusão.']);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Método não permitido.']);
        break;
}

// --- FUNÇÕES DO CRUD E LOGIN ---

function getAll($conn) {
    try {
        $stmt = $conn->query('SELECT id_usuario, nome, email, data_nascimento, usuario FROM usuario ORDER BY nome');
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($usuarios);
    } catch (PDOException $e) {
        handleError($e, "Erro ao buscar usuários.");
    }
}

function getById($conn, $id) {
    try {
        $stmt = $conn->prepare("SELECT id_usuario, nome, email, data_nascimento, usuario FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    } catch (PDOException $e) {
        handleError($e, "Erro ao buscar usuário.");
    }
}

/**
 * Função para CADASTRAR um novo usuário.
 */
function post($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    // --- CORREÇÃO: Validação corrigida ---
    $required_fields = ['nome', 'email', 'data_nascimento', 'usuario', 'senha'];
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $errors[] = "O campo '$field' é obrigatório.";
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['errors' => $errors]);
        return;
    }

    $nome = $data['nome'];
    $email = $data['email'];
    $data_nascimento = $data['data_nascimento'];
    $usuario = $data['usuario'];
    $senhaPlana = $data['senha'];

    // --- SEGURANÇA: Criptografa a senha com password_hash() ---
    $senhaHash = password_hash($senhaPlana, PASSWORD_DEFAULT);

    try {
        // --- SEGURANÇA: Salva o HASH da senha, não a senha plana ---
        $stmt = $conn->prepare('INSERT INTO usuario (nome, email, data_nascimento, usuario, senha) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$nome, $email, $data_nascimento, $usuario, $senhaHash]);
        
        $usuarioId = $conn->lastInsertId();
        http_response_code(201); // 201 Created
        echo json_encode(['id_usuario' => $usuarioId, 'nome' => $nome, 'message' => 'Usuário criado com sucesso.']);
    } catch (PDOException $e) {
        handleError($e, "Erro ao criar usuário.");
    }
}

/**
 * Função para AUTENTICAR (fazer login).
 * Esta é a nova função que implementa a lógica segura de login.
 */
function login($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['usuario']) || empty($data['senha'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Usuário e senha são obrigatórios.']);
        return;
    }
    
    $usuario = $data['usuario'];
    $senhaPlana = $data['senha'];

    try {
        $stmt = $conn->prepare("SELECT id_usuario, usuario, senha FROM usuario WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // --- SEGURANÇA: Usa password_verify() para comparar a senha ---
        if ($user && password_verify($senhaPlana, $user['senha'])) {
            http_response_code(200);
            echo json_encode(['message' => 'Login bem-sucedido!', 'userId' => $user['id_usuario']]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Usuário ou senha inválidos.']);
        }
    } catch (PDOException $e) {
        handleError($e, "Erro durante a autenticação.");
    }
}

function put($conn, $id) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nenhum dado fornecido para atualização.']);
        return;
    }

    try {
        // Busca os dados atuais para preencher os que não foram enviados
        $stmt = $conn->prepare("SELECT nome, email, data_nascimento, usuario, senha FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$currentUser) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
            return;
        }

        // Atualiza os campos apenas se eles foram enviados no JSON
        $nome = $data['nome'] ?? $currentUser['nome'];
        $email = $data['email'] ?? $currentUser['email'];
        $data_nascimento = $data['data_nascimento'] ?? $currentUser['data_nascimento'];
        $usuario = $data['usuario'] ?? $currentUser['usuario'];
        
        // --- SEGURANÇA: Atualiza a senha apenas se uma nova for enviada ---
        $senha = $currentUser['senha']; // Mantém a senha antiga por padrão
        if (!empty($data['senha'])) {
            $senha = password_hash($data['senha'], PASSWORD_DEFAULT); // Cria um novo hash
        }

        $stmt = $conn->prepare('UPDATE usuario SET nome=?, email=?, data_nascimento=?, usuario=?, senha=? WHERE id_usuario=?');
        $stmt->execute([$nome, $email, $data_nascimento, $usuario, $senha, $id]);
        
        echo json_encode(['id_usuario' => $id, 'message' => 'Usuário atualizado com sucesso.']);
    } catch (PDOException $e) {
        handleError($e, "Erro ao atualizar usuário.");
    }
}

function delete($conn, $id) {
    try {
        $stmt = $conn->prepare('DELETE FROM usuario WHERE id_usuario = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Usuário excluído com sucesso!"]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado.']);
        }
    } catch (PDOException $e) {
        handleError($e, "Erro ao excluir usuário.");
    }
}

/**
 * Função centralizada para lidar com erros de banco de dados de forma segura.
 */
function handleError(PDOException $e, string $customMessage) {
    // Em modo de desenvolvimento, você pode querer logar o erro real
    // error_log($e->getMessage()); 
    
    http_response_code(500); // Internal Server Error
    // --- SEGURANÇA: Não exibe o erro do PDO para o cliente ---
    echo json_encode(['error' => $customMessage]);
}