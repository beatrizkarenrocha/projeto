<?php
$host = "localhost";
$dbname = "db_powerpc";
$username = "root";
$pass = "";

try {
    // Cria conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilita exceções para erros
} catch (PDOException $e) {
    // Se ocorrer erro na conexão, exibe e encerra
    die("Erro na conexão: " . $e->getMessage());
}
?>