<?php
include "conexao.php";
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$q = "%$q%";

$stmt = $conn->prepare("SELECT nome FROM alunos WHERE nome LIKE ? ORDER BY nome LIMIT 10");
$stmt->execute([$q]);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($alunos);
