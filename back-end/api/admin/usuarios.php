<?php
require_once 'header_admin.php'; // Inclui o header e o gatekeeper
require_once '../database/config.php'; // Inclui a conexão com o banco

// Consulta com LEFT JOIN para buscar todos os usuários e suas assinaturas ATIVAS
$sql = "
    SELECT
        u.id_usuario,
        u.nome,
        u.email,
        p.nome AS nome_plano,
        a.status AS status_assinatura,
        a.data_fim
    FROM
        usuarios u
    LEFT JOIN
        assinaturas a ON u.id_usuario = a.id_usuario AND a.status = 'ativa'
    LEFT JOIN
        planos p ON a.id_plano = p.id_plano
    ORDER BY
        u.nome;
";

$stmt = $conn->query($sql);
$lista_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h1>Gerenciar Usuários e Planos</h1>
</div>

<div class="page-content">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Plano Ativo</th>
                <th>Status</th>
                <th>Expira em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nome_plano'] ?? 'Nenhum'); ?></td>
                    <td>
                        <?php if ($usuario['status_assinatura']): ?>
                            <span class="badge status-<?php echo $usuario['status_assinatura']; ?>">
                                <?php echo htmlspecialchars(ucfirst($usuario['status_assinatura'])); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge status-inativo">Nenhum</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $usuario['data_fim'] ? date('d/m/Y', strtotime($usuario['data_fim'])) : 'N/A'; ?></td>
                    <td>
                        <a href="gerenciar_assinatura.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn-edit">Gerenciar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer_admin.php'; ?>