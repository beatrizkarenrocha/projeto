<?php
require('../../conf/conexao.php');

$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

$sql = "SELECT * FROM fornecedores";
if ($filtro) {
    $sql .= " WHERE nome LIKE :filtro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':filtro', "%$filtro%");
} else {
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$fornecedores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fornecedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="p-4">
    <div class="container">
        <h2>Fornecedores</h2>

        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="filtro" class="form-control" placeholder="Buscar por nome" value="<?= htmlspecialchars($filtro) ?>">
                <button class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="../../public/cad_fornecedores.php" class="btn btn-success btn-sm">
                <i class="fas fa-user-plus me-1"></i> Novo Fornecedor
            </a>
        </div>

        <table class="table table-bordered table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($fornecedores) > 0): ?>
                <?php foreach ($fornecedores as $f): ?>
                    <tr>
                        <td><?= $f['id_fornece'] ?></td>
                        <td><?= $f['nome'] ?></td>
                        <td><?= $f['email'] ?></td>
                        <td><?= $f['telefone'] ?></td>
                        <td><?= $f['endereco'] ?></td>
                        <td>
                            <a href="fornecedores/edit.php?id=<?= $f['id_fornece'] ?>" class="btn btn-warning btn-sm">Editar</a>

                            <a href="delete.php?id=<?= $f['id_fornece'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir fornecedor?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum fornecedor encontrado.</td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</body>
</html>
