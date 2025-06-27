<?php
session_start();
include "conexao.php";
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Visualizar Alunos</title>
<style>
    body { font-family: Arial; background: #eef2f3; padding: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #1d44b8; color: white; }
    a { color: #1d44b8; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
</head>
<body>
<h2>Lista de Alunos</h2>
<table>
    <thead>
        <tr><th>ID</th><th>Nome</th><th>Email</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php
        $stmt = $conn->query("SELECT * FROM alunos ORDER BY nome");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td><a href='editar_aluno.php?id={$row['id']}'>Editar</a> | <a href='excluir_aluno.php?id={$row['id']}' onclick=\"return confirm('Excluir este aluno?');\">Excluir</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<a href="painel_alunos.php">← Voltar</a>
</body>
</html>
