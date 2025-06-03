<?php
session_start();
require_once('../conf/conexao.php');

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitização e validação básica
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações
    if (strlen($nome) < 3) {
        $erros['nome'] = "Nome deve ter pelo menos 3 caracteres.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = "Email inválido.";
    }

    if (strlen($senha) < 8) {
        $erros['senha'] = "Senha deve ter no mínimo 8 caracteres.";
    }

    if ($senha !== $confirmar_senha) {
        $erros['confirmar_senha'] = "As senhas não coincidem.";
    }


    // Inserção no banco
    if (empty($erros)) {
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
        ];

        try {
            $sql = "INSERT INTO administrador (nome,  email,  senha)
                    VALUES (:nome,  :email, :senha)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($dados);

            $_SESSION['admin_id'] = $pdo->lastInsertId();
            $_SESSION['admin_nome'] = $dados['nome'];
            $_SESSION['admin_email'] = $dados['email'];

            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $erros['db'] = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre-se | POWER PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(to right, #4e73df, #224abe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        
        .box {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        h1 {
            text-align: center;
            color: #224abe;
            margin-bottom: 25px;
        }
        
        .inputBox {
            position: relative;
            margin-bottom: 20px;
        }
        
        .inputUser {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .inputUser:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
            outline: none;
        }
        
        .labelinput {
            position: absolute;
            top: -10px;
            left: 15px;
            background: white;
            padding: 0 5px;
            font-size: 14px;
            color: #224abe;
            font-weight: bold;
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
            transition: all 0.3s;
            font-weight: bold;
        }
        
        #submit:hover {
            background-color: #224abe;
        }
        
        .btn-voltar {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #2e2c2c;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-voltar:hover {
            background-color: #4e73df;
            color: white;
        }
        
        .alert {
            margin-bottom: 20px;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <a href="../admin/index.php" class="btn-voltar">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    
    <div class="box">
        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger">
                <?php foreach ($erros as $erro): ?>
                    <p><?php echo htmlspecialchars($erro); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="cadastro_new_V2.php" method="POST" id="formCadastro" novalidate>
            <h1><i class="bi bi-person-plus"></i> Cadastro</h1>
            
            <div class="inputBox">
                <input type="text" name="nome" id="nome" class="inputUser <?php echo isset($erros['nome']) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
                <label for="nome" class="labelinput">Nome Completo</label>
                <div class="invalid-feedback">Nome deve ter pelo menos 3 caracteres</div>
            </div>
            
            <div class="inputBox">
                <input type="email" name="email" id="email" class="inputUser <?php echo isset($erros['email']) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                <label for="email" class="labelinput">Email</label>
                <div class="invalid-feedback">Email inválido</div>
            </div>
            
            <div class="inputBox">
                <input type="password" name="senha" id="senha" class="inputUser <?php echo isset($erros['senha']) ? 'is-invalid' : ''; ?>" 
                       minlength="8" required>
                <label for="senha" class="labelinput">Senha (mínimo 8 caracteres)</label>
                <div class="invalid-feedback">Senha deve ter no mínimo 8 caracteres</div>
            </div>
            
            <div class="inputBox">
                <input type="password" name="confirmar_senha" id="confirmar_senha" class="inputUser <?php echo isset($erros['confirmar_senha']) ? 'is-invalid' : ''; ?>" 
                       minlength="8" required>
                <label for="confirmar_senha" class="labelinput">Confirmar Senha</label>
                <div class="invalid-feedback">As senhas não coincidem</div>
            </div>
            
            <input type="submit" name="submit" id="submit" value="Cadastrar">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/jquery.inputmask.min.js"></script>
    <script>   
            // Validação do formulário
            document.getElementById('formCadastro').addEventListener('submit', function(e) {
                let senha = document.getElementById('senha').value;
                let confirmarSenha = document.getElementById('confirmar_senha').value;
                
                if (senha.length < 8) {
                    alert('A senha deve ter no mínimo 8 caracteres');
                    e.preventDefault();
                }
                
                if (senha !== confirmarSenha) {
                    alert('As senhas não coincidem');
                    e.preventDefault();
                }
            });
        
    </script>
</body>
</html>