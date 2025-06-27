<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Professores</title>
    <style>
        /* Reset simples */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            background: white;
            max-width: 500px;
            width: 90%;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 12px 30px rgba(29, 68, 184, 0.3);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(29, 68, 184, 0.5);
        }

        h2 {
            color: #1d44b8;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .menu a {
            background-color: #1d44b8;
            color: white;
            padding: 14px 0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(29, 68, 184, 0.3);
            transition:
                background-color 0.3s ease,
                transform 0.2s ease,
                box-shadow 0.3s ease;
            user-select: none;
        }

        .menu a:hover,
        .menu a:focus {
            background-color: #163791;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(22, 55, 145, 0.6);
        }

        .menu a:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(29, 68, 184, 0.3);
        }

        .menu:last-child {
            margin-top: 30px;
        }

        .menu:last-child a {
            background-color: #e74c3c;
            font-weight: 700;
        }
        .menu:last-child a:hover,
        .menu:last-child a:focus {
            background-color: #c0392b;
            box-shadow: 0 8px 25px rgba(192, 57, 43, 0.7);
            transform: translateY(-3px);
        }

        /* Responsividade simples */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            h2 {
                font-size: 1.6rem;
            }
            .menu a {
                font-size: 1rem;
                padding: 12px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gerenciar Professores</h2>
        <div class="menu">
            <a href="ver_professores.php">📄 Ver Professores</a>
            <a href="cadastrar_professor.php">➕ Cadastrar Professor</a>
            <a href="buscar_professor.php">🔍 Buscar Professor</a>
            <a href="relatorio_professores.php">📊 Relatórios</a>
            <a href="filtrar_professores.php">🔎 Filtrar Professores</a>
        </div>
        <div class="menu">
            <a href="painel.php">← Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>
