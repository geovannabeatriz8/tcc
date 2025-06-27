<?php
include "conexao.php";
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$q = "%$q%";

$stmt = $conn->prepare("SELECT titulo FROM livros WHERE titulo LIKE ? ORDER BY titulo LIMIT 10");
$stmt->execute([$q]);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($livros);
