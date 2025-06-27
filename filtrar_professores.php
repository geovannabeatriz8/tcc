<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Recebe filtro do formulário (ou vazio)
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Monta a query base
$sql = "SELECT * FROM professores WHERE 1=1 ";
$params = [];

// Se filtrou pelo status, adiciona no SQL e no array de parâmetros
if ($status === 'ativo' || $status === 'inativo') {
    $sql .= " AND status = ? ";
    $params[] = $status;
}

$sql .= " ORDER BY nome";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$professores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Filtrar Professores</title>
<style>
    body { font-family: Arial; background: #eef2f3; padding: 20px; }
    .container { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    h2 { color: #1d44b8; text-align: center; }
    form { margin-bottom: 20px; }
    select, button {
        padding: 8px 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }
    button {
        background-color: #1d44b8;
        color: white;
        border: none;
        cursor: pointer;
        margin-left: 10px;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #163791;
    }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
    th { background-color: #1d44b8; color: white; }
</style>
</head>
<body>
<div class="container">
    <h2>Filtrar Professores</h2>

    <form method="GET" action="filtrar_professores.php">
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="" <?= $status == '' ? 'selected' : '' ?>>Todos</option>
            <option value="ativo" <?= $status == 'ativo' ? 'selected' : '' ?>>Ativo</option>
            <option value="inativo" <?= $status == 'inativo' ? 'selected' : '' ?>>Inativo</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($professores) > 0): ?>
                <?php foreach ($professores as $professor): ?>
                <tr>
                    <td><?= htmlspecialchars($professor['id']) ?></td>
                    <td><?= htmlspecialchars($professor['nome']) ?></td>
                    <td><?= htmlspecialchars($professor['cpf']) ?></td>
                    <td><?= htmlspecialchars($professor['email']) ?></td>
                    <td><?= htmlspecialchars($professor['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">Nenhum professor encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="painel_professores.php">← Voltar ao Painel de Professores</a></p>
</div>
</body>
</html>
