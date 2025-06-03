<?php
session_start();
require_once('../conf/conexao.php');

$erros = [];

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
            $sql = "SELECT id_fornece FROM fornecedores WHERE email = ? OR cnpj = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $cnpj]);
            if ($stmt->rowCount() > 0) {
                $erros[] = "Email ou CNPJ já cadastrado";
            }
        } catch (PDOException $e) {
            $erros[] = "Erro ao verificar dados: " . $e->getMessage();
        }
    }

    if (empty($erros)) {
        try {
            $sql = "INSERT INTO fornecedores (nome, cnpj, email, telefone, endereco) VALUES (:nome, :cnpj, :email, :telefone, :endereco)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':cnpj' => $cnpj,
                ':email' => $email,
                ':telefone' => $telefone,
                ':endereco' => $endereco
            ]);
            header("Location: fornecedores-index.php");
            exit();
        } catch (PDOException $e) {
            $erros[] = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Fornecedor | POWER PC</title>
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
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: #224abe;
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
        }
        .input-group-text {
            background-color: #224abe;
            color: white;
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
        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="box">
    <a href="fornecedores-index.php" class="btn-voltar">&larr; Voltar à Lista</a>

    <h1><i class="bi bi-person-plus"></i> Cadastro Fornecedor</h1>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" name="nome" id="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" minlength="3" />
