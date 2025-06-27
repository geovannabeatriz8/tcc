<?php
session_start();
include "conexao.php";
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$termo = $_GET['q'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Buscar Aluno</title>
<style>
    body { font-family: Arial; background: #eef2f3; padding: 20px; }
    input[type="text"] { width: 300px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
    button { padding: 8px 15px; background: #1d44b8; color: white; border: none; border-radius: 5px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #1d44b8; color: white; }
    a { color: #1d44b8; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2>Buscar Aluno</h2>

<form method="GET">
    <input type="text" name="q" placeholder="Digite nome ou email" value="<?= htmlspecialchars($termo) ?>" required>
    <button type="submit">Buscar</button>
</form>

<?php if ($termo): 
    $stmt = $conn->prepare("SELECT * FROM alunos WHERE nome LIKE ? OR email LIKE ? ORDER BY nome");
    $stmt->execute(["%$termo%", "%$termo%"]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if ($resultados): ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Nome</th><th>Email</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['id']) ?></td>
                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td><?= htmlspecialchars($aluno['email']) ?></td>
                        <td><a href="editar_aluno.php?id=<?= $aluno['id'] ?>">Editar</a> | <a href="excluir_aluno.php?id=<?= $aluno['id'] ?>" onclick="return confirm('Excluir este aluno?');">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum aluno encontrado.</p>
    <?php endif; ?>

<?php endif; ?>

<a href="painel_alunos.php">← Voltar</a>

</body>
</html>
