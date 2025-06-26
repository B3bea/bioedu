<?php
// controla as credencias de acesso ao banco de dados
$host = 'localhost';
$db = 'bioedu_usuario';
$port = 3306;
$user = 'root';
$pass = '';
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "servidor de banco: conexão bem sucessida !";
} catch(PDOException $e) {
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    exit;
}