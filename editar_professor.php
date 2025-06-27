<?php
// editar_professor.php
// Editar dados de um professor pelo ID

// Configurações do banco
$host = "localhost";
$dbname = "tcc";  // ajuste se necessário
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verifica se ID foi passado
if (!isset($_GET['id'])) {
    die("ID do professor não especificado.");
}

$id = intval($_GET['id']);

// Se o formulário foi enviado, atualiza os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($nome) || empty($email)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $sqlUpdate = "UPDATE professores SET nome = :nome, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $sucesso = "Professor atualizado com sucesso!";
        } else {
            $erro = "Erro ao atualizar professor.";
        }
    }
}

// Busca os dados atuais do professor para preencher o formulário
$sql = "SELECT * FROM professores WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    die("Professor não encontrado.");
}

$professor = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Professor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 20px;
            color: #333;
        }
        .container {
            background: #fff;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #2980b9;
            padding-bottom: 10px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            margin-top: 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #1f6391;
        }
        .msg-sucesso {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        .msg-erro {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        a.voltar {
            display: inline-block;
            margin-top: 15px;
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
        }
        a.voltar:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Professor</h2>

        <?php if (!empty($erro)): ?>
            <div class="msg-erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="msg-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($professor['nome']); ?>" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($professor['email']); ?>" required />

            <input type="submit" value="Salvar Alterações" />
        </form>

        <a href="ver_professores.php" class="voltar">← Voltar para Lista</a>
    </div>
</body>
</html>
