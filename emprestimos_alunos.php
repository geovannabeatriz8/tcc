<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Função para definir status do empréstimo
function statusEmprestimo($data_devolucao) {
    if (!$data_devolucao) return "Ativo";
    $hoje = new DateTime();
    $devolucao = new DateTime($data_devolucao);
    if ($hoje > $devolucao) {
        return "Atrasado";
    } else {
        return "Em dia";
    }
}

// Processa o termo de busca, se existir
$termoBusca = '';
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $termoBusca = trim($_GET['q']);
    $sql = "SELECT 
                alunos.nome AS aluno_nome,
                livros.titulo AS livro_titulo,
                emprestimos.data_retirada,
                emprestimos.data_devolucao
            FROM emprestimos
            INNER JOIN alunos ON emprestimos.aluno_id = alunos.id
            INNER JOIN livros ON emprestimos.livro_id = livros.id
            WHERE alunos.nome LIKE :termo OR livros.titulo LIKE :termo
            ORDER BY alunos.nome, emprestimos.data_retirada DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':termo' => "%$termoBusca%"]);
} else {
    $sql = "SELECT 
                alunos.nome AS aluno_nome,
                livros.titulo AS livro_titulo,
                emprestimos.data_retirada,
                emprestimos.data_devolucao
            FROM emprestimos
            INNER JOIN alunos ON emprestimos.aluno_id = alunos.id
            INNER JOIN livros ON emprestimos.livro_id = livros.id
            ORDER BY alunos.nome, emprestimos.data_retirada DESC";
    $stmt = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Empréstimos dos Alunos</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f4f8;
        margin: 0; padding: 20px;
        color: #333;
    }
    h2 {
        color: #1d44b8;
        text-align: center;
        margin-bottom: 20px;
    }
    form#buscaForm {
        max-width: 600px;
        margin: 0 auto 30px auto;
        display: flex;
        gap: 10px;
    }
    form#buscaForm input[type="text"] {
        flex: 1;
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    form#buscaForm input[type="text"]:focus {
        border-color: #1d44b8;
        outline: none;
    }
    form#buscaForm button {
        background-color: #1d44b8;
        border: none;
        color: white;
        padding: 0 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }
    form#buscaForm button:hover {
        background-color: #163791;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(29, 68, 184, 0.2);
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    th {
        background-color: #1d44b8;
        color: white;
        font-weight: 600;
        user-select: none;
    }
    tbody tr:hover {
        background-color: #f5faff;
    }
    .status-atrasado {
        color: #e74c3c;
        font-weight: bold;
    }
    .status-em-dia {
        color: #27ae60;
        font-weight: bold;
    }
    .status-ativo {
        color: #f39c12;
        font-weight: bold;
    }
    .voltar {
        max-width: 900px;
        margin: 30px auto;
        text-align: center;
    }
    .voltar a {
        color: #1d44b8;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        border: 2px solid #1d44b8;
        padding: 8px 18px;
        border-radius: 8px;
        transition: background-color 0.3s, color 0.3s;
        display: inline-block;
    }
    .voltar a:hover {
        background-color: #1d44b8;
        color: white;
    }
    .no-results {
        max-width: 900px;
        margin: 40px auto;
        text-align: center;
        font-size: 18px;
        color: #555;
        font-style: italic;
    }
</style>
</head>
<body>

<h2>Empréstimos dos Alunos</h2>

<form id="buscaForm" method="get" action="">
    <input type="text" name="q" placeholder="Pesquisar por nome do aluno ou título do livro" value="<?php echo htmlspecialchars($termoBusca); ?>" />
    <button type="submit">Pesquisar</button>
</form>

<?php if ($stmt->rowCount() > 0): ?>
<table>
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Livro</th>
            <th>Data Retirada</th>
            <th>Data Devolução Prevista</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stmt as $row):
            $status = statusEmprestimo($row['data_devolucao']);
            $classeStatus = $status === "Atrasado" ? "status-atrasado" : ($status === "Em dia" ? "status-em-dia" : "status-ativo");
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['aluno_nome']); ?></td>
            <td><?php echo htmlspecialchars($row['livro_titulo']); ?></td>
            <td><?php echo htmlspecialchars($row['data_retirada']); ?></td>
            <td><?php echo ($row['data_devolucao'] ? htmlspecialchars($row['data_devolucao']) : '-'); ?></td>
            <td class="<?php echo $classeStatus; ?>"><?php echo $status; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p class="no-results">Nenhum empréstimo encontrado para sua pesquisa.</p>
<?php endif; ?>

<div class="voltar">
    <a href="painel_alunos.php">← Voltar ao Painel de Alunos</a>
</div>

</body>
</html>
