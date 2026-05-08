<?php
// Inclui o cabeçalho e a conexão
include 'templates/header.php';
require 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];

//DADOS PARA O DASHBOARD 

//Resumo Rápido: Total de livros
$total_livros_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM livros WHERE id_usuario = ?");
$total_livros_stmt->execute([$id_usuario]);
$total_livros = $total_livros_stmt->fetchColumn();

//Resumo Rápido: Livros sendo lidos
$lendo_agora_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM livros WHERE id_usuario = ? AND andamento = 'Lendo'");
$lendo_agora_stmt->execute([$id_usuario]);
$lendo_agora = $lendo_agora_stmt->fetchColumn();

//Lista: Continue Lendo (limite de 3)
$continue_lendo_stmt = $pdo->prepare("SELECT id, titulo, autor FROM livros WHERE id_usuario = ? AND andamento = 'Lendo' LIMIT 3");
$continue_lendo_stmt->execute([$id_usuario]);
$lista_continue_lendo = $continue_lendo_stmt->fetchAll(PDO::FETCH_ASSOC);

//Lista de Livros Favoritos (limite de 3)
$favoritos_stmt = $pdo->prepare("SELECT id, titulo, autor FROM livros WHERE id_usuario = ? AND favorito = 1 LIMIT 3");
$favoritos_stmt->execute([$id_usuario]);
$lista_favoritos = $favoritos_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container page-container">
    <h1 class="dashboard-greeting">Olá, <?= htmlspecialchars($nome_usuario) ?>!</h1>
    <p class="dashboard-subtitle">Aqui está um resumo da sua estante virtual.</p>

    <div class="dashboard-grid">
        
        <div class="dashboard-widget">
            <h2 class="widget-title">Resumo</h2>
            <div class="stat-boxes">
                <div class="stat-box">
                    <span class="stat-number"><?= $total_livros ?></span>
                    <span class="stat-label">Livros na Estante</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number"><?= $lendo_agora ?></span>
                    <span class="stat-label">Lendo Atualmente</span>
                </div>
            </div>
        </div>

        <div class="dashboard-widget">
            <h2 class="widget-title">Acesso Rápido</h2>
            <div class="action-boxes">
                <a href="adicionar_livro.php" class="action-box">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Adicionar Novo Livro</span>
                </a>
                <a href="meus_livros.php" class="action-box">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Ver Estante Completa</span>
                </a>
            </div>
        </div>

        <div class="dashboard-widget full-width">
            <h2 class="widget-title">Continue Lendo</h2>
            <div class="book-list-widget">
                <?php if (count($lista_continue_lendo) > 0): ?>
                    <ul>
                        <?php foreach ($lista_continue_lendo as $livro): ?>
                            <li>
                                <span class="book-title"><?= htmlspecialchars($livro['titulo']) ?></span>
                                <span class="book-author">por <?= htmlspecialchars($livro['autor']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-list-message">Você não está lendo nenhum livro no momento.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-widget full-width">
            <h2 class="widget-title"><i class="fa-solid fa-star" style="color: var(--cor-favorito);"></i> Seus Favoritos</h2>
             <div class="book-list-widget">
                <?php if (count($lista_favoritos) > 0): ?>
                    <ul>
                        <?php foreach ($lista_favoritos as $livro): ?>
                            <li>
                                <span class="book-title"><?= htmlspecialchars($livro['titulo']) ?></span>
                                <span class="book-author">por <?= htmlspecialchars($livro['autor']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-list-message">Você ainda não marcou nenhum livro como favorito.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include 'templates/footer.php'; ?>