<?php
session_start();
require_once('../conf/conexao.php');

$erros = [];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: fornecedores-index.php");
    exit();
}

// Busca os dados atuais do fornecedor
try {
    $stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id_fornece = ?");
    $stmt->execute([$id]);
    $fornecedor = $stmt->fetch();

    if (!$fornecedor) {
        die("Fornecedor não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar fornecedor: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);

    if (strlen($nome) < 3) {
        $erros[] = "Nome deve ter pelo menos 3 caracteres";
    }
    if (strlen($cnpj) != 14) {
        $erros[] = "CNPJ inválido";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido";
    }

    if (empty($erros)) {
        try {
            $sql = "UPDATE fornecedores SET nome = :nome, cnpj = :cnpj, email = :email, telefone = :telefone, endereco = :endereco WHERE id_fornece = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':cnpj' => $cnpj,
                ':email' => $email,
                ':telefone' => $telefone,
                ':endereco' => $endereco,
                ':id' => $id
            ]);
            header("Location: fornecedores-index.php");
            exit();
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Fornecedor | POWER PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* o mesmo estilo do seu código original */
    </style>
</head>
<body>
<div class="box">
    <a href="fornecedores-index.php" class="btn-voltar">&larr; Voltar à Lista</a>

    <h1>Editar Fornecedor</h1>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? $fornecedor['nome']) ?>" />
        </div>
        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ</label>
            <input type="text" name="cnpj" id="cnpj" class="form-control" required value="<?= htmlspecialchars($_POST['cnpj'] ?? $fornecedor['cnpj']) ?>" />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? $fornecedor['email']) ?>" />
        </div>
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" name="telefone" id="telefone" class="form-control" required value="<?= htmlspecialchars($_POST['telefone'] ?? $fornecedor['telefone']) ?>" />
        </div>
        <div class="mb-3">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" name="endereco" id="endereco" class="form-control" required value="<?= htmlspecialchars($_POST['endereco'] ?? $fornecedor['endereco']) ?>" />
        </div>
        <button type="submit" id="submit">Salvar Alterações</button>
    </form>
</div>
</body>
</html>
