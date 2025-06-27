<?php
include "conexao.php";

$term = $_GET['term'] ?? '';
$term = $term . '%';

$stmt = $conn->prepare("SELECT nome FROM alunos WHERE nome LIKE ? ORDER BY nome LIMIT 10");
$stmt->execute([$term]);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($alunos);
