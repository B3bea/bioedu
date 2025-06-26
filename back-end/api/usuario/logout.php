<?php
// 1. Inicia a sessão
// É fundamental iniciar a sessão para poder manipulá-la.
session_start();

// 2. Limpa todas as variáveis da sessão
// É uma boa prática esvaziar o array da sessão para remover todos os dados guardados.
$_SESSION = array();

// 3. Destrói a sessão
// Este é o passo principal. Ele remove o arquivo da sessão do servidor e invalida o ID da sessão
// que estava no cookie do navegador do usuário.
session_destroy();

// 4. Redireciona para a página de login
// Após o logout, o usuário deve ser enviado para uma página pública,
// geralmente a de login. O exit() garante que o script pare a execução aqui.
header("Location: index.php");
exit();
?>