<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// mensagem de sucesso/erro ao devolver livro
$msg = isset($_GET['msg']) ? $_GET['msg'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

// buscar os empréstimos
$sql = "
    SELECT e.id, e.data_retirada, e.data_devolucao, 
           l.titulo, a.nome AS nome_aluno
    FROM emprestimos e
    JOIN livros l ON e.livro_id = l.id
    JOIN alunos a ON e.aluno_id = a.id
    ORDER BY e.data_retirada DESC
";
$stmt = $conn->query($sql);
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Empréstimos de Livros</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f3; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #1d44b8; color: white; }
        tr:hover { background: #f1f1f1; }
        .btn { background: #1d44b8; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #163791; }
        .mensagem { margin-top: 15px; padding: 10px; border-radius: 5px; }
        .sucesso { background: #d4edda; color: #155724; }
        .erro { background: #f8d7da; color: #721c24; }
        a.voltar { display: inline-block; margin-top: 15px; text-decoration: none; color: #1d44b8; }
        a.voltar:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Empréstimos de Livros</h2>

    <?php if ($msg): ?>
        <div class="mensagem <?php echo ($type == 'error') ? 'erro' : 'sucesso'; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Livro</th>
                <th>Aluno</th>
                <th>Data Retirada</th>
                <th>Data Devolução</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($emprestimos) > 0): ?>
                <?php foreach ($emprestimos as $emp): ?>
                    <tr>
                        <td><?php echo $emp['id']; ?></td>
                        <td><?php echo htmlspecialchars($emp['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($emp['nome_aluno']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($emp['data_retirada'])); ?></td>
                        <td>
                            <?php
                                if ($emp['data_devolucao']) {
                                    echo date('d/m/Y', strtotime($emp['data_devolucao']));
                                } else {
                                    echo "Em aberto";
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ($emp['data_devolucao']) {
                                    echo "Finalizado";
                                } else {
                                    echo "Ativo";
                                }
                            ?>
                        </td>
                        <td>
                            <?php if (!$emp['data_devolucao']): ?>
                                <form method="post" action="devolver_livro.php" style="display:inline;">
                                    <input type="hidden" name="emprestimo_id" value="<?php echo $emp['id']; ?>">
                                    <button type="submit" class="btn">Devolver</button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Nenhum empréstimo encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="painel_livros.php" class="btn-voltar">← Voltar ao Painel de Livros</a>
</body>
</html>
