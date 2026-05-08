<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario']) || empty($_GET['id'])) {
    header('Location: login.php');
    exit();
}

$id_livro = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];


$stmt = $pdo->prepare("UPDATE livros SET favorito = NOT favorito WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_livro, $id_usuario]);

header('Location: meus_livros.php');
exit();
?>