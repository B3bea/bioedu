<?php
session_start();
require_once '../database/config.php'; // Inclui a conexão com o banco

// 1. VERIFICA SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, não pode fazer upload.
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// 2. VALIDAÇÃO DO ARQUIVO (mesma lógica de antes)
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    
    $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
    $fileName = $_FILES['foto_perfil']['name'];
    $fileSize = $_FILES['foto_perfil']['size'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $uploadDir = '../uploads/';
    $newFileName = $userId . '_' . time() . '.' . $fileExtension;
    $dest_path = $uploadDir . $newFileName;

    $allowedfileExtensions = ['jpg', 'jpeg', 'png'];
    // ... (todas as outras validações de tamanho e tipo de arquivo) ...

    // 3. MOVE O ARQUIVO E ATUALIZA O BANCO DE DADOS
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        
        // **A PARTE MAIS IMPORTANTE**
        // Agora, atualizamos o registro do usuário no banco de dados com o caminho da nova foto.
        try {
            $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dest_path, $userId]);
            
            // Sucesso! Redireciona de volta para o menu.
            header('Location: menu.php?status=sucesso');
            exit;

        } catch (PDOException $e) {
            // Se houver um erro no banco, redireciona com mensagem de erro.
            // Em produção, você poderia logar o erro em vez de mostrar ao usuário.
            header('Location: menu.php?status=erro_db');
            exit();
        }

    } else {
        header('Location: menu.php?status=erro_upload');
        exit;
    }
} else {
    header('Location: menu.php?status=erro_geral');
    exit;
}
?>