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
<title>Visualizar Professores</title>
<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        margin: 0;
        padding: 20px;
    }
    .container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-width: 1000px;
        margin: 30px auto;
        animation: fadeIn 0.6s ease;
    }
    h2 {
        text-align: center;
        color: #1d44b8;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 12px;
        text-align: left;
    }
    th {
        background: #1d44b8;
        color: white;
    }
    td {
        background: #f9f9f9;
    }
    a {
        color: #1d44b8;
        text-decoration: none;
        font-weight: bold;
    }
    a:hover {
        text-decoration: underline;
    }
    .btn-voltar {
        display: inline-block;
        margin-top: 20px;
        background: #1d44b8;
        color: white;
        padding: 10px 16px;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s;
    }
    .btn-voltar:hover {
        background: #163791;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>
<div class="container">
    <h2>Lista de Professores</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Disciplina</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $conn->query("SELECT * FROM professores ORDER BY nome");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
           
            echo "<td>
                    <a href='editar_professor.php?id={$row['id']}'>Editar</a> | 
                    <a href='excluir_professor.php?id={$row['id']}' onclick=\"return confirm('Deseja realmente excluir este professor?');\">Excluir</a>
                  </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="painel_professores.php" class="btn-voltar">← Voltar ao Painel</a>
</div>
</body>
</html>
