<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 20px;
            color: #333;
        }

        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #2980b9;
            padding-bottom: 10px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #1f6391;
        }

        .buttons {
            margin-top: 15px;
        }

        a.button-back,
        a.cancel-link {
            display: inline-block; 
            padding: 8px 15px; 
            border-radius: 5px; 
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        a.button-back {
            background-color: #2980b9; 
            color: white; 
        }

        a.button-back:hover {
            background-color: #1f6391;
        }

        a.cancel-link {
            background-color: #ccc; 
            color: #333;
        }

        a.cancel-link:hover {
            background-color: #999;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">

<?php
// CONEXÃO COM PDO
$host = "localhost";
$dbname = "tcc";
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<strong>Erro na conexão:</strong> " . $e->getMessage());
}

// VERIFICAR SE O ID FOI PASSADO
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // SE O FORMULÁRIO FOI ENVIADO (POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        // UPDATE no banco
        $sql = "UPDATE alunos SET nome = :nome, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<p><strong>Aluno atualizado com sucesso!</strong></p>";
            echo "<a href='detalhes_aluno.php?id=$id' class='button-back'>Ver Detalhes</a>";
            echo "<br><br>";
            echo "<a href='ver_alunos.php' class='button-back'>← Voltar para Lista</a>";
        } else {
            echo "<p>Erro ao atualizar o aluno.</p>";
        }

    } else {
        // Buscar os dados atuais do aluno
        $sql = "SELECT * FROM alunos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>

            <h2>Editar Aluno</h2>
            <form method="post">
                <label>Nome:</label><br>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required><br>

                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required><br>

                <input type="submit" value="Salvar Alterações">
            </form>

            <div class="buttons">
                <a href="ver_alunos.php" class="button-back">← Voltar para Lista</a>
                <a href="detalhes_aluno.php?id=<?php echo $id; ?>" class="cancel-link">Cancelar</a>
            </div>

            <?php
        } else {
            echo "<p>Aluno não encontrado.</p>";
        }
    }

} else {
    echo "<p>ID do aluno não especificado.</p>";
}

// FECHAR CONEXÃO
$conn = null;
?>

</div>
</body>
</html>
