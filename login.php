<?php
session_start();
include "conexao.php"; // Inclua a conexão com seu banco de dados

// Se o formulário for enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se o campo 'email' e 'senha' existem antes de usá-los
    if (isset($_POST["email"]) && isset($_POST["senha"])) {
        $email = trim($_POST["email"]);
        $senha = trim($_POST["senha"]);

        // Verifica se os campos não estão vazios
        if (!empty($email) && !empty($senha)) {
            // Consulta o banco de dados para encontrar o usuário com o email informado
            $sql = "SELECT * FROM professores WHERE email = ?";
            $stmt = $conn->prepare($sql); // Aqui a variável $conn precisa ser definida
            $stmt->execute([$email]);

            // Se o usuário for encontrado
            if ($stmt->rowCount() > 0) {
                $professor = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verifica se a senha está correta utilizando password_verify
                if (password_verify($senha, $professor['senha'])) {
                    // Armazena informações do professor na sessão
                    $_SESSION['usuario_id'] = $professor['id'];
                    $_SESSION['nome'] = $professor['nome'];

                    // Redireciona para a página de painel após o login bem-sucedido
                    header("Location: painel.php");
                    exit();
                } else {
                    $erro = "Senha incorreta!";
                }
            } else {
                $erro = "Usuário não encontrado!";
            }
        } else {
            $erro = "Preencha todos os campos!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #aaa;
            width: 320px;
        }

        h2 {
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .erro {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="entrar">Entrar</button>
        </form>
        <?php if (isset($erro)) echo "<div class='erro'>$erro</div>"; ?>
    </div>
</body>
</html>
