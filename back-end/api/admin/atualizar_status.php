<?php
require_once 'admin_gatekeeper.php';
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_assinatura = $_POST['id_assinatura'];
    $novo_status = $_POST['novo_status'];

    // Valida se o status enviado é um dos valores permitidos no ENUM
    $status_permitidos = ['ativa', 'expirada', 'cancelada', 'bloqueada'];
    if (in_array($novo_status, $status_permitidos)) {
        try {
            $sql = "UPDATE assinaturas SET status = ? WHERE id_assinatura = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$novo_status, $id_assinatura]);
            
            // Redireciona de volta para a lista geral com sucesso
            header('Location: usuarios.php?status=update_success');
            exit;
        } catch (PDOException $e) {
            header('Location: usuarios.php?status=update_error');
            exit;
        }
    }
}

// Se algo der errado, volta para a lista
header('Location: usuarios.php');
exit;
?>