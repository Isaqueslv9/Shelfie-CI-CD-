<?php
include 'templates/header.php';
require 'conexao.php';
//alterar senha
?>
<div class="container page-container">
    <h1>Meu Perfil</h1>
    <form class="livro-form" method="POST">
        <h2>Alterar Senha</h2>
        <label for="senha_atual">Senha Atual</label>
        <input type="password" name="senha_atual" required>
        <label for="nova_senha">Nova Senha</label>
        <input type="password" name="nova_senha" required>
        <label for="confirma_senha">Confirmar Nova Senha</label>
        <input type="password" name="confirma_senha" required>
        <button type="submit">Salvar Alterações</button>
    </form>
</div>
<?php include 'templates/footer.php'; ?>