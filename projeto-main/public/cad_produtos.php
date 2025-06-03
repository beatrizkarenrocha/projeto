<?php
session_start();
require_once('../conf/conexao.php');

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
    $estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);
    $imagem = filter_input(INPUT_POST, 'imagem', FILTER_SANITIZE_URL);
    $tamanho = filter_input(INPUT_POST, 'tamanho', FILTER_SANITIZE_STRING);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);

    // Validações
    if (!$nome || strlen($nome) < 3) {
        $erros[] = "Nome do produto deve ter pelo menos 3 caracteres.";
    }
    if (!$descricao || strlen($descricao) < 5) {
        $erros[] = "Descrição é obrigatória e deve conter no mínimo 5 caracteres.";
    }
    if ($preco === false || $preco < 0) {
        $erros[] = "Preço inválido.";
    }
    if ($estoque === false || $estoque < 0) {
        $erros[] = "Estoque inválido.";
    }
    if (!$tamanho) {
        $erros[] = "Tamanho é obrigatório.";
    }
    if (!$categoria) {
        $erros[] = "Categoria é obrigatória.";
    }

    if (empty($erros)) {
        try {
            $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, tamanho, categoria) 
                    VALUES (:nome, :descricao, :preco, :estoque, :imagem, :tamanho, :categoria)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':estoque' => $estoque,
                ':imagem' => $imagem,
                ':tamanho' => $tamanho,
                ':categoria' => $categoria
            ]);

            header("Location: produtos-index.php");
            exit();
        } catch (PDOException $e) {
            $erros[] = "Erro ao cadastrar produto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Produto | POWER PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(to right, #4e73df, #224abe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .box {
            background-color: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: #224abe;
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        #submit {
            background-color: #4e73df;
            width: 100%;
            border: none;
            padding: 12px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        #submit:hover {
            background-color: #224abe;
        }
        .btn-voltar {
            margin-bottom: 15px;
            display: inline-block;
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="box">
    <a href="/admin/index.php" class="btn-voltar">&larr; Voltar à Lista</a>

    <h1>Cadastro de Produto</h1>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" name="nome" id="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" class="form-control" required><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" name="preco" id="preco" class="form-control" required value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="estoque" class="form-label">Estoque</label>
            <input type="number" name="estoque" id="estoque" class="form-control" required value="<?= htmlspecialchars($_POST['estoque'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="imagem" class="form-label">URL da Imagem (opcional)</label>
            <input type="url" name="imagem" id="imagem" class="form-control" value="<?= htmlspecialchars($_POST['imagem'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="tamanho" class="form-label">Tamanho</label>
            <input type="text" name="tamanho" id="tamanho" class="form-control" required value="<?= htmlspecialchars($_POST['tamanho'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" name="categoria" id="categoria" class="form-control" required value="<?= htmlspecialchars($_POST['categoria'] ?? '') ?>">
        </div>

        <button type="submit" id="submit">Cadastrar Produto</button>
    </form>
</div>
</body>
</html>
