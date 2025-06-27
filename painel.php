<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nome'])) {
    header('Location: login.php');
    exit();
}
$nome_professor = htmlspecialchars($_SESSION['nome']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Principal</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            color: #333;
        }

        .header {
            background-color: #1d44b8;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #1d44b8;
        }

        .card p {
            color: #555;
        }

        .logout {
            margin-top: 40px;
            text-align: center;
        }

        .logout a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        .logout a:hover {
            text-decoration: underline;
        }

        a.card-link {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bem-vindo, <?php echo $nome_professor; ?>!</h1>
    </div>

    <div class="container">
        <div class="grid">
            <a href="painel_alunos.php" class="card-link">
                <div class="card">
                    <h3>Alunos</h3>
                    <p>Gerenciar alunos cadastrados</p>
                </div>
            </a>
            <a href="painel_professores.php" class="card-link">
                <div class="card">
                    <h3>Professores</h3>
                    <p>Gerenciar professores cadastrados</p>
                </div>
            </a>
            <a href="painel_livros.php" class="card-link">
                <div class="card">
                    <h3>Livros</h3>
                    <p>Gerenciar livros disponíveis</p>
                </div>
            </a>
        </div>

        <div class="logout">
            <a href="logout.php">Sair</a>
        </div>
    </div>
</body>
</html>
