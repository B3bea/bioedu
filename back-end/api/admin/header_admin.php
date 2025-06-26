<?php require_once 'admin_gatekeeper.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - BIOEDU</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <div class="admin-container">
        <aside class="sidebar" id="sidebar"> <div class="sidebar-header">
                <h2>BIOEDU</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'usuarios.php' ? 'active' : ''; ?>">
                        <a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'faturamento.php' ? 'active' : ''; ?>">
                        <a href="faturamento.php"><i class="fas fa-dollar-sign"></i> Faturamento</a>
                    </li>
                    <li>
                        <a href="../usuario/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="content"> 
            <div class="content-topbar">
                <button class="hamburger-btn" id="hamburger-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>