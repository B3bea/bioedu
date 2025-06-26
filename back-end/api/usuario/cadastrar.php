<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../front-end/css/styleCadastro.css">
    <link rel="icon" href="../../../favicon_io/favicon-16x16.png">  
    <title>Entrar - BIOEDU</title>
</head>
<body>
    <main>
        <div class="containerLogin">
            <div class="voltar">
                <a href="index.php"><img src="../../../front-end/imagens/arrow.png" alt="Seta para voltar à página inicial do site" class="imgSeta"></a>
            </div>

            <div class="lados">
                <div class="lado1">
                    <img src="../../../front-end/imagens/register.png" alt="Ícone representando um cadastro online" width="300px" class="img2">
                </div>
                <div class="lado2">
                    <h1 class="tituloEntrar">Cadastrar</h1>

                    <form id="novoCadastro" class="form">
                        <label for="txtNome">Nome completo</label>
                        <input type="text" name="nome" id="txtNome" placeholder="Insira seu nome completo" required>
                        
                        <label for="txtEmail">Email</label>
                        <input type="email" name="email" id="txtEmail" placeholder="Insira um email válido" required>

                        <label for="txtData">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="txtData">

                        <label for="txtEscolhaUsuario">Usuário</label>
                        <input type="text" name="usuario" id="txtEscolhaUsuario" placeholder="Digite um nome de usuário" required>

                        <label for="txtEscolhaSenha">Senha</label>
                        <input type="password" name="senha" id="txtEscolhaSenha" placeholder="Insira uma senha" required>

                        <button type="submit" class="btn">Concluir</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

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
</body>
<script src="../../../front-end/js/cadastro.js"></script>
</html>