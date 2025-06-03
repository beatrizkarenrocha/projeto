<?php
session_start();
require_once('../conf/conexao.php');

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação dos dados
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    // Validações
    if (strlen($nome) < 3) {
        $erros[] = "Nome deve ter pelo menos 3 caracteres";
    }

    if (strlen($cnpj) != 14) {
        $erros[] = "CNPJ inválido";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido";
    }


    // Verifica se email ou cnpj já existem
    if (empty($erros)) {
        try {
            $sql = "SELECT id FROM tb_fornecedores WHERE email = ? OR cnpj = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $cnpj]);
            
            if ($stmt->rowCount() > 0) {
                $erros[] = "Email ou CNPJ já cadastrado";
            }
        } catch (PDOException $e) {
            $erros[] = "Erro ao verificar dados: " . $e->getMessage();
        }
    }

    // Se não houver erros, cadastra o usuário
    if (empty($erros)) {
        $dados = [
            'nome' => $nome,
            'cnpj' => $cnpj,
            'email' => $email,
            'endereco' => $endereco,

        ];

        try {
            $sql = "INSERT INTO tb_fornecedores (nome, cnpj, email,  endereco,) 
                    VALUES (:nome, :cnpj, :email, :endereco )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($dados);
            
            // Autentica o usuário automaticamente após cadastro
            $_SESSION['usuario_id'] = $pdo->lastInsertId();
            $_SESSION['usuario_nome'] = $dados['nome'];
            $_SESSION['usuario_tipo'] = $dados['tipo'];
            $_SESSION['usuario_email'] = $dados['email'];
            
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $erros[] = "Erro ao cadastrar: " . $e->getMessage();
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
        
        <form action="cad_fornecedores.php" method="POST" id="formCadastro" novalidate>
            <h1><i class="bi bi-person-plus"></i> Cadastro Fornecedor</h1>
            
            <div class="inputBox">
                <input type="text" name="nome" id="nome" class="inputUser <?php echo isset($erros['nome']) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
                <label for="nome" class="labelinput">Nome Completo</label>
                <div class="invalid-feedback">Nome deve ter pelo menos 3 caracteres</div>
            </div>
            
            <div class="inputBox">
                <input type="text" name="cnpj" id="cnpj" class="inputUser <?php echo isset($erros['cnpj']) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($_POST['cnpj'] ?? ''); ?>" required>
                <label for="cnpj" class="labelinput">Cnpj</label>
                <div class="invalid-feedback">Cnpj inválido</div>
            </div>
            
            <div class="inputBox">
                <input type="email" name="email" id="email" class="inputUser <?php echo isset($erros['email']) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                <label for="email" class="labelinput">Email</label>
                <div class="invalid-feedback">Email inválido</div>
            </div>
            
            <div class="inputBox">
                <input type="text" name="endereco" id="endereco" class="inputUser" 
                       value="<?php echo htmlspecialchars($_POST['endereco'] ?? ''); ?>" required>
                <label for="endereco" class="labelinput">Endereço</label>
            </div>
            
            
            
            <input type="submit" name="submit" id="submit" value="Cadastrar">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/jquery.inputmask.min.js"></script>
    <script>
        // Máscaras para CNPJ e Telefone
        document.addEventListener('DOMContentLoaded', function() {
            Inputmask('99.999.999/9999-99').mask(document.getElementById('cpf'));
        });
    </script>
</body>
</html>