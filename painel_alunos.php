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
<meta charset="UTF-8">
<title>Gerenciar Alunos</title>
<style>
    :root {
        --azul: #1d44b8;
        --azul-escuro: #163791;
        --cinza-claro: #eef2f3;
        --cinza: #ccc;
        --branco: #fff;
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, var(--cinza-claro), #d0dce8);
        animation: fadeBody 1s ease-in;
    }
    @keyframes fadeBody {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .container {
        max-width: 700px;
        margin: 60px auto;
        background: var(--branco);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        animation: slideIn 0.8s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    h2 {
        text-align: center;
        color: var(--azul);
        margin-bottom: 30px;
        border-bottom: 2px solid var(--azul);
        padding-bottom: 10px;
    }
    .menu a {
        display: block;
        margin: 15px 0;
        text-align: center;
        background: var(--azul);
        color: var(--branco);
        padding: 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .menu a::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 0%;
        top: 0;
        left: 0;
        background: rgba(255,255,255,0.2);
        transition: all 0.3s ease;
        z-index: 0;
    }
    .menu a:hover::after {
        height: 100%;
    }
    .menu a:hover {
        background: var(--azul-escuro);
        transform: scale(1.02);
    }
    .menu a:active {
        transform: scale(0.97);
    }
    .menu a span {
        position: relative;
        z-index: 1;
    }
    @media(max-width: 768px){
        .container {
            margin: 30px 15px;
            padding: 20px;
        }
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Gerenciar Alunos</h2>
        <div class="menu">
            <a href="ver_alunos.php"><span>📄 Ver Alunos</span></a>
            <a href="cadastrar_aluno.php"><span>➕ Cadastrar Aluno</span></a>
            <a href="buscar_alunos.php"><span>🔍 Buscar Aluno</span></a>
            <a href="emprestimos_alunos.php"><span>📚 Empréstimos dos Alunos</span></a>
            <a href="total_alunos.php"><span>📊 Total de Alunos</span></a>
            <a href="filtrar_alunos.php"><span>⚙️ Filtros Avançados</span></a>
            <a href="graficos_estatisticas.php"><span>📈 Gráficos & Estatísticas</span></a>
        </div>
        <div class="menu">
            <a href="painel.php"><span>← Voltar ao Painel</span></a>
        </div>
    </div>
<script>
    // pequena animação de clique
    document.querySelectorAll('.menu a').forEach(btn => {
        btn.addEventListener('click', function(){
            btn.style.transform = "scale(0.95)";
            setTimeout(() => { btn.style.transform = "scale(1)"; }, 150);
        });
    });
</script>
</body>
</html>
