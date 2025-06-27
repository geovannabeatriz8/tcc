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
<title>Gerenciar Livros</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap');

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #c3cfe2, #c3cfe2 40%, #e6e9f0 100%);
    margin: 0; padding: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .container {
    background: #fff;
    max-width: 600px;
    width: 90%;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow:
      0 8px 24px rgba(0, 0, 0, 0.15),
      inset 0 0 60px #a0b9ff33;
    text-align: center;
  }

  h2 {
    color: #1d44b8;
    margin-bottom: 35px;
    font-weight: 700;
    font-size: 2.2rem;
    text-shadow: 1px 1px 3px #a3b1ffcc;
  }

  .menu {
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

  .menu a {
    background: linear-gradient(90deg, #1d44b8, #3656c0);
    color: white;
    text-decoration: none;
    padding: 14px 20px;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    box-shadow:
      0 4px 10px rgba(29, 68, 184, 0.3);
    transition:
      background 0.35s ease,
      transform 0.25s ease,
      box-shadow 0.25s ease;
    user-select: none;
    position: relative;
    overflow: hidden;
  }

  .menu a:hover,
  .menu a:focus {
    background: linear-gradient(90deg, #2f5ed4, #4872e8);
    transform: translateY(-3px);
    box-shadow:
      0 8px 20px rgba(29, 68, 184, 0.5);
  }

  .menu a:active {
    transform: translateY(-1px);
    box-shadow:
      0 6px 15px rgba(29, 68, 184, 0.4);
  }

  /* Emoji icon spacing */
  .menu a::before {
    content: attr(data-icon);
    margin-right: 10px;
    font-size: 1.3rem;
    vertical-align: middle;
  }

  /* Voltar ao painel - estilo diferenciado */
  .menu + .menu {
    margin-top: 30px;
  }

  .menu + .menu a {
    background: #e74c3c;
    box-shadow: 0 4px 12px #e74c3ccc;
  }

  .menu + .menu a:hover,
  .menu + .menu a:focus {
    background: #c0392b;
    box-shadow: 0 8px 22px #c0392bcc;
    transform: translateY(-3px);
  }

  @media (max-width: 480px) {
    .container {
      padding: 30px 20px;
    }

    .menu a {
      font-size: 1rem;
      padding: 12px 15px;
    }

    h2 {
      font-size: 1.8rem;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <h2>Gerenciar Livros</h2>
    <div class="menu">
      <a href="ver_livros.php" data-icon="📄">Ver Livros</a>
      <a href="cadastrar_livro.php" data-icon="➕">Cadastrar Livro</a>
      <a href="buscar_livro.php" data-icon="🔍">Buscar Livro</a>
      <a href="emprestimos_livros.php" data-icon="📚">Empréstimos Ativos</a>
      <a href="fazer_emprestimo.php" data-icon="📝">Fazer Empréstimo</a>
      <a href="relatorios_livros.php" data-icon="📊">Relatórios e Estatísticas</a>
      <a href="livros_atrasados.php" data-icon="⚠️">Livros Atrasados</a>
    </div>
    <div class="menu">
      <a href="painel.php">← Voltar ao Painel</a>
    </div>
  </div>
</body>
</html>
