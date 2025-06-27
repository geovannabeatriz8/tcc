<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emprestimo_id'])) {
    $id = $_POST['emprestimo_id'];

    // Atualiza a data de devolução para o dia atual
    $sql = "UPDATE emprestimos SET data_devolucao = NOW() WHERE id = ? AND data_devolucao IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        header("Location: emprestimos_livros.php?msg=Livro devolvido com sucesso!");
        exit();
    } else {
        header("Location: emprestimos_livros.php?msg=Erro ao devolver livro&type=error");
        exit();
    }
} else {
    header("Location: emprestimos_livros.php");
    exit();
}
?>
