<?php
session_start();
// O caminho para seu config.php deve estar correto a partir da localização deste arquivo.
// Se ele estiver na raiz do projeto, o caminho pode ser só 'config.php'.
require_once '../database/config.php'; 

// Segurança: Garante que o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

try {

    $sql = "UPDATE assinaturas SET status = 'cancelada' WHERE id_usuario = ? AND status = 'ativa'";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);

   
    header('Location: menu.php?status=plano_cancelado');
    exit();

} catch (PDOException $e) {
   
    header('Location: menu.php?status=erro&msg=' . urlencode('Não foi possível cancelar o plano. Tente novamente.'));
    exit();
}
?>