<?php
include "conexao.php";

$term = $_GET['term'] ?? '';
$term = $term . '%';

$stmt = $conn->prepare("SELECT titulo FROM livros WHERE titulo LIKE ? ORDER BY titulo LIMIT 10");
$stmt->execute([$term]);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($livros);
