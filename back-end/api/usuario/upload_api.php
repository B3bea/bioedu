<?php

session_start();
require_once '../database/config.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: perfil.php?status=upload_erro&msg=' . urlencode('Sessão expirada. Faça login novamente.'));
}

$apiUrlBase = 'http://127.0.0.1:8000/api/importar/';



?>