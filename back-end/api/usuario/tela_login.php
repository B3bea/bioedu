<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../front-end/css/styleLogin.css">
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
                    <img src="../../../front-end/imagens/icone-entrar.png" alt="Ícone de uma pessoa entrando em sua conta online" width="300px">
                </div>
                <div class="lado2">
                    <h1 class="tituloEntrar">Entrar</h1>

                    <form id="formLogin" class="form">
                        <label for="txtNome">Usuário</label>
                        <input type="text" name="usuario" id="txtNome" placeholder="Insira seu nome de usuário" required>

                        <label for="txtSenha">Senha</label>
                        <input type="password" name="senha" id="txtSenha" placeholder="Insira sua senha" required>

                        <div class="cbRemember">
                            <input type="checkbox" name="remember" id="remember">
                            <p>Lembrar</p>
                        </div>

                        <button class="btn">Entrar</button>
                    </form>

                    <div class="complementosLogin">
                        <a href="#" class="esqueceuSenha">Esqueceu a senha?</a>

                        <p>Não possui uma conta? <a href="cadastrar.php">Cadastre-se</a></p>
                    </div>
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
<script src="../../../front-end/js/login.js"></script>
</html>