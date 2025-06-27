<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$busca = '';
$resultados = [];

if (isset($_GET['q'])) {
    $busca = trim($_GET['q']);

    if ($busca !== '') {
        $sql = "SELECT * FROM alunos WHERE nome LIKE :busca OR email LIKE :busca ORDER BY nome";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['busca' => "%$busca%"]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Buscar Alunos</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #eef2f3;
        margin: 0;
        padding: 20px;
        color: #333;
    }
    .container {
        max-width: 700px;
        margin: 30px auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(29, 68, 184, 0.2);
    }
    h2 {
        color: #1d44b8;
        text-align: center;
        margin-bottom: 25px;
    }
    form {
        display: flex;
        margin-bottom: 25px;
        gap: 10px;
    }
    input[type="text"] {
        flex: 1;
        padding: 12px 15px;
        font-size: 1rem;
        border-radius: 8px;
        border: 1.5px solid #1d44b8;
        outline: none;
        transition: border-color 0.3s ease;
    }
    input[type="text"]:focus {
        border-color: #163791;
        box-shadow: 0 0 6px #163791;
    }
    button {
        background-color: #1d44b8;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0 25px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    button:hover {
        background-color: #163791;
        transform: translateY(-2px);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        padding: 12px 10px;
        border-bottom: 1px solid #ccc;
        text-align: left;
    }
    th {
        background-color: #1d44b8;
        color: white;
    }
    tr:hover {
        background-color: #f0f6ff;
    }
    a.back-btn {
        display: inline-block;
        margin-top: 20px;
        color: #1d44b8;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    a.back-btn:hover {
        color: #163791;
        text-decoration: underline;
    }
    .no-results {
        text-align: center;
        padding: 20px;
        color: #777;
        font-style: italic;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Buscar Alunos</h2>

        <form method="GET" action="">
            <input type="text" name="q" placeholder="Digite nome ou email do aluno..." value="<?php echo htmlspecialchars($busca); ?>" autocomplete="off" />
            <button type="submit">Pesquisar</button>
        </form>

        <?php if ($busca !== ''): ?>
            <?php if (count($resultados) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $aluno): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($aluno['id']); ?></td>
                                <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">Nenhum aluno encontrado para "<?php echo htmlspecialchars($busca); ?>"</p>
            <?php endif; ?>
        <?php endif; ?>

        <a href="painel_alunos.php" class="back-btn">← Voltar ao Painel de Alunos</a>
    </div>
</body>
</html>
