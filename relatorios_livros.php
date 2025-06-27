<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// total de livros cadastrados
$stmt = $conn->query("SELECT COUNT(*) AS total_livros FROM livros");
$total_livros = $stmt->fetch(PDO::FETCH_ASSOC)['total_livros'];

// total de livros emprestados atualmente
$stmt2 = $conn->query("SELECT COUNT(*) AS total_emprestados FROM emprestimos WHERE data_devolucao IS NULL");
$total_emprestados = $stmt2->fetch(PDO::FETCH_ASSOC)['total_emprestados'];

// top 5 livros mais emprestados
$sql_top = "
    SELECT l.titulo, COUNT(e.id) AS total
    FROM emprestimos e
    JOIN livros l ON e.livro_id = l.id
    GROUP BY e.livro_id
    ORDER BY total DESC
    LIMIT 5
";
$stmt3 = $conn->query($sql_top);
$top_livros = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Relatório de Livros</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: Arial, sans-serif; background-color: #eef2f3; margin: 0; padding: 20px; }
    .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    h2 { text-align: center; color: #1d44b8; }
    .stats { margin-top: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #1d44b8; border-radius: 5px; }
    .chart-container { margin-top: 40px; }
    canvas { background: #fff; border-radius: 10px; }
    .back { margin-top: 20px; text-align: center; }
    .back a { text-decoration: none; color: #1d44b8; }
    .back a:hover { text-decoration: underline; }
  </style>
</head>
<body>
<div class="container">
  <h2>Relatório de Livros</h2>

  <div class="stats">
    <p><strong>Total de livros cadastrados:</strong> <?php echo $total_livros; ?></p>
    <p><strong>Total de livros emprestados:</strong> <?php echo $total_emprestados; ?></p>
  </div>

  <div class="chart-container">
    <canvas id="graficoResumo"></canvas>
  </div>

  <h3 style="margin-top:40px;">Top 5 Livros Mais Emprestados</h3>
  <canvas id="graficoTopLivros"></canvas>

  <div class="back">
    <a href="painel_livros.php">← Voltar ao Painel de Livros</a>
  </div>
</div>

<script>
  // gráfico de resumo total
  const ctxResumo = document.getElementById('graficoResumo').getContext('2d');
  new Chart(ctxResumo, {
    type: 'doughnut',
    data: {
      labels: ['Cadastrados', 'Emprestados'],
      datasets: [{
        label: 'Livros',
        data: [<?php echo $total_livros; ?>, <?php echo $total_emprestados; ?>],
        backgroundColor: ['#1d44b8', '#ff6b6b']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // gráfico de top 5
  const ctxTop = document.getElementById('graficoTopLivros').getContext('2d');
  new Chart(ctxTop, {
    type: 'bar',
    data: {
      labels: [
        <?php
        foreach($top_livros as $livro) {
          echo "'" . addslashes($livro['titulo']) . "',";
        }
        ?>
      ],
      datasets: [{
        label: 'Total de Empréstimos',
        data: [
          <?php
          foreach($top_livros as $livro) {
            echo $livro['total'] . ",";
          }
          ?>
        ],
        backgroundColor: '#1d44b8'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      },
      plugins: { legend: { display: false } }
    }
  });
</script>
</body>
</html>
