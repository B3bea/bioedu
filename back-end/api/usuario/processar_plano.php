<?php
session_start();
require_once '../database/config.php'; // Ajuste o caminho para seu config.php

// 1. SEGURANÇA: Verifica se o usuário está logado e se o formulário foi enviado via POST
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: planos.php');
    exit();
}

// 2. PEGA OS DADOS DO FORMULÁRIO
$id_usuario = $_SESSION['user_id'];
$tipo_plano_nome = $_POST['tipo_plano']; // 'mensal' ou 'anual'

// --- SIMULAÇÃO DE PAGAMENTO ---
// Em um sistema real, aqui você usaria uma API de pagamento (Stripe, PagSeguro, etc.).
// Para nosso projeto, vamos apenas assumir que o pagamento foi um sucesso se os dados foram enviados.
// Poderíamos adicionar uma validação simples para os campos do cartão, mas vamos focar na lógica da assinatura.
$pagamento_aprovado = true;


// 3. SE O PAGAMENTO FOI APROVADO, ATIVA A ASSINATURA NO BANCO
if ($pagamento_aprovado) {
    
    // Busca o ID e a duração do plano na tabela 'planos'
    $stmt = $conn->prepare("SELECT id_plano, duracao_dias FROM planos WHERE nome LIKE ?");
    $termo_busca = '%' . $tipo_plano_nome . '%';
    $stmt->execute([$termo_busca]);
    $plano = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plano) {
        $id_plano = $plano['id_plano'];
        $duracao_dias = $plano['duracao_dias'];

        // Calcula as datas de início e fim da assinatura
        $data_inicio = date('Y-m-d');
        $data_fim = date('Y-m-d', strtotime("+$duracao_dias days"));
        
        // Antes de criar uma nova, podemos desativar assinaturas antigas deste usuário (opcional, mas recomendado)
        $stmt_desativar = $conn->prepare("UPDATE assinaturas SET status = 'inativo' WHERE id_usuario = ?");
        $stmt_desativar->execute([$id_usuario]);

        // Insere a nova assinatura como 'ativa'
        try {
            $sql = "INSERT INTO assinaturas (id_usuario, id_plano, data_inicio, data_fim, status) VALUES (?, ?, ?, ?, 'ativa')";
            $stmt_inserir = $conn->prepare($sql);
            $stmt_inserir->execute([$id_usuario, $id_plano, $data_inicio, $data_fim]);

            // Redireciona para a página de perfil com mensagem de sucesso
            header('Location: menu.php?status=plano_sucesso');
            exit();

        } catch (PDOException $e) {
            // Se der erro no banco, redireciona para a página de planos com erro
            header('Location: planos.php?erro=db_error');
            exit();
        }

    } else {
        // Se não encontrou o plano no banco
        header('Location: planos.php?erro=plano_invalido');
        exit();
    }
} else {
    // Se a "simulação de pagamento" falhar
    header('Location: checkout.php?erro=pagamento_recusado');
    exit();
}
?>