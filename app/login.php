<?php
session_start();
require 'conexao.php';
$errors = [];

//CADASTRO
if (isset($_POST['signup'])) {
    $nome_usuario = trim($_POST['nome_usuario']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome_usuario) || empty($email) || empty($senha)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? OR nome_usuario = ?');
        $stmt->execute([$email, $nome_usuario]);
        if ($stmt->fetch()) {
            $errors[] = 'E-mail ou nome de usuário já cadastrado.';
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)');
            if (!$stmt->execute([$nome_usuario, $email, $senha_hash])) {
                $errors[] = 'Erro ao criar conta. Tente novamente.';
            }
            // Ação de sucesso (redirecionar ou mostrar mensagem)
        }
    }
}

//LOGIN 
if (isset($_POST['signin'])) {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $errors[] = 'E-mail e senha são obrigatórios.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'E-mail ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao Shelfie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/login_style.css">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="login.php" method="POST">
                <h1>Criar Conta</h1>
                <?php if (!empty($errors) && isset($_POST['signup'])): ?>
                    <div class="error-box"><?php foreach($errors as $error) { echo "<p>$error</p>"; } ?></div>
                <?php endif; ?>
                <span>Use seu e-mail para cadastrar</span>
                <input type="text" name="nome_usuario" placeholder="Nome" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <div class="password-container">
                    <input type="password" name="senha" id="signup-password" placeholder="Senha" required>
                    <i class="fa-solid fa-eye-slash toggle-password" data-target="signup-password"></i>
                </div>
                <button type="submit" name="signup">Cadastrar</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form action="login.php" method="POST">
                <h1>Entrar</h1>
                 <?php if (!empty($errors) && isset($_POST['signin'])): ?>
                    <div class="error-box"><?php foreach($errors as $error) { echo "<p>$error</p>"; } ?></div>
                <?php endif; ?>
                <span>Use seu e-mail e senha</span>
                <input type="email" name="email" placeholder="E-mail" required>
                 <div class="password-container">
                    <input type="password" name="senha" id="signin-password" placeholder="Senha" required>
                    <i class="fa-solid fa-eye-slash toggle-password" data-target="signin-password"></i>
                </div>
                <a href="#">Esqueceu sua senha?</a>
                <button type="submit" name="signin">Entrar</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Bem-vindo de Volta!</h1>
                    <p>Já tem uma conta? Faça login para ver sua estante!</p>
                    <button class="hidden" id="login">Entrar</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <img src="img/logo.png" alt="Shelfie Logo" style="width:150px; margin-bottom: 1rem;">
                    <h1>Olá, Leitor!</h1>
                    <p>Cadastre-se e comece a organizar seus livros!</p>
                    <button class="hidden" id="register">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/login_script.js"></script>
</body>
</html>