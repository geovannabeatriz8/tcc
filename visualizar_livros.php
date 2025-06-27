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
<title>Visualizar Livros</title>
<style>
    body { font-family: Arial; background: #eef2f3; padding: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #1d44b8; color: white; }
    button.excluir-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
    }
    button.excluir-btn:hover {
        background-color: #c0392b;
    }
    #msg {
        margin-top: 15px;
        font-weight: bold;
        color: green;
    }
</style>
</head>
<body>
<h2>Lista de Livros</h2>
<table>
    <thead>
        <tr><th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Ações</th></tr>
    </thead>
    <tbody id="tabela-livros">
        <?php
        $stmt = $conn->query("SELECT * FROM livros ORDER BY titulo");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr id='livro-{$row['id']}'>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['autor']) . "</td>";
            echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
            echo "<td><button class='excluir-btn' data-id='{$row['id']}'>Excluir</button></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<div id="msg"></div>

<a href="painel_livros.php">← Voltar</a>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabela = document.getElementById('tabela-livros');
    const msg = document.getElementById('msg');

    tabela.addEventListener('click', (e) => {
        if (e.target.classList.contains('excluir-btn')) {
            const id = e.target.getAttribute('data-id');

            if (confirm('Tem certeza que deseja excluir este livro?')) {
                fetch('excluir_livro_ajax.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + encodeURIComponent(id)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        // Remove a linha da tabela
                        const tr = document.getElementById('livro-' + id);
                        if(tr) tr.remove();
                        msg.style.color = 'green';
                        msg.textContent = 'Livro excluído com sucesso!';
                    } else {
                        msg.style.color = 'red';
                        msg.textContent = data.error || 'Erro ao excluir o livro.';
                    }
                })
                .catch(() => {
                    msg.style.color = 'red';
                    msg.textContent = 'Erro na requisição.';
                });
            }
        }
    });
});
</script>

</body>
</html>
