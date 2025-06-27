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
    button.excluir {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    button.excluir:hover {
        background-color: #c0392b;
    }
    #mensagem {
        margin-top: 20px;
        font-weight: bold;
        color: green;
    }
    .btn-voltar {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 18px;
        background-color: #1d44b8;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    .btn-voltar:hover {
        background-color: #163791;
    }
</style>
</head>
<body>
<h2>Lista de Livros</h2>
<table id="tabelaLivros">
    <thead>
        <tr><th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php
        $stmt = $conn->query("SELECT * FROM livros ORDER BY titulo");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr id='linha_".htmlspecialchars($row['id'])."'>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['autor']) . "</td>";
            echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
            echo "<td><button class='excluir' data-id='" . htmlspecialchars($row['id']) . "'>Excluir</button></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<div id="mensagem"></div>

<a href="painel_livros.php" class="btn-voltar">← Voltar ao Painel de Livros</a>

<script>
document.querySelectorAll('button.excluir').forEach(button => {
    button.addEventListener('click', () => {
        if (!confirm('Deseja realmente excluir este livro?')) return;

        const livroId = button.getAttribute('data-id');

        fetch('excluir_livro_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(livroId)
        })
        .then(response => response.json())
        .then(data => {
            const mensagemEl = document.getElementById('mensagem');
            if (data.success) {
                // Remove a linha da tabela
                const linha = document.getElementById('linha_' + livroId);
                if (linha) linha.remove();

                mensagemEl.style.color = 'green';
                mensagemEl.textContent = 'Livro excluído com sucesso!';
            } else {
                mensagemEl.style.color = 'red';
                mensagemEl.textContent = data.error || 'Erro ao excluir o livro.';
            }
        })
        .catch(() => {
            const mensagemEl = document.getElementById('mensagem');
            mensagemEl.style.color = 'red';
            mensagemEl.textContent = 'Erro na comunicação com o servidor.';
        });
    });
});
</script>
</body>
</html>
