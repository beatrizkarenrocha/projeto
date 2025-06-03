<?php
session_start();
require_once('../conf/conexao.php');

$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? '';
    $estoque = $_POST['estoque'] ?? '';
    $tamanho = trim($_POST['tamanho'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $imagem_nome = null;

    // Validações básicas
    if (strlen($nome) < 3) {
        $erros[] = "Nome deve ter ao menos 3 caracteres";
    }
    if (strlen($descricao) < 5) {
        $erros[] = "Descrição muito curta";
    }
    if (!is_numeric($preco) || $preco < 0) {
        $erros[] = "Preço inválido";
    }
    if (!filter_var($estoque, FILTER_VALIDATE_INT) || $estoque < 0) {
        $erros[] = "Estoque inválido";
    }
    if (strlen($tamanho) < 1) {
        $erros[] = "Tamanho é obrigatório";
    }
    if (strlen($categoria) < 1) {
        $erros[] = "Categoria é obrigatória";
    }

    // Upload imagem (opcional)
    if (!empty($_FILES['imagem']['name'])) {
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['imagem']['type'], $permitidos)) {
            $erros[] = "Tipo de imagem não suportado (aceito: jpeg, png, gif)";
        } elseif ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
            $erros[] = "Imagem muito grande (máx 2MB)";
        } else {
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagem_nome = uniqid() . "." . $ext;
            $destino = __DIR__ . '/../uploads/' . $imagem_nome;
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                $erros[] = "Erro ao salvar imagem";
            }
        }
    }

    if (empty($erros)) {
        $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, tamanho, categoria) VALUES (:nome, :descricao, :preco, :estoque, :imagem, :tamanho, :categoria)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':estoque' => $estoque,
            ':imagem' => $imagem_nome,
            ':tamanho' => $tamanho,
            ':categoria' => $categoria,
        ]);
        header("Location: produtos-index.php");
        exit();
    }
} else {
    $nome = $descricao = $preco = $estoque = $tamanho = $categoria = '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Adicionar Produto | POWER PC</title>
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
        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="box">
    <a href="produtos-index.php" class="btn-voltar">&larr; Voltar à Lista</a>

    <h1><i class="bi bi-plus-circle"></i> Adicionar Produto</h1>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required minlength="3" value="<?= htmlspecialchars($nome) ?>" />
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required minlength="5"><?= htmlspecialchars($descricao) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" min="0" name="preco" id="preco" class="form-control" required value="<?= htmlspecialchars($preco) ?>" />
        </div>

        <div class="mb-3">
            <label for="estoque" class="form-label">Estoque</label>
            <input type="number" min="0" name="estoque" id="estoque" class="form-control" required value="<?= htmlspecialchars($estoque) ?>" />
        </div>

        <div class="mb-3">
            <label for="tamanho" class="form-label">Tamanho</label>
            <input type="text" name="tamanho" id="tamanho" class="form-control" required value="<?= htmlspecialchars($tamanho) ?>" />
        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" name="categoria" id="categoria" class="form-control" required value="<?= htmlspecialchars($categoria) ?>" />
        </div>

        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem (JPEG, PNG ou GIF até 2MB)</label>
            <input type="file" name="imagem" id="imagem" class="form-control" accept=".jpeg,.jpg,.png,.gif" />
        </div>

        <button type="submit" id="submit">Salvar Produto</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
