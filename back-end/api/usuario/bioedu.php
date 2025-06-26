<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();
require_once '../database/config.php'; // ATENÇÃO: Verifique se este caminho está correto a partir do seu index.php

$usuarioLogado = false; // Começamos assumindo que o usuário NÃO está logado

// Se existir um user_id na sessão, significa que ele está logado
if (isset($_SESSION['user_id'])) {
    $usuarioLogado = true;
    $userId = $_SESSION['user_id'];

    // Busca no banco de dados o nome e a foto do usuário logado
    $stmt = $conn->prepare("SELECT nome, foto_perfil FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$userId]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Pega apenas o primeiro nome para uma saudação mais amigável
        $primeiroNome = explode(' ', trim($usuario['nome']))[0];
        
        // Define o caminho da foto de perfil ou usa uma imagem padrão
        $caminhoFoto = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : '../../../front-end/imagens/login.png'; // Usando o mesmo ícone de login como padrão
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../favicon_io/favicon-16x16.png">  
    <link rel="stylesheet" href="../../../front-end/css/styleBioedu.css">
    <link rel="stylesheet" href="../../../front-end/css/styleCamera.css">
    <title>BIOEDU</title>
    <style>
        .perfil-logado {
            display: flex;
            align-items: center;
            gap: 15px; /* Espaço entre a foto e o nome */
            text-decoration: none;
            color: #ffffff;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        .perfil-logado:hover {
            opacity: 0.8;
        }

        .perfil-logado img {
            width: 48px;
            height: 48px;
            border-radius: 50%; /* Deixa a foto redonda */
            object-fit: cover; /* Garante que a imagem preencha o círculo sem distorcer */
            border: 2px solid #ffffff; /* Adiciona uma borda branca */
        }

                /* Estilos para a Navegação Principal */
        .nav {
            display: flex;
            justify-content: flex-end; /* Alinha itens à direita */
            align-items: center;
            gap: 20px;
        }

        .menu1 {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 20px;
        }

        .menu1 a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 5px;
        }

        /* Link de login para não-logados (melhorando o estilo) */
        .link-login {
            display: flex;
            align-items: center;
            gap: 10px;
        }
</style>
</head>
<body>

    <section class="banner">
        <img src="../../../front-end/imagens/plano de fundo-BIOEDU.jpg" alt="Um fundo de gradiente do preto para o azul">
    </section>   

    <a href="index.php">
        <img class="logo" src="../../../front-end/imagens/LOGO - BIOEDU - BRANCO ESCRITO.png" alt="Logo BioEdu">
    </a>
    
    <header>

        <nav class="nav">
    <button class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <ul class="menu1">
        <li><a href="index.php">INÍCIO</a></li>
        <li><a href="sobreNos.php">SOBRE NÓS</a></li>
        <li><a href="bioedu.php">BIOEDU</a></li>
        <li><a href="faleConosco.php">FALE CONOSCO</a></li>
        
        <li class="item-perfil">
            <?php if ($usuarioLogado): ?>
                <a href="menu.php" class="perfil-logado">
                    <img src="<?php echo htmlspecialchars($caminhoFoto); ?>" alt="Foto de Perfil">
                    <span>Olá, <?php echo htmlspecialchars($primeiroNome); ?></span>
                </a>
            <?php else: ?>
                <a href="tela_login.php" class="link-login">
                    <img class="imgLogin" src="../../../front-end/imagens/login.png" alt="Ícone de login" width="40px">
                    <span>Login</span>
                </a>
            <?php endif; ?>
        </li>
    </ul>
</nav>
    </header>

<div class="overlay"></div>

    <main>
        <div class="tituloBioEdu">
            <h1>BIOEDU</h1>
            <p>O MELHOR SISTEMA INDICADO PARA SUA ESCOLA</p>

            <button class="btnMais" onclick="rolar('#conteudo1')">LER MAIS</button>
        </div>

        <div id="conteudo1">
            <h2>O que é o BIOEDU?</h2>
    
                <p class="p1_bioedu">O sistema biométrico BIOEDU é uma tecnologia utilizada nas escolas para controlar as presenças e faltas dos alunos de forma mais eficiente e precisa. Ele funciona através da identificação única de características biológicas de cada aluno, como impressões digitais, íris ou reconhecimento facial.</p>
        </div>

        <div class="conteudo2_bioedu">
            <div class="ladoTexto_bioedu">
                <p class="parag1_bioedu">
                    Ao utilizar o BIOEDU, os alunos registram suas presenças e faltas simplesmente utilizando sua biometria, em vez de assinaturas em listas de chamada ou cartões de identificação. Isso torna o processo mais ágil e seguro, evitando a possibilidade de fraudes, como a entrada de alunos com cartões de identificação de outros ou assinaturas falsificadas.
                </p>
        </div>

            <div class="ladoImagem_bioedu">
                <img class="biometric" src="../../../front-end/imagens/biometric.png" alt="Ícone representando um sistema de biometria" width="350px">
            </div>
        </div>

        <h2 class="titulo3_bioedu">Como o sistema funciona?</h2>

        <div class="conteudo3_bioedu">
            
            <div class="pt1">
                <h3><strong>Cadastro Biométrico:</strong></h3>

                <p>Antes de utilizar o sistema, cada aluno é cadastrado no sistema BIOEDU com suas informações pessoais e uma amostra biométrica única. Isso geralmente envolve a digitalização das impressões digitais dos alunos.</p>
            </div>

            <div class="pt2">
                <h3><strong>Dispositivos Biométricos:</strong></h3>

                <p>O sistema BIOEDU é integrado a dispositivos biométricos, como leitores de impressão digital ou scanners de íris, instalados nas entradas das salas de aula ou em locais designados.</p>
            </div>

            <div class="pt3">
                <h3><strong>Registro de Presença:</strong></h3>

                <p>Quando um aluno chega à sala de aula, ele é solicitado a colocar o dedo no leitor biométrico para registrar sua presença. O sistema verifica a correspondência da impressão digital com o banco de dados biométrico para identificar o aluno.</p>
            </div>

            <div class="pt4">
                <h3><strong>Monitoramento em Tempo Real:</strong></h3>

                <p>Os administradores e professores têm acesso em tempo real aos registros de presença dos alunos por meio de um painel de controle online. Isso permite um acompanhamento preciso da frequência dos alunos durante o período letivo.</p>
            </div>

            <div class="pt5">
                <h3><strong>Segurança e Privacidade:</strong></h3>

                <p>A segurança dos dados biométricos dos alunos é uma prioridade no sistema BIOEDU. Todas as informações biométricas são criptografadas e armazenadas de forma segura, garantindo a privacidade e a integridade dos dados dos alunos.</p>
            </div>
        </div>

        <div class="testeBtn">
            <button class="btnTeste"><a href="testeCamera.php">FAÇA UM TESTE AGORA</a></button>
        </div>
    </main>

    <footer>
        <div class="contato">
            <h4>Contato</h4> <br><br>

            <div class="email">
                <img src="../../../front-end/imagens/email.png" alt="Ícone de email" width="4%">
                <p>Email: bioedu@gmail.com.br</p>
            </div>

            <div class="telefone">
                <img src="../../../front-end/imagens/phone-call.png" alt="Ícone de um telefone" width="4%">
                <p>Telefone: (16) 1234-5678</p>
            </div>

            <div class="facebook">
                <img src="../../../front-end/imagens/facebook.png" alt="Ícone do Facebook" width="4%">
                <p>BIOEDU</p>
            </div>

            <div class="instagram">
                <img src="../../../front-end/imagens/instagram.png" alt="Ícone do Instagram" width="4%">
                <p>@bioedu_ed</p>
            </div>

            <div class="whatsapp">
                <img src="../../../front-end/imagens/whatsapp.png" alt="Ícone do Whatsapp" width="4%">
                <p>(16) 12345-6789</p>
            </div>
        </div>

        <div class="endereco">
            <h4>Endereço</h4>
            <p>Av. Dr. Flávio Henrique,</p>

            <p>Av. Dr. Flávio Henrique Lemos, 585</p>

            <p>Portal Itamaracá</p>

            <p>Taquaritinga - SP, 15900</p>
        </div>

        <div class="politica">
            <h4>Política de Privacidade</h4>

            <div>
                <p>Todos os direitor reservados.</p>

                <p>&copy; Copyright 2024, BIOEDU</p>
            </div>
        </div>
    </footer>

    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
          <div class="vw-plugin-top-wrapper"></div>
        </div>
      </div>
      <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
      <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
      </script>

    <script>
    // Seleciona os elementos
    const hamburger = document.querySelector('.hamburger');
    const menu1 = document.querySelector('.menu1');
    const overlay = document.querySelector('.overlay'); // Seleciona o overlay

    // Função para abrir/fechar o menu
    function toggleMenu() {
        hamburger.classList.toggle('open');
        menu1.classList.toggle('open');
        overlay.classList.toggle('active'); // Ativa/desativa o overlay
    }

    // Adiciona o evento de clique ao botão hambúrguer
    hamburger.addEventListener('click', toggleMenu);

    // Adiciona um evento de clique ao overlay para fechar o menu
    overlay.addEventListener('click', toggleMenu);
</script>
      
</body>
<script src="../../../front-end/js/script.js"></script>
</html>