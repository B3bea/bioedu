<?php
require_once 'header_admin.php';
require_once '../database/config.php';

$id_usuario = $_GET['id'] ?? 0;
if (!$id_usuario) {
    echo "ID do usuário não fornecido."; exit;
}

// Busca os dados do usuário e de TODAS as suas assinaturas (ativas, expiradas, etc.)
$sql = "
    SELECT a.*, p.nome as nome_plano
    FROM assinaturas a
    JOIN planos p ON a.id_plano = p.id_plano
    WHERE a.id_usuario = ?
    ORDER BY a.data_inicio DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$assinaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca o nome do usuário para o título da página
$stmt_user = $conn->prepare("SELECT nome FROM usuarios WHERE id_usuario = ?");
$stmt_user->execute([$id_usuario]);
$nome_usuario = $stmt_user->fetchColumn();
?>

<div class="page-header-assinatura">
    <h1>Gerenciando Assinaturas de: <?php echo htmlspecialchars($nome_usuario); ?></h1>
    <a href="usuarios.php" class="btn-back">Voltar para a lista</a>
</div>

<div class="page-content-assinatura">
    <h3>Histórico de Assinaturas</h3>
    <table>
        <thead>
            <tr>
                <th>Plano</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Status Atual</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assinaturas)): ?>
                <tr><td colspan="5">Este usuário não possui nenhuma assinatura.</td></tr>
            <?php endif; ?>

            <?php foreach ($assinaturas as $assinatura): ?>
                <tr>
                    <td><?php echo htmlspecialchars($assinatura['nome_plano']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($assinatura['data_inicio'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($assinatura['data_fim'])); ?></td>
                    <td><span class="badge status-<?php echo $assinatura['status']; ?>"><?php echo ucfirst($assinatura['status']); ?></span></td>
                    <td>
                        <form action="atualizar_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_assinatura" value="<?php echo $assinatura['id_assinatura']; ?>">
                            <select name="novo_status">
                                <option value="ativa" <?php echo ($assinatura['status'] == 'ativa') ? 'selected' : ''; ?>>Ativa</option>
                                <option value="expirada" <?php echo ($assinatura['status'] == 'expirada') ? 'selected' : ''; ?>>Expirada</option>
                                <option value="cancelada" <?php echo ($assinatura['status'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                <option value="bloqueada" <?php echo ($assinatura['status'] == 'bloqueada') ? 'selected' : ''; ?>>Bloqueada</option>
                            </select>
                            <button type="submit" class="btn-save">Atualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer_admin.php'; ?>