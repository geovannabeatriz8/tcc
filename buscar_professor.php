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
<title>Buscar Professor</title>
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

<h2>Buscar Professor</h2>

<form method="GET">
    <input type="text" name="q" placeholder="Digite nome, email ou CPF" value="<?= htmlspecialchars($termo) ?>" required>
    <button type="submit">Buscar</button>
</form>

<?php if ($termo):
    $sql = "SELECT * FROM professores WHERE nome LIKE ? OR email LIKE ? OR cpf LIKE ? ORDER BY nome";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$termo%", "%$termo%", "%$termo%"]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if ($resultados): ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Nome</th><th>Email</th><th>CPF</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $professor): ?>
                    <tr>
                        <td><?= htmlspecialchars($professor['id']) ?></td>
                        <td><?= htmlspecialchars($professor['nome']) ?></td>
                        <td><?= htmlspecialchars($professor['email']) ?></td>
                        <td><?= htmlspecialchars($professor['cpf']) ?></td>
                        <td><a href="editar_professor.php?id=<?= $professor['id'] ?>">Editar</a> | <a href="excluir_professor.php?id=<?= $professor['id'] ?>" onclick="return confirm('Excluir este professor?');">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum professor encontrado.</p>
    <?php endif; ?>

<?php endif; ?>

<a href="painel_professores.php">← Voltar</a>

</body>
</html>
