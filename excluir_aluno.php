<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Excluir Aluno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 20px;
            color: #333;
        }
        .container {
            background-color: #fff;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-back:hover {
            background-color: #1f6391;
        }
    </style>
</head>
<body>
<div class="container">

<?php
// Configuração de conexão
$host = "localhost";
$dbname = "tcc";
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verifica se o id foi passado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica se o aluno existe
    $sql = "SELECT * FROM alunos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Aluno existe, faz a exclusão
        $sqlDelete = "DELETE FROM alunos WHERE id = :id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmtDelete->execute()) {
            echo "<h2>Aluno excluído com sucesso!</h2>";
            echo "<a href='ver_alunos.php' class='btn-back'>Voltar para Lista</a>";
        } else {
            echo "<h2>Erro ao excluir o aluno.</h2>";
            echo "<a href='ver_alunos.php' class='btn-back'>Voltar para Lista</a>";
        }
    } else {
        echo "<h2>Aluno não encontrado.</h2>";
        echo "<a href='ver_alunos.php' class='btn-back'>Voltar para Lista</a>";
    }

} else {
    echo "<h2>ID do aluno não especificado.</h2>";
    echo "<a href='ver_alunos.php' class='btn-back'>Voltar para Lista</a>";
}

// Fecha conexão
$conn = null;
?>

</div>
</body>
</html>
