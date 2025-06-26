<?php
// É essencial iniciar a sessão em qualquer script que vá usar a variável $_SESSION.
// Como este será o primeiro 'require' em muitas páginas, colocamos aqui.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A CONDIÇÃO DE SEGURANÇA:
// Se a sessão 'user_id' NÃO existir (não está logado)
// OU o 'user_type' que guardamos no login NÃO for 'admin'...
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    
    // ...então o acesso é negado.
    
    // Limpa qualquer sessão que possa existir, por segurança.
    session_destroy();
    
    // Redireciona o usuário para a página de login com uma mensagem de erro.
    // O '../' significa "voltar uma pasta", para sair de /admin/ e encontrar o login.php na raiz.
    header('Location: ../login.php?erro=acesso_negado');
    
    // GARANTE que o script pare de ser executado imediatamente após o redirecionamento.
    // Isso é uma medida de segurança crucial.
    exit();
}

// Se o script passar por essa verificação, significa que o usuário é um admin logado
// e a página que incluiu este arquivo pode continuar a ser carregada normalmente.
?>