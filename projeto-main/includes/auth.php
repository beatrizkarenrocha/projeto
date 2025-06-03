<?php
function loginAdmin($email, $senha, $pdo) {
    // Busca o usuário pelo e-mail
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha usando password_verify
        if (password_verify($senha, $usuario['senha'])) {
            // Armazena os dados do usuário na sessão com o campo correto: tipo
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'tipo' => $usuario['tipo'] ?? 'usuario' // <-- corrigido aqui
            ];
            return true;
        }
    }

    return false;
}
?>
