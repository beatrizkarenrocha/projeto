<?php
session_start();
require_once('../conf/conexao.php');

if (!isset($_GET['id'])) {
    header('Location: fornecedores-index.php');
    exit;
}

$id = intval($_GET['id']);

// Delete fornecedor
$sql = "DELETE FROM fornecedores WHERE id_fornece = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header('Location: fornecedores-index.php');
exit;
?>
