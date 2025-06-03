<?php
require ('../../conf/conexao.php');

$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

$sql = "SELECT * FROM pedidos";
if ($filtro) {
    $sql .= " WHERE status LIKE :filtro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':filtro', "%$filtro%");
} else {
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
    <div class="container">
        <h2>Pedidos</h2>

        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="filtro" class="form-control" placeholder="Filtrar por status" value="<?= htmlspecialchars($filtro) ?>">
                <button class="btn btn-primary">Buscar</button>
            </div>
        </form>
  <div class="text-center mt-3">
                                <a href="../public/cad_pedidos.php" class="btn btn-action btn-sm">
                                    <i class="fas fa-user-plus me-1"></i> Novo pedido
                                </a>
                            </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuário ID</th>
                    <th>Data do Pedido</th>
                    <th>Total (R$)</th>
                    <th>Status</th>
                    <th>Endereço de Entrega</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($pedidos) > 0): ?>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['usuario_id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
                        <td><?= number_format($p['total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars(ucfirst($p['status'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($p['endereco_entrega'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir pedido?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum pedido encontrado.</td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</body>
</html>
