<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Data de hoje para comparação
$hoje = date("Y-m-d");

$sql = "
    SELECT e.id, l.titulo, l.autor, a.nome AS aluno_nome, e.data_retirada, e.data_prevista_devolucao
    FROM emprestimos e
    JOIN livros l ON e.livro_id = l.id
    LEFT JOIN alunos a ON e.aluno_id = a.id
    WHERE e.data_devolucao IS NULL
      AND e.data_prevista_devolucao < ?
    ORDER BY e.data_prevista_devolucao ASC
";
$stmt = $conn->prepare($sql);
$stmt->execute([$hoje]);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Livros Atrasados</title>
<style>
    body { font-family: Arial; background: #eef2f3; padding: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background: #c0392b; color: white; }
    tr.atrasado { background-color: #f8d7da; }
    a { text-decoration: none; color: #1d44b8; }
    a:hover { text-decoration: underline; }
</style>
</head>
<body>
<h2>Livros Atrasados</h2>
<table>
    <thead>
        <tr>
            <th>ID Empréstimo</th>
            <th>Livro</th>
            <th>Autor</th>
            <th>Aluno</th>
            <th>Data Retirada</th>
            <th>Data Prevista Devolução</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($registros): ?>
            <?php foreach ($registros as $r): ?>
            <tr class="atrasado">
                <td><?= htmlspecialchars($r['id']) ?></td>
                <td><?= htmlspecialchars($r['titulo']) ?></td>
                <td><?= htmlspecialchars($r['autor']) ?></td>
                <td><?= htmlspecialchars($r['aluno_nome'] ?? '---') ?></td>
                <td><?= htmlspecialchars($r['data_retirada']) ?></td>
                <td><?= htmlspecialchars($r['data_prevista_devolucao']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhum livro atrasado encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<a href="painel_livros.php">← Voltar</a>
</body>
</html>
