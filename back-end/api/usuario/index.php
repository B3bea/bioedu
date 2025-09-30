<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();
require_once '../database/config.php'; 
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
    <link rel="stylesheet" href="../../../front-end/css/styleInicio.css">
    <title>BIOEDU - Gestão biométrica de presença</title>
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
            font-size: 20px;
        }

        /* Link de login para não-logados (melhorando o estilo) */
        .link-login {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Botão Hamburger (escondido em telas grandes) */
        .hamburger {
            display: none;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 1001; /* Garante que fique sempre no topo */
        }
</style>
</head>

<body>
    <section class="banner">
        <img src="../../../front-end/imagens/plano de fundo.jpg" alt="Imagem de um olho sendo escaneado por biometria">
    </section>

    <a href="index.php"><img class="logo" src="../../../front-end/imagens/LOGO - BIOEDU - BRANCO ESCRITO.png" alt="Logo BioEdu"></a>
    
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
            <p>GESTÃO BIOMÉTRICA DE PRESENÇA</p>

            <button class="btnMais" onclick="rolar('#conteudo1')">LER MAIS</button>
        </div>

        <section id="conteudo1">
            <h2>A importância da automação em tarefas escolares</h2>

            <p class="citacao"><em>"A tecnologia é apenas uma ferramenta. Em termos de tarefas e trabalhos <br>
                    mais amplos, é como ter lápis." - Neil Postman</em></p>
        </section>

        <section id="conteudo2">
            <div class="ladoTexto">
                <p class="parag1">
                    A necessidade de automações em tarefas
                    escolares é uma evolução natural da forma
                    como lidamos com a informação e
                    otimizamos nosso tempo. Vivemos em
                    uma era em que a tecnologia desempenha
                    um papel fundamental em quase todos os
                    aspectos de nossas vidas, e a educação
                    não é exceção.
                </p>
            </div>

            <div class="ladoImagem">
                <img class="engrenagens" src="../../../front-end/imagens/engineering.png" alt="Engrenagens coloridas" width="500px">
            </div>
        </section>


        <section id="conteudo3">
            <h3>Por que automatizar chamadas?</h3>

            <div class="ladoTexto2">
                <ul class="parag2">
                    <li><strong>Segurança</strong>: A biometria oferece um nível adicional de segurança, garantindo que
                        apenas os alunos autorizados estejam presentes nas dependências da escola. Isso ajuda a prevenir
                        casos de entrada não autorizada e contribui para um ambiente escolar mais seguro.
                    </li>
                    <br><br>
                    <li><strong>Precisão</strong>: Com a automação de chamadas por biometria, os registros de presença
                        são mais precisos, eliminando a possibilidade de erros humanos na contagem de alunos presentes.
                        Isso proporciona uma base de dados confiável para análises futuras de frequência e desempenho
                        acadêmico.</li>
                </ul>
            </div>

            <div class="ladoImagem2">
                <img class="lock" src="../../../front-end/imagens/3d-lock.png" alt="Cadeado em 3D">
                <img class="alvo" src="../../../front-end/imagens/target-dynamic-premium.png" alt="Flecha acertando um alvo precisamente">
            </div>
        </section>

        <section id="conteudo4">

            <div class="ladoTexto3">
                <ul class="parag3">
                    <li><strong>Economia de tempo</strong>: Ao automatizar o processo de chamada, os professores e
                        funcionários da escola economizam tempo valioso que poderia ser direcionado para atividades
                        educacionais mais produtivas. Além disso, os alunos passam menos tempo em filas para registrar
                        sua presença, otimizando o tempo de aula.
                    </li>
                    <br><br>
                    <li><strong>Redução de burocracias</strong>: A automação de chamadas reduz a necessidade de papelada
                        e processos burocráticos relacionados ao registro de presença dos alunos. Isso simplifica a
                        gestão escolar e aumenta a eficiência administrativa.
                    </li>
                </ul>
            </div>

            <div class="ladoImagem3">
                <img class="tempo" src="../../../front-end/imagens/hourglass-1046841_1280.png" alt="Ampulheta">
                <img class="grafico" src="../../../front-end/imagens/pngwing.com (1).png" alt="Flecha acertando um alvo precisamente">
            </div>
        </section>

        <section class="parceiros">
            <h3>Parceiros</h3>

            <div class="logoParc">
                <div class="parc">
                    <img src="../../../front-end/imagens/R.png" alt="Logo de um R branco em azul">
                    <p><strong>Blue R</strong></p>
                </div>

                <div class="parc">
                    <img src="../../../front-end/imagens/X.png" alt="Logo de um X branco e rosa">
                    <p><strong>Pink X</strong></p>
                </div>

                <div class="parc">
                    <img src="../../../front-end/imagens/P.png" alt="Logo de um P branco e verde">
                    <p><strong>Green P</strong></p>
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