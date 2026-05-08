<?php
include 'templates/header.php';
require 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];

// Quantidade de livros lidos por ano
$lidos_por_ano_stmt = $pdo->prepare("SELECT YEAR(data_adicao) as ano, COUNT(*) as total FROM livros WHERE id_usuario = ? AND andamento = 'Lido' GROUP BY ano ORDER BY ano ASC");
$lidos_por_ano_stmt->execute([$id_usuario]);
$lidos_por_ano = $lidos_por_ano_stmt->fetchAll(PDO::FETCH_ASSOC);

// Top 3 autores
$top_autores_stmt = $pdo->prepare("SELECT autor, COUNT(*) as total FROM livros WHERE id_usuario = ? GROUP BY autor ORDER BY total DESC LIMIT 3");
$top_autores_stmt->execute([$id_usuario]);
$top_autores = $top_autores_stmt->fetchAll(PDO::FETCH_ASSOC);

// Média de notas
$media_notas_stmt = $pdo->prepare("SELECT AVG(nota) as media FROM livros WHERE id_usuario = ? AND nota > 0");
$media_notas_stmt->execute([$id_usuario]);
$media_notas = $media_notas_stmt->fetch(PDO::FETCH_ASSOC);

// Passa dados para o JavaScript
$chart_lidos_labels = json_encode(array_column($lidos_por_ano, 'ano'));
$chart_lidos_data = json_encode(array_column($lidos_por_ano, 'total'));

$chart_autores_labels = json_encode(array_column($top_autores, 'autor'));
$chart_autores_data = json_encode(array_column($top_autores, 'total'));
?>

<div class="container page-container">
    <h1>Estatísticas de Leitura</h1>
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Livros Lidos por Ano</h3>
            <canvas id="lidosPorAnoChart"></canvas>
        </div>
        <div class="stat-card">
            <h3>Top 3 Autores</h3>
            <canvas id="topAutoresChart"></canvas>
        </div>
        <div class="stat-card">
            <h3>Média das Suas Notas</h3>
            <div class="media-nota">
                <?= number_format($media_notas['media'] ?? 0, 1, ',') ?> <i class="fa-solid fa-star"></i>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gráfico de Livros Lidos por Ano
    const ctxLidos = document.getElementById('lidosPorAnoChart');
    new Chart(ctxLidos, {
        type: 'bar',
        data: {
            labels: <?= $chart_lidos_labels ?>,
            datasets: [{
                label: 'Livros Lidos',
                data: <?= $chart_lidos_data ?>,
                backgroundColor: '#004d4d'
            }]
        }
    });

    // Gráfico de Top Autores
    const ctxAutores = document.getElementById('topAutoresChart');
    new Chart(ctxAutores, {
        type: 'pie',
        data: {
            labels: <?= $chart_autores_labels ?>,
            datasets: [{
                label: 'Livros',
                data: <?= $chart_autores_data ?>,
                backgroundColor: ['#004d4d', '#E07A5F', '#3D405B']
            }]
        }
    });
});
</script>

<?php include 'templates/footer.php'; ?>