<?php
// Lógica para garantir que apenas usuários logados acessem aqui
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha seu Plano - BIOEDU</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0a1041;
            color: #cdd6f4;
            margin: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .header-planos {
            text-align: center;
            margin-bottom: 3rem;
        }
        .header-planos h1 {
            color: #ffffff;
            font-size: 2.5rem;
        }
        .header-planos p {
            font-size: 1.2rem;
            max-width: 600px;
        }
        .planos-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .plano-card {
            background-color: rgb(12, 18, 74);
            border: 2px solid #2a3f5a;
            border-radius: 12px;
            padding: 2.5rem;
            width: 300px;
            text-align: center;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .plano-card:hover {
            transform: translateY(-10px);
            border-color: #00a8ff;
        }
        .plano-card h2 {
            font-size: 1.8rem;
            color: #00a8ff;
        }
        .plano-card .preco {
            font-size: 2.8rem;
            font-weight: 700;
            color: #ffffff;
            margin: 1rem 0;
        }
        .plano-card .preco span {
            font-size: 1rem;
            font-weight: 400;
            color: #cdd6f4;
        }
        .plano-card ul {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }
        .plano-card ul li {
            margin-bottom: 0.8rem;
        }
        .plano-card ul li i {
            color: #28a745;
            margin-right: 0.5rem;
        }
        .btn-comprar {
            display: inline-block;
            /* width: 100%; */
            padding: 1rem;
            background-color: #00a8ff;
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-comprar:hover {
            background-color: #008fcc;
        }
    </style>
</head>
<body>

    <div class="header-planos">
        <h1>Escolha o Plano Ideal para Você</h1>
        <p>Tenha acesso a todos os recursos da plataforma BIOEDU e acelere seus resultados.</p>
    </div>

    <div class="planos-container">
        <div class="plano-card">
            <h2>Mensal</h2>
            <div class="preco">R$29<span>,90/mês</span></div>
            <ul>
                <li><i class="fas fa-check-circle"></i> Acesso a todos os cursos</li>
                <li><i class="fas fa-check-circle"></i> Relatórios de progresso</li>
                <li><i class="fas fa-check-circle"></i> Suporte via e-mail</li>
                <li><i class="fas fa-check-circle"></i> Cancele quando quiser</li>
            </ul>
            <a href="checkout.php?tipo=mensal" class="btn-comprar">Assinar Agora</a>
        </div>

        <div class="plano-card">
            <h2>Anual</h2>
            <div class="preco">R$299<span>,90/ano</span></div>
            <ul>
                <li><i class="fas fa-check-circle"></i> Acesso a todos os cursos</li>
                <li><i class="fas fa-check-circle"></i> Relatórios de progresso</li>
                <li><i class="fas fa-check-circle"></i> Suporte prioritário 24/7</li>
                <li><i class="fas fa-check-circle"></i> Desconto de 2 meses</li>
            </ul>
            <a href="checkout.php?tipo=anual" class="btn-comprar">Assinar Agora</a>
        </div>
    </div>

</body>
</html>