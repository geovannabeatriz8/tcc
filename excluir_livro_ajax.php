<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM livros WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Livro não encontrado ou já excluído.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID do livro não fornecido']);
}
?>
