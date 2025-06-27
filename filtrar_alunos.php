<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Recebe os filtros do formulário
$status = isset($_GET['status']) ? $_GET['status'] : '';
$pendentes = isset($_GET['pendentes']) ? $_GET['pendentes'] : '';

// Monta a query base
$query = "SELECT a.id, a.nome, a.email, a.status,
    CASE 
        WHEN EXISTS (
            SELECT 1 FROM emprestimos e 
            WHERE e.aluno_id = a.id 
              AND (e.data_devolucao IS NULL OR e.data_devolucao > NOW())
        ) THEN 'Sim' ELSE 'Não' 
    END AS possui_emprestimo_pendente
FROM alunos a WHERE 1=1 ";

// Parâmetros para a query preparada
$params = [];

// Filtra pelo status
if ($status === 'ativo' || $status === 'inativo') {
    $query .= " AND a.status = ? ";
    $params[] = $status;
}

// Filtra por empréstimos pendentes
if ($pendentes === 'sim') {
    $query .= " AND EXISTS (
        SELECT 1 FROM emprestimos e 
        WHERE e.aluno_id = a.id 
          AND (e.data_devolucao IS NULL OR e.data_devolucao > NOW())
    ) ";
} elseif ($pendentes === 'nao') {
    $query .= " AND NOT EXISTS (
        SELECT 1 FROM emprestimos e 
        WHERE e.aluno_id = a.id 
          AND (e.data_devolucao IS NULL OR e.data_devolucao > NOW())
    ) ";
}

// Ordena por nome
$query .= " ORDER BY a.nome";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Filtrar Alunos</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f7f9fc;
        padding: 20px;
        color: #333;
    }
    .container {
        max-width: 900px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(29, 68, 184, 0.15);
    }
    h2 {
        color: #1d44b8;
        margin-bottom: 20px;
    }
    form {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        align-items: center;
        flex-wrap: wrap;
    }
    label {
        font-weight: 600;
        color: #1d44b8;
    }
    select {
        padding: 8px 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
        min-width: 160px;
        cursor: pointer;
        transition: border-color 0.3s;
    }
    select:hover {
        border-color: #1d44b8;
    }
    button {
        background-color: #1d44b8;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 700;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #163791;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #1d44b8;
        color: white;
    }
    tr:hover {
        background-color: #f1f5fc;
    }
    .btn-voltar {
        margin-top: 20px;
        display: inline-block;
        color: #1d44b8;
        text-decoration: none;
        font-weight: 600;
        border: 2px solid #1d44b8;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s, color 0.3s;
    }
    .btn-voltar:hover {
        background-color: #1d44b8;
        color: white;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Filtrar Alunos</h2>
    <form method="GET" action="filtrar_alunos.php">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="" <?php if ($status === '') echo 'selected'; ?>>Todos</option>
            <option value="ativo" <?php if ($status === 'ativo') echo 'selected'; ?>>Ativo</option>
            <option value="inativo" <?php if ($status === 'inativo') echo 'selected'; ?>>Inativo</option>
        </select>

        <label for="pendentes">Empréstimos Pendentes:</label>
        <select name="pendentes" id="pendentes">
            <option value="" <?php if ($pendentes === '') echo 'selected'; ?>>Todos</option>
            <option value="sim" <?php if ($pendentes === 'sim') echo 'selected'; ?>>Com Empréstimos Pendentes</option>
            <option value="nao" <?php if ($pendentes === 'nao') echo 'selected'; ?>>Sem Empréstimos Pendentes</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Status</th>
                <th>Empréstimos Pendentes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($alunos) > 0): ?>
                <?php foreach ($alunos as $aluno): ?>
                <tr>
                    <td><?php echo htmlspecialchars($aluno['id']); ?></td>
                    <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                    <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($aluno['status'])); ?></td>
                    <td><?php echo $aluno['possui_emprestimo_pendente']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">Nenhum aluno encontrado com os filtros aplicados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="painel_alunos.php" class="btn-voltar">← Voltar ao Painel de Alunos</a>
</div>

</body>
</html>
