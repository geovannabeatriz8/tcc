<?php
session_start();
include "conexao.php";
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $cpf && $senha) {
        $hashSenha = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO professores (nome, email, cpf, senha) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$nome, $email, $cpf, $hashSenha])) {
            $msg = "Professor cadastrado com sucesso!";
        } else {
            $msg = "Erro ao cadastrar professor.";
        }
    } else {
        $msg = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastrar Professor</title>
<style>
    body { font-family: Arial; background: #eef2f3; padding: 20px; }
    form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px #aaa; }
    label { display: block; margin-top: 10px; }
    input { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
    button { margin-top: 15px; background: #1d44b8; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; }
    button:hover { background: #163791; }
    .msg { margin-top: 15px; color: green; }
    a { display: inline-block; margin-top: 10px; color: #1d44b8; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2>Cadastrar Professor</h2>

<form method="POST">
    <label for="nome">Nome</label>
    <input type="text" name="nome" id="nome" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>

    <label for="cpf">CPF</label>
    <input type="text" name="cpf" id="cpf" required>

    <label for="senha">Senha</label>
    <input type="password" name="senha" id="senha" required>

    <button type="submit">Cadastrar</button>
</form>

<?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<a href="painel_professores.php">← Voltar</a>

</body>
</html>
