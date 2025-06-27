<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$termo = "";
$resultados = [];

if (isset($_GET['q'])) {
    $termo = trim($_GET['q']);

    if (!empty($termo)) {
        try {
            // Consulta para buscar livro por título, autor ou ISBN
            $sql = "SELECT * FROM livros WHERE titulo LIKE :termo OR autor LIKE :termo OR isbn LIKE :termo ORDER BY titulo ASC";
            $stmt = $conn->prepare($sql);
            $likeTermo = "%$termo%";
            $stmt->bindParam(':termo', $likeTermo, PDO::PARAM_STR);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na busca: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Buscar Livros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #aaa;
        }
        input[type="text"] {
            width: calc(100% - 110px);
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
            float: left;
        }
        button {
            width: 90px;
            padding: 12px;
            margin-left: 10px;
            border-radius: 6px;
            border: none;
            background-color: #1d44b8;
            color: white;
            font-size: 16px;
            cursor: pointer;
            float: left;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #163791;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            clear: both;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #1d44b8;
            color: white;
        }
        img {
            max-width: 60px;
        }
        .voltar {
            margin-top: 20px;
            text-align: center;
            clear: both;
        }
        .voltar a {
            text-decoration: none;
            color: #1d44b8;
            font-weight: bold;
        }
        .voltar a:hover {
            text-decoration: underline;
        }
        /* Clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Buscar Livros</h2>
        <form method="GET" class="clearfix">
            <input type="text" name="q" placeholder="Digite título, autor ou ISBN" value="<?php echo htmlspecialchars($termo); ?>" autofocus />
            <button type="submit">Pesquisar</button>
        </form>

        <?php if ($termo !== ''): ?>
            <?php if (count($resultados) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $livro): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($livro['imagem_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($livro['imagem_url']); ?>" alt="Imagem do livro" />
                                    <?php else: ?>
                                        Sem imagem
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($livro['autor']); ?></td>
                                <td><?php echo htmlspecialchars($livro['isbn']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum livro encontrado para "<?php echo htmlspecialchars($termo); ?>"</p>
            <?php endif; ?>
        <?php endif; ?>

        <div class="voltar">
        <a href="painel_livros.php" class="btn-voltar">← Voltar ao Painel de Livros</a>

        </div>
    </div>
</body>
</html>
