<?php
// 1. SEGURANÇA MÁXIMA: O PORTEIRO
// A primeira linha DEVE ser o gatekeeper. Ninguém que não seja admin pode executar este script.
require_once 'admin_gatekeeper.php';
require_once '../database/config.php'; // Conexão com o banco

// 2. VALIDAÇÃO DO ID
// Verifica se um ID foi passado pela URL e se é um número válido.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: usuarios.php?status=id_invalido');
    exit();
}

$id_para_deletar = (int)$_GET['id'];
$id_admin_logado = $_SESSION['user_id'];

// 3. TRAVA DE SEGURANÇA CONTRA AUTO-DELEÇÃO
// Impede que um admin apague a própria conta, o que poderia travar o acesso ao painel.
if ($id_para_deletar === $id_admin_logado) {
    header('Location: usuarios.php?status=erro_autodelete');
    exit();
}

// 4. EXECUÇÃO DA EXCLUSÃO
// Usa um bloco try...catch para lidar com possíveis erros do banco de dados.
try {
    // Prepara a consulta SQL usando um 'placeholder' (?) para segurança (evita SQL Injection)
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    
    // Executa a consulta, substituindo o '?' pelo ID do usuário a ser deletado
    $stmt->execute([$id_para_deletar]);

    // Verifica se alguma linha foi realmente afetada/deletada
    if ($stmt->rowCount() > 0) {
        // Se deletou com sucesso, redireciona para a lista com uma mensagem de sucesso
        header('Location: usuarios.php?status=delete_success');
    } else {
        // Se nenhuma linha foi deletada (ex: o ID não existia), redireciona com um aviso
        header('Location: usuarios.php?status=user_not_found');
    }
    exit();

} catch (PDOException $e) {
    // Se ocorrer um erro no banco de dados durante a exclusão...
    // Em um ambiente real, você poderia logar o erro: error_log($e->getMessage());
    // Redireciona com uma mensagem de erro genérica.
    header('Location: usuarios.php?status=delete_error');
    exit();
}
?>