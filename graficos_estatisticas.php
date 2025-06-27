<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Alunos cadastrados por mês
$alunos_por_mes = $conn->query("
    SELECT DATE_FORMAT(data_cadastro, '%Y-%m') AS mes, COUNT(*) AS total
    FROM alunos
    GROUP BY mes
    ORDER BY mes
")->fetchAll(PDO::FETCH_ASSOC);

// Top 5 empréstimos por aluno
$top_alunos = $conn->query("
    SELECT a.nome, COUNT(e.id) AS total
    FROM emprestimos e
    JOIN alunos a ON e.aluno_id = a.id
    WHERE e.data_devolucao IS NULL
    GROUP BY e.aluno_id
    ORDER BY total DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gráficos e Estatísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; background-color: #eef2f3; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        h2 { text-align: center; color: #1d44b8; margin-bottom: 30px; }
        canvas { margin: 30px auto; display: block; max-width: 100%; }
        a { display: inline-block; margin-top: 20px; text-align: center; color: #1d44b8; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gráficos e Estatísticas</h2>

        <canvas id="graficoAlunos"></canvas>
        <canvas id="graficoTopAlunos"></canvas>

        <a href="painel_alunos.php">← Voltar ao Painel</a>
    </div>

    <script>
        const alunosPorMes = <?php echo json_encode($alunos_por_mes); ?>;
        const topAlunos = <?php echo json_encode($top_alunos); ?>;

        // Gráfico de Alunos por Mês
        new Chart(document.getElementById('graficoAlunos'), {
            type: 'line',
            data: {
                labels: alunosPorMes.map(item => item.mes),
                datasets: [{
                    label: 'Alunos Cadastrados',
                    data: alunosPorMes.map(item => item.total),
                    borderColor: '#1d44b8',
                    backgroundColor: 'rgba(29,68,184,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Alunos Cadastrados por Mês'
                    }
                }
            }
        });

        // Gráfico Top 5 Empréstimos Ativos por Aluno
        new Chart(document.getElementById('graficoTopAlunos'), {
            type: 'bar',
            data: {
                labels: topAlunos.map(item => item.nome),
                datasets: [{
                    label: 'Empréstimos Ativos',
                    data: topAlunos.map(item => item.total),
                    backgroundColor: '#1d44b8'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Top 5 Alunos com Mais Empréstimos Ativos'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</body>
</html>

