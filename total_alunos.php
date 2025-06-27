<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Consulta total de alunos
$stmt = $conn->query("SELECT COUNT(*) AS total_alunos FROM alunos");
$totalAlunos = $stmt->fetch(PDO::FETCH_ASSOC)['total_alunos'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Total de Alunos Cadastrados</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f4f8;
        color: #333;
        margin: 0; padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    .card {
        background: white;
        padding: 40px 60px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(29, 68, 184, 0.2);
        text-align: center;
        max-width: 400px;
        width: 100%;
    }
    .card h1 {
        font-size: 3.5rem;
        margin: 0;
        color: #1d44b8;
    }
    .card p {
        font-size: 1.5rem;
        margin-top: 10px;
        color: #555;
    }
    .btn-voltar {
        margin-top: 30px;
        text-decoration: none;
        color: #1d44b8;
        font-weight: 600;
        border: 2px solid #1d44b8;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s, color 0.3s;
        display: inline-block;
    }
    .btn-voltar:hover {
        background-color: #1d44b8;
        color: white;
    }
</style>
</head>
<body>

<div class="card">
    <h1><?php echo $totalAlunos; ?></h1>
    <p>Alunos cadastrados no sistema</p>
    <a href="painel_alunos.php" class="btn-voltar">← Voltar ao Painel de Alunos</a>
</div>

</body>
</html>
