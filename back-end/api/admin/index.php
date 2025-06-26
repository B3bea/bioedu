<?php
require_once 'header_admin.php'; 
require_once '../database/config.php';   


$total_usuarios = $conn->query("SELECT count(id_usuario) FROM usuarios")->fetchColumn();


$assinaturas_ativas = $conn->query("SELECT count(id_assinatura) FROM assinaturas WHERE status = 'ativa'")->fetchColumn();

$stmt_recentes = $conn->query("SELECT nome, email FROM usuarios ORDER BY id_usuario DESC LIMIT 5");
$usuarios_recentes = $stmt_recentes->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-users"></i></div>
        <div class="stat-card-info">
            <h4>Total de Usuários</h4>
            <span><?php echo $total_usuarios; ?></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-gem"></i></div>
        <div class="stat-card-info">
            <h4>Assinaturas Ativas</h4>
            <span><?php echo $assinaturas_ativas; ?></span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-user-plus"></i></div>
        <div class="stat-card-info">
            <h4>Novos Cadastros (Recentes)</h4>
            <span><?php echo count($usuarios_recentes); ?></span>
        </div>
    </div>
</div>

<div class="dashboard-grid-secondary">
    <div class="panel">
        <div class="panel-header">
            <h3>Atividade Recente</h3>
        </div>
        <div class="panel-body">
            <ul class="activity-list">
                <?php foreach ($usuarios_recentes as $recente): ?>
                    <li>
                        <i class="fas fa-user"></i>
                        <div>
                            <strong>Novo usuário cadastrado:</strong> <?php echo htmlspecialchars($recente['nome']); ?>
                            <span><?php echo htmlspecialchars($recente['email']); ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h3>Ações Rápidas</h3>
        </div>
        <div class="panel-body quick-actions">
            <a href="usuarios.php" class="quick-action-btn">
                <i class="fas fa-users-cog"></i>
                <span>Gerenciar Usuários</span>
            </a>
            <a href="faturamento.php" class="quick-action-btn">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Ver Faturamento</span>
            </a>
        </div>
    </div>
</div>

<?php require_once 'footer_admin.php'; ?>