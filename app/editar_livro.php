<?php
include 'templates/header.php';
require 'conexao.php';

$mensagem = '';
$livro = null;
$id_livro = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_livro_post = filter_input(INPUT_POST, 'id_livro', FILTER_VALIDATE_INT);
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $editora = trim($_POST['editora']); // NOVO
    $categoria = trim($_POST['categoria']);
    $andamento = $_POST['andamento'];
    $resenha = trim($_POST['resenha']);
    $nota = !empty($_POST['nota']) ? (int)$_POST['nota'] : null;

    if (!empty($titulo) && !empty($autor) && $id_livro_post) {
        $sql = "UPDATE livros SET titulo = ?, autor = ?, editora = ?, categoria = ?, andamento = ?, nota = ?, resenha = ? WHERE id = ? AND id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$titulo, $autor, $editora, $categoria, $andamento, $nota, $resenha, $id_livro_post, $id_usuario]);
            header('Location: meus_livros.php?status=edit_success');
            exit();
        } catch (PDOException $e) {
            $mensagem = '<div class="mensagem-erro">Ocorreu um erro ao atualizar o livro.</div>';
        }
    }
}

if ($id_livro) {
    $stmt = $pdo->prepare("SELECT * FROM livros WHERE id = ? AND id_usuario = ?");
    $stmt->execute([$id_livro, $id_usuario]);
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container page-container">
    <?php if ($livro): ?>
        <h1>Editar Livro: "<?= htmlspecialchars($livro['titulo']) ?>"</h1>
        <?php echo $mensagem; ?>

        <form class="livro-form" action="editar_livro.php?id=<?= $livro['id'] ?>" method="POST">
            <input type="hidden" name="id_livro" value="<?= $livro['id'] ?>">

            <label for="titulo">Título do Livro:</label>
            <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($livro['titulo']) ?>" required>
            
            <label for="autor">Autor:</label>
            <input type="text" name="autor" id="autor" value="<?= htmlspecialchars($livro['autor']) ?>" required>
            
            <label for="editora">Editora:</label>
            <input type="text" name="editora" id="editora" value="<?= htmlspecialchars($livro['editora']) ?>" placeholder="Ex: Companhia das Letras">

            <label for="categoria">Categoria:</label>
            <input type="text" name="categoria" id="categoria" value="<?= htmlspecialchars($livro['categoria']) ?>" placeholder="Ex: Ficção Científica">
            
            <label for="andamento">Andamento da Leitura:</label>
            <select name="andamento" id="andamento" required>
                <option value="Quero Ler" <?= ($livro['andamento'] == 'Quero Ler') ? 'selected' : '' ?>>Quero Ler</option>
                <option value="Lendo" <?= ($livro['andamento'] == 'Lendo') ? 'selected' : '' ?>>Lendo</option>
                <option value="Lido" <?= ($livro['andamento'] == 'Lido') ? 'selected' : '' ?>>Lido</option>
                <option value="Abandonei" <?= ($livro['andamento'] == 'Abandonei') ? 'selected' : '' ?>>Abandonei</option>
            </select>
            
            <label for="nota">Nota (de 1 a 5):</label>
            <input type="number" name="nota" id="nota" min="1" max="5" value="<?= htmlspecialchars($livro['nota']) ?>">
            
            <label for="resenha">Resenha:</label>
            <textarea name="resenha" id="resenha" rows="5"><?= htmlspecialchars($livro['resenha']) ?></textarea>
            
            <button type="submit">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <h1>Erro</h1>
        <p class="mensagem-erro">Livro não encontrado ou você não tem permissão para editá-lo.</p>
        <a href="meus_livros.php">Voltar para a lista de livros</a>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>