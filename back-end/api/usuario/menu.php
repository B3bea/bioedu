<?php
// LÓGICA PHP NO TOPO DA PÁGINA
session_start();
require_once '../database/config.php'; // Seu arquivo de conexão com o banco

// 1. VERIFICA SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 2. BUSCA OS DADOS DO USUÁRIO LOGADO
// 2. BUSCA OS DADOS DO USUÁRIO E DA SUA ASSINATURA ATIVA
$userId = $_SESSION['user_id'];

// Esta consulta usa LEFT JOIN para buscar os dados do usuário e, se existir, os dados do seu plano e assinatura ativa.
$sql = "
    SELECT
        u.id_usuario, u.nome, u.email, u.data_nascimento, u.usuario, u.foto_perfil,
        p.nome AS nome_do_plano,
        a.status AS status_da_assinatura,
        a.data_fim
    FROM
        usuarios u
    LEFT JOIN
        assinaturas a ON u.id_usuario = a.id_usuario AND a.status = 'ativa'
    LEFT JOIN
        planos p ON a.id_plano = p.id_plano
    WHERE
        u.id_usuario = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
// Se por algum motivo o usuário da sessão não for encontrado, desloga por segurança
if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Define o caminho da foto ou usa uma padrão
$caminhoFoto = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'https://via.placeholder.com/200';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - BIOEDU</title>
    <link rel="stylesheet" href="../../../front-end/css/styleLogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* CSS BASE (Inspirado no seu código) */
        :root {
            --dark-blue: rgb(12, 18, 74);
            --accent-blue: #00a8ff;
            --light-slate: #cdd6f4;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0a1041; /* Um pouco mais escuro que o card */
            color: var(--light-slate);
            margin: 0;
        }

        .profile-page-container {
            display: flex;
            justify-content: center;
            align-items: center; /* Alinha ao topo */
            min-height: 100vh;
            padding: 2rem;
        }

        .profile-card {
            display: flex;
            flex-wrap: wrap;
            background-color: var(--dark-blue);
            color: white;
            border-radius: 12px;
            padding: 2.5rem;
            max-width: 950px;
            width: 100%;
            box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.7);
            border: 1px solid #2a3f5a;
            gap: 3rem;
        }

        /* Coluna da Esquerda (Foto e Nome) */
        .profile-card-left {
            flex: 1;
            min-width: 250px;
            text-align: center;
        }

        .profile-image-container {
            display: block; /* Garante que o elemento se comporte como um bloco */
            position: relative; /* Já estava na sua classe .editable, mas é bom garantir */
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden; /* ESSENCIAL: Esconde tudo que passar dos limites do círculo */
            margin: 0 auto 1.5rem auto;
            border: 5px solid var(--accent-blue);
            box-shadow: 0 0 15px rgba(0, 168, 255, 0.5);
            box-sizing: content-box; /* Garante que a borda seja adicionada FORA dos 200px */
        }

        /* Garanta que esta regra para a imagem também exista e esteja correta */
        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Faz a imagem cobrir o espaço sem distorcer */
        }


        .user-main-info h1 {
            font-size: 2rem;
            margin: 0;
            color: var(--white);
        }

        .user-main-info p {
            color: var(--light-slate);
            margin-top: 0.5rem;
        }
        
        /* Coluna da Direita (Abas e Conteúdo) */
        .profile-card-right {
            flex: 2;
            min-width: 300px;
        }

        /* --- NOVO: Sistema de Abas --- */
        .tabs-nav {
            display: flex;
            border-bottom: 2px solid #2a3f5a;
            margin-bottom: 2rem;
        }

        .tab-link {
            font-size: 1rem;
            font-weight: 600;
            color: var(--light-slate);
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            cursor: pointer;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .tab-link:hover {
            color: var(--accent-blue);
        }

        .tab-link.active {
            color: var(--accent-blue);
        }
        /* Linha decorativa da aba ativa */
        .tab-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--accent-blue);
        }

        .tab-link i {
            margin-right: 0.5rem;
        }

        /* Painéis de conteúdo das abas */
        .tab-pane {
            display: none; /* Começam escondidos */
        }
        .tab-pane.active {
            display: block; /* O ativo é mostrado */
        }
        
        /* Estilo do formulário */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            background-color: #1e2952;
            border: 1px solid #2a3f5a;
            border-radius: 6px;
            color: var(--white);
            font-size: 1rem;
        }

        .btn-submit {
            background-color: var(--accent-blue);
            color: var(--white);
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #008fcc;
        }

        /* Estilo da aba 'Meus Planos' */
        .plan-info {
            background-color: #1e2952;
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid #2a3f5a;
        }
        .plan-info h3 { margin-top: 0; }
        
        /* Responsividade */
        @media (max-width: 850px) {
            .profile-card {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
        }

        /* --- NOVO: Estilos para Upload de Foto --- */
        .profile-image-container.editable {
            position: relative; /* Necessário para o posicionamento do overlay */
            cursor: pointer;
        }

        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.5);
            color: var(--white);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            opacity: 0; /* Começa invisível */
            transition: opacity 0.3s ease;
        }

        .profile-image-container.editable:hover .camera-overlay {
            opacity: 1; /* Aparece ao passar o mouse */
        }

        .btn-cancelar {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #dc3545; /* Vermelho para indicar uma ação de "perigo" */
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-cancelar:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="voltar">
        <a href="index.php"><img src="../../../front-end/imagens/arrow.png" alt="Seta para voltar à página inicial do site" class="imgSeta"></a>
    </div>

    <main class="profile-page-container">

        <div class="profile-card">
            
            <aside class="profile-card-left">
    <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
        
        <label for="foto_perfil" class="profile-image-container editable">
            <img src="<?php echo htmlspecialchars($caminhoFoto); ?>" alt="Foto de Perfil" id="profile-image-preview">
            
            <div class="camera-overlay">
                <i class="fas fa-camera"></i>
            </div>
        </label>
        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" style="display: none;">

        <div class="user-main-info">
            <h1><?php echo htmlspecialchars($usuario['nome']); ?></h1>
            <p><?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>

        <button type="submit" class="btn-submit" id="save-photo-btn" style="display: none; margin-top: 1rem;">
            Salvar Nova Foto
        </button>

    </form>
</aside>

            <section class="profile-card-right">
                <nav class="tabs-nav">
                    <button class="tab-link active" data-tab="info-pessoais"><i class="fas fa-user-edit"></i> Informações</button>
                    <button class="tab-link" data-tab="meus-planos"><i class="fas fa-gem"></i> Meus Planos</button>
                    <a href="logout.php" class="tab-link"><i class="fas fa-sign-out-alt"></i> Sair</a>
                </nav>

                <div class="tabs-content">
                    <div id="info-pessoais" class="tab-pane active">
                        <form action="atualizar_perfil.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nome">Nome Completo</label>
                                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="usuario">Nome de Usuário</label>
                                <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" readonly>
                            </div>
                             <div class="form-group">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento']); ?>">
                            </div>
                            <button type="submit" class="btn-submit">Salvar Alterações</button>
                        </form>
                    </div>

                <div id="meus-planos" class="tab-pane">

            <?php
            // A nova verificação: checa se a consulta retornou um nome de plano.
            // Isso significa que o usuário tem uma assinatura ativa.
            if (!empty($usuario['nome_do_plano'])):

                // Formata a data de expiração para o formato brasileiro (DD/MM/AAAA)
                $dataExp = new DateTime($usuario['data_fim']);
                $dataExpFormatada = $dataExp->format('d/m/Y');
            ?>
                <div class="plan-info">
                    <h3>Seu Plano Atual</h3>
                    <p><strong>Plano Ativo:</strong> BIOEDU <?php echo htmlspecialchars(ucfirst($usuario['nome_do_plano'])); ?></p>
                    <p>Acesso ilimitado a todos os recursos da plataforma.</p>
                    <p>Sua assinatura é válida até: <strong><?php echo $dataExpFormatada; ?></strong>.</p>

                    <br>
                    <a href="cancelar_plano.php" class="btn-cancelar" onclick="return confirm('Tem certeza que deseja cancelar seu plano?');">
                        Cancelar Plano
                    </a>
                </div>
    <?php
    // Se a consulta não retornou um nome de plano, ele não tem assinatura ativa.
    else:
    ?>
        <div class="plan-info">
            <h3>Você ainda não tem um plano ativo.</h3>
            <p>Assine agora um de nossos planos para ter acesso completo a todos os recursos incríveis da plataforma BIOEDU!</p>
            <br>
            <a href="planos.php" class="btn-submit" style="text-decoration: none;">Ver Planos</a>
        </div>
    <?php
    endif;
    ?>
    
</div>
                </div>
            </section>
        </div>
    </main>

    <script>
    // JAVASCRIPT PARA FUNCIONALIDADE DA PÁGINA
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- CÓDIGO DAS ABAS (que você já tinha) ---
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            if (link.tagName === 'A') return; // Ignora o link de logout
            link.addEventListener('click', () => {
                const tabId = link.getAttribute('data-tab');
                tabLinks.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                link.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // --- NOVO: CÓDIGO PARA UPLOAD DA FOTO ---
        const fileInput = document.getElementById('foto_perfil');
        const imagePreview = document.getElementById('profile-image-preview');
        const savePhotoButton = document.getElementById('save-photo-btn');

        // Quando o usuário escolhe um arquivo...
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Atualiza a imagem na tela com a nova foto
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);

                // Mostra o botão "Salvar Nova Foto"
                savePhotoButton.style.display = 'block';
            }
        });
    });
</script>
</main>

<script>
// JAVASCRIPT PARA FUNCIONALIDADE DA PÁGINA
// ... seu script de abas e upload de foto ...
</script>

<?php
    // Verifica se existe um 'status' na URL, vindo do redirecionamento
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        // Pega a mensagem de erro, se ela existir
        $msg = isset($_GET['msg']) ? $_GET['msg'] : '';

        // Inicia a tag de script para poder usar JavaScript
        echo '<script>';

        // Verifica qual o status e define a mensagem do alerta
        if ($status === 'sucesso_update') {
            echo 'alert("Dados atualizados com sucesso!");';
        } elseif ($status === 'erro' && !empty($msg)) {
            // Usamos json_encode para garantir que a mensagem seja passada de forma segura para o JavaScript,
            // evitando que aspas ou caracteres especiais quebrem o código.
            $mensagem_erro_formatada = json_encode('Erro: ' . $msg);
            echo 'alert(' . $mensagem_erro_formatada . ');';

        // ----- INÍCIO DA ADIÇÃO -----
        } elseif ($status === 'plano_sucesso') {
            echo 'alert("Plano ativado com sucesso! Bem-vindo(a) ao BIOEDU Premium!");';
        } elseif ($status === 'plano_cancelado') {
            echo 'alert("Seu plano foi cancelado com sucesso.");';
        }
        // ----- FIM DA ADIÇÃO -----

        // Limpa a URL para que o alerta não apareça novamente se o usuário recarregar a página
        echo 'window.history.replaceState(null, null, window.location.pathname);';

        // Fecha a tag de script
        echo '</script>';
    }
?>
</body>
</html>