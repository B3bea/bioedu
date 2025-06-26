<?php
require_once 'header_admin.php';
require_once '../database/config.php';

// Faturamento Total e Total de Assinaturas

$query_total = "
    SELECT
        SUM(p.preco) AS faturamento_total,
        COUNT(a.id_assinatura) AS total_assinaturas
    FROM assinaturas a
    JOIN planos p ON a.id_plano = p.id_plano
";
$stmt_total = $conn->query($query_total);
$indicadores_totais = $stmt_total->fetch(PDO::FETCH_ASSOC);


// Indicadores Chave: Faturamento do Mês Atual
$query_mes_atual = "
    SELECT SUM(p.preco) AS faturamento_mes
    FROM assinaturas a
    JOIN planos p ON a.id_plano = p.id_plano
    WHERE MONTH(a.data_inicio) = MONTH(CURDATE()) AND YEAR(a.data_inicio) = YEAR(CURDATE())
";
$stmt_mes_atual = $conn->query($query_mes_atual);
$faturamento_mes_atual = $stmt_mes_atual->fetchColumn();


// Desempenho por Plano
$query_por_plano = "
    SELECT
        p.nome,
        COUNT(a.id_assinatura) AS qtd_vendas,
        SUM(p.preco) AS faturamento_por_plano
    FROM assinaturas a
    JOIN planos p ON a.id_plano = p.id_plano
    GROUP BY p.nome
    ORDER BY faturamento_por_plano DESC
";
$stmt_por_plano = $conn->query($query_por_plano);
$desempenho_planos = $stmt_por_plano->fetchAll(PDO::FETCH_ASSOC);


// Últimas 10 Assinaturas (Transações Recentes)
$query_recentes = "
    SELECT
        u.nome AS nome_usuario,
        p.nome AS nome_plano,
        p.preco,
        a.data_inicio
    FROM assinaturas a
    JOIN usuarios u ON a.id_usuario = u.id_usuario
    JOIN planos p ON a.id_plano = p.id_plano
    ORDER BY a.data_inicio DESC
    LIMIT 10
";
$stmt_recentes = $conn->query($query_recentes);
$transacoes_recentes = $stmt_recentes->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h1>Relatório de Faturamento</h1>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-card-info">
            <h4>Faturamento Total</h4>
            <span>R$ <?php echo number_format($indicadores_totais['faturamento_total'] ?? 0, 2, ',', '.'); ?></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-card-info">
            <h4>Faturamento (Mês Atual)</h4>
            <span>R$ <?php echo number_format($faturamento_mes_atual ?? 0, 2, ',', '.'); ?></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-file-signature"></i></div>
        <div class="stat-card-info">
            <h4>Total de Assinaturas</h4>
            <span><?php echo $indicadores_totais['total_assinaturas'] ?? 0; ?></span>
        </div>
    </div>
</div>

<div class="dashboard-grid-secondary" style="margin-top: 30px;">
    <div class="panel">
        <div class="panel-header"><h3>Desempenho por Plano</h3></div>
        <div class="panel-body">
            <table>
                <thead>
                    <tr>
                        <th>Nome do Plano</th>
                        <th>Qtd. de Vendas</th>
                        <th>Receita Gerada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($desempenho_planos as $plano): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($plano['nome']); ?></td>
                        <td><?php echo $plano['qtd_vendas']; ?></td>
                        <td>R$ <?php echo number_format($plano['faturamento_por_plano'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><h3>Últimas Transações</h3></div>
        <div class="panel-body">
            <table>
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Plano</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacoes_recentes as $transacao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transacao['nome_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($transacao['nome_plano']); ?></td>
                        <td>R$ <?php echo number_format($transacao['preco'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer_admin.php'; ?> 