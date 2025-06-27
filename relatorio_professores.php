<?php
session_start();
include "conexao.php";
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// consulta empréstimos agrupados por professor
$query = "
    SELECT p.nome AS professor, COUNT(e.id) AS total_emprestimos
    FROM professores p
    LEFT JOIN emprestimos e ON e.professor_id = p.id
    GROUP BY p.id
    ORDER BY total_emprestimos DESC
";
$stmt = $conn->prepare($query);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Relatório de Empréstimos por Professor</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        animation: fadein 0.8s ease;
    }
    @keyframes fadein {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    h2 {
        text-align: center;
        color: #1d44b8;
        margin-bottom: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ccc;
        text-align: center;
    }
    th {
        background-color: #1d44b8;
        color: white;
    }
    tr:hover {
        background-color: #f0f8ff;
    }
    .back-btn {
        display: inline-block;
        margin-top: 20px;
        background-color: #1d44b8;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .back-btn:hover {
        background-color: #163791;
    }
    canvas {
        margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Relatório de Empréstimos por Professor</h2>
    <table>
        <thead>
            <tr>
                <th>Professor</th>
                <th>Total de Empréstimos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($resultados as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['professor']) ?></td>
                <td><?= $row['total_emprestimos'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <canvas id="grafico"></canvas>

    <a href="painel_professores.php" class="back-btn">← Voltar ao Painel</a>
  </div>

  <script>
    const ctx = document.getElementById('grafico').getContext('2d');
    const grafico = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($resultados, 'professor')) ?>,
            datasets: [{
                label: 'Total de Empréstimos',
                data: <?= json_encode(array_column($resultados, 'total_emprestimos')) ?>,
                backgroundColor: '#1d44b8'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
  </script>
</body>
</html>
