<?php
require ('../../conf/conexao.php');

// Filtro opcional
$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

// Consulta com ou sem filtro
$sql = "SELECT * FROM produtos";
if ($filtro) {
    $sql .= " WHERE nome LIKE :filtro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':filtro', "%$filtro%");
} else {
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Produtos</h2>

        <!-- Formulário de busca -->
        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="filtro" class="form-control" placeholder="Buscar por nome" value="<?= htmlspecialchars($filtro) ?>">
                <button class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <!-- Botão novo produto -->
        <div class="text mt-3">
            <a href="../../public/cad_produtos.php" class="btn btn-success btn-sm">
                <i class="fas fa-user-plus me-1"></i> Novo produto
            </a>
        </div>
 <br>
        <!-- Tabela de produtos -->
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço (R$)</th>
                    <th>Estoque</th>
                    <th>Tamanho</th>
                    <th>Categoria</th>
                    <th>Data Cadastro</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td><?= htmlspecialchars($p['descricao']) ?></td>
                        <td><?= number_format($p['preco'], 2, ',', '.') ?></td>
                        <td><?= $p['estoque'] ?></td>
                        <td><?= htmlspecialchars($p['tamanho']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($p['data_cadastro'])) ?></td>
                        <td>
                            <?php if (!empty($p['imagem'])): ?>
                                <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="Imagem do Produto" style="width: 60px; height: auto;">
                            <?php else: ?>
                                -
                            <?php endif ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Nenhum produto encontrado.</td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</body>
</html>
