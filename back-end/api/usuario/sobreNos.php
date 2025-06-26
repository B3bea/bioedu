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
    <link rel="stylesheet" href="../../../front-end/css/styleSobreNos.css">
    <title>BIOEDU - SOBRE NÓS</title>
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
            <img src="../../../front-end/imagens/plano de fundo-sobrenos.jpg" alt="Um grupo de pessoas com as mãos dadas">
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
            <h1>Conheça nossa equipe</h1>

            <button class="btnMais" onclick="rolar('#conteudo1')">LER MAIS</button>
        </div>

        <section id="conteudo1">
            <h2>Um pouco sobre nossa história</h2>
            
            <div class="conteudo1_1">
                <div class="ladoTexto">
                    <p class="parag1">
                        Somos o grupo TechTitans formado por 3 alunos, atualmente da Faculdade de Tecnologia FATEC Taquaritinga,  apaixonados por programação. 
                        <br><br>
                        Estamos cursando Sistemas para Internet (2º semestre). Desde o começo de nosso projeto buscamos achar soluções tecnológicas para problemas do dia-a-dia de escolas, onde segundo pesquisas, podemos ver que tecnologias existentes não são tão eficientes como deveriam ser. 
                    </p>
            </div>

                <div class="ladoImagem">
                    <img class="fatec" src="../../../front-end/imagens/Marca_Fatec/Fatec Taquaritinga (oficial).png" alt="Logo da Fatec Taquaritinga em cinza e vermelho" width="650px">
                </div>
            </div>
        </section>

        <section id="conteudo2_sobrenos">
            <h3>Quem somos nós?</h3>

            <div class="equipe">
            <div class="integrante">
                <img src="../../../front-end/imagens/eu.png" alt="Foto da integrante Beatriz sorrindo" width="185px">
                <ul>
                    <li><strong>Beatriz Silva</strong></li>
                    <li>23 anos</li>
                    <li>Estudante do 4º semestre de S.I. - FATEC Taquaritinga</li>
                    <li>Técnica em Informática - ETEC Taquaritinga</li>
                    <li>Professora de Inglês a 5 anos</li>
                </ul>
            </div>

            <div class="integrante">
                <img src="../../../front-end/imagens/gui.png" alt="Foto do integrante Guilherme sorrindo" width="200px">
                <ul>
                    <li><strong>Guilherme Pedrozo</strong></li>
                    <li>21 anos</li>
                    <li>Estudante do 4º semestre de S.I. - FATEC Taquaritinga</li>
                    <li>Técnico em D.S. - ETEC Taquaritinga</li>
                </ul>
            </div>

            <div class="integrante">
                <img src="../../../front-end/imagens/fe.png" alt="Foto do integrante Felipe sorrindo" width="200px">
                <ul>
                    <li><strong>Fellipe Prado</strong></li>
                    <li>21 anos</li>
                    <li>Estudante do 4º semestre de S.I. - FATEC Taquaritinga</li>
                    <li>Técnico em D.S. - ETEC Ibitinga
                    </li>
                </ul>
            </div>
        </div>
        </section>
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
