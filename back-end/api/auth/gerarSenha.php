<?php
// Define a senha fixa
$senhaFixa = "MinhaSenhaFixa123";

// Gera a senha criptografada
$senhaCriptografada = password_hash($senhaFixa, PASSWORD_BCRYPT);

// Exibe a senha criptografada
echo "Senha criptografada: " . $senhaCriptografada;

?>
