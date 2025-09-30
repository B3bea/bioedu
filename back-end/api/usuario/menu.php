<?php

session_start();
require_once '../database/config.php'; 


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$userId = $_SESSION['user_id'];


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

if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit();
}


$caminhoFoto = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'https://via.placeholder.com/200';

$temPlanoAtivo = !empty($usuario['nome_do_plano']) && $usuario['status_da_assinatura'] === 'ativa';

$turmas = [];
if ($temPlanoAtivo) {
    // Supondo que você tenha uma tabela 'turmas' com 'id_turma' e 'nome'
    $sqlTurmas = "SELECT id, nome FROM turmas ORDER BY nome ASC";
    $stmtTurmas = $conn->query($sqlTurmas);
    $turmas = $stmtTurmas->fetchAll(PDO::FETCH_ASSOC);
}
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

        /* Sistema de Abas */
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

        /* Estilos para Upload de Foto*/
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

            <?php if ($temPlanoAtivo): ?>
                <button class="tab-link" data-tab="gerenciar-turmas"><i class="fas fa-users"></i>Gerenciar Turmas</button>
            <?php endif; ?>
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
        <?php if (!empty($usuario['nome_do_plano'])):
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
        <?php else:  ?>
            <div class="plan-info">
                <h3>Você ainda não tem um plano ativo.</h3>
                <p>Assine um de nossos planos para ter acesso a este recurso!</p>
                <br>
                <a href="planos.php" class="btn-submit" style="text-decoration: none;">Ver Planos</a>
            </div>
        <?php endif; ?>
    </div> <?php if ($temPlanoAtivo): ?>
        <div id="gerenciar-turmas" class="tab-pane">
            <h3>Importar Dados</h3>
            <p>Faça o upload das suas planilhas para cadastrar alunos, professores e turmas.</p>

            <hr style="margin: 2rem 0;"> <form id="form-importacao-alunos" method="post" action="javascript:void(0)">
                <div class="form-group">
                    <label for="arquivo_alunos"><i class="fas fa-user-graduate"></i> Planilha de Alunos</label>
                    <input type="file" id="arquivo_alunos" name="arquivo_alunos" accept=".xlsx, .xls, .csv" required>
                </div>
                <button type="button" id="btn-importar-alunos" class="btn-submit">Importar Alunos</button>
            </form>
            <div id="upload-feedback" style="margin-top: 1.5rem; padding: 1rem; border-radius: 6px; display: none;"></div>

            <hr style="margin: 2rem 0;"> <form id="form-importacao-professores" method="post" action="javascript:void(0)">
            <div class="form-group">
                <label for="arquivo_professores"><i class="fas fa-chalkboard-teacher"></i> Planilha de Professores</label>
                <input type="file" id="arquivo_professores" name="arquivo_professores" accept=".xlsx, .xls, .csv" required>
            </div>
            <button type="button" id="btn-importar-professores" class="btn-submit">Importar Professores</button>
            </form>

            <div id="upload-feedback-professores" style="margin-top: 1.5rem; padding: 1rem; border-radius: 6px; display: none;"></div>

            <hr style="margin: 2rem 0;"> <form id="form-importacao-turmas" method="post" action="javascript:void(0)">
            <div class="form-group">
                <label for="arquivo_turmas"><i class="fas fa-school"></i> Planilha de Turmas</label>
                <input type="file" id="arquivo_turmas" name="arquivo_turmas" accept=".xlsx, .xls, .csv" required>
            </div>
            <button type="button" id="btn-importar-turmas" class="btn-submit">Importar Turmas</button>
            </form>
            <div id="upload-feedback-turmas" style="margin-top: 1.5rem; padding: 1rem; border-radius: 6px; display: none;"></div>

            <hr style="margin: 2rem 0;">
            <h4>Adicionar Alunos a uma Turma Existente</h4>
            <form id="form-adicionar-alunos-turma" method="post" action="javascript:void(0)">
                <div class="form-group">
                    <label for="turma_id">Selecione a Turma</label>
                    <select name="turma_id" id="turma_id" required style="width: 100%; padding: 0.8rem; background-color: #1e2952; border: 1px solid #2a3f5a; border-radius: 6px; color: var(--white); font-size: 1rem;">
                        <option value="">-- Escolha uma turma --</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?php echo $turma['id']; ?>">
                                <?php echo htmlspecialchars($turma['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="arquivo_alunos_turma"><i class="fas fa-users"></i> Planilha com Matrículas dos Alunos</label>
                    <input type="file" id="arquivo_alunos_turma" name="arquivo_alunos_turma" accept=".xlsx, .xls, .csv" required>
                </div>
                <button type="button" id="btn-adicionar-alunos-turma" class="btn-submit">Matricular Alunos</button>
            </form>
            <div id="upload-feedback-turma" class="feedback-div"></div>
    </div>
    <?php endif; ?>

</div> 
            </section>
        </div> 
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            if (link.tagName.toLowerCase() != 'button') return;
            link.addEventListener('click', function() {
                const tabId = link.getAttribute('data-tab');
                const targetPane = document.getElementById(tabId);
                tabLinks.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                this.classList.add('active');
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });

        const fileInput = document.getElementById('foto_perfil');
        const imagePreview = document.getElementById('profile-image-preview');
        const savePhotoButton = document.getElementById('save-photo-btn');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);

          
                savePhotoButton.style.display = 'block';
            }
        });
    });
    </script>
    
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        
        $msg = isset($_GET['msg']) ? $_GET['msg'] : '';

       
        $msg = isset($_GET['msg']) ? $_GET['msg'] : '';
        echo '<script>';

        
        if ($status === 'sucesso_update') {
            echo 'alert("Dados atualizados com sucesso!");';
        } elseif ($status === 'erro' && !empty($msg)) {
           
            $mensagem_erro_formatada = json_encode('Erro: ' . $msg);
            echo 'alert(' . $mensagem_erro_formatada . ');';


        } elseif ($status === 'plano_sucesso') {
            echo 'alert("Plano ativado com sucesso! Bem-vindo(a) ao BIOEDU Premium!");';
        } elseif ($status === 'plano_cancelado') {
            echo 'alert("Seu plano foi cancelado com sucesso.");';
        }

        echo 'window.history.replaceState(null, null, window.location.pathname);';


        echo '</script>';
    }
    ?>

    <script>
document.addEventListener('DOMContentLoaded', function () {

    const token = '3|FJb0SW7ePm9vuHXJInuT8jyrH3botopZFwJbncGs0153cdec'; 

    function setupImportForm(buttonId, formId, feedbackId, fileInputId, apiUrl) {
        const importButton = document.getElementById(buttonId);
        const form = document.getElementById(formId);
        const feedbackDiv = document.getElementById(feedbackId);

        if (importButton && form) {
            importButton.addEventListener('click', function () {
                const formData = new FormData(form);
                const fileInput = document.getElementById(fileInputId);
                if (!fileInput || fileInput.files.length === 0) {
                    alert('Por favor, selecione um arquivo para importar.');
                    return;
                }
                
                feedbackDiv.style.display = 'block';
                feedbackDiv.textContent = 'Enviando, por favor aguarde...';
                feedbackDiv.style.color = 'black';
                feedbackDiv.style.backgroundColor = '#ffc107';

                fetch('http://localhost:8000/api/importar/alunos', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(async response => {
                    const responseData = await response.json();
                    if (!response.ok) {
                        return Promise.reject(responseData);
                    }
                    return responseData;
                })
                .then(data => {
                    feedbackDiv.textContent = data.message;
                    feedbackDiv.textContent = 'Planilha importada com sucesso!';
                    feedbackDiv.style.backgroundColor = '#28a745';
                    form.reset();
                })
                .catch(error => {
                    console.error('Detalhes do Erro:', error);
                    let errorMessage = 'Erro na importação: ';
                    if (error && error.message) {
                        errorMessage += error.message;
                    } else {
                        errorMessage += 'Falha na comunicação com o servidor. Verifique se há extensões de navegador bloqueando a requisição.';
                    }
                    feedbackDiv.textContent = errorMessage;
                    feedbackDiv.style.backgroundColor = '#dc3545';
                });
            });
        }
    }

    
    setupImportForm('btn-importar-alunos', 'form-importacao-alunos', 'upload-feedback', 'arquivo_alunos', 'http://localhost:8000/api/importar/alunos');
    setupImportForm('btn-importar-professores', 'form-importacao-professores', 'upload-feedback-professores', 'arquivo_professores', 'http://localhost:8000/api/importar/professores');
    setupImportForm('btn-importar-turmas', 'form-importacao-turmas', 'upload-feedback-turmas', 'arquivo_turmas', 'http://localhost:8000/api/importar/turmas');
    

    const matriculaButton = document.getElementById('btn-adicionar-alunos-turma');
    const matriculaForm = document.getElementById('form-adicionar-alunos-turma');
    const matriculaFeedback = document.getElementById('upload-feedback-turma');

    if (matriculaButton && matriculaForm) {
        matriculaButton.addEventListener('click', function() {
            const selectTurma = document.getElementById('turma_id');
            const turmaId = selectTurma.value;

            if (!turmaId) {
                alert('Por favor, selecione uma turma.');
                return;
            }

            const fileInput = document.getElementById('arquivo_alunos_turma');
            if (!fileInput || fileInput.files.length === 0) {
                alert('Por favor, selecione um arquivo com as matrículas.');
                return;
            }

            const formData = new FormData(matriculaForm);
            const url = `http://localhost:8000/api/turmas/${turmaId}/adicionar-alunos`;
        
        matriculaFeedback.style.display = 'block';
        matriculaFeedback.textContent = 'Enviando, por favor aguarde...';
        matriculaFeedback.style.backgroundColor = '#ffc107';

        fetch(url, {
            method: 'POST',
            headers: { 
                'Authorization': `Bearer ${token}`, // Garanta que 'token' está definido no escopo
                'Accept': 'application/json' 
            },
            body: formData
        })
        .then(async response => {
            const responseData = await response.json();
            if (!response.ok) return Promise.reject(responseData);
            return responseData;
        })
        .then(data => {
            matriculaFeedback.textContent = data.message || 'Alunos matriculados com sucesso!';
            matriculaFeedback.style.backgroundColor = '#28a745';
            matriculaForm.reset();
        })
        .catch(error => {
            console.error('Detalhes do Erro:', error);
            let errorMessage = 'Erro na matrícula: ';
            errorMessage += error.message || 'Falha na comunicação com o servidor.';
            matriculaFeedback.textContent = errorMessage;
            matriculaFeedback.style.backgroundColor = '#dc3545';
        });
    });
    }
});
</script>
</body>
</html>