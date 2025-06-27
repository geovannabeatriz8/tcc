<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM professores WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "<script>
                alert('Professor excluído com sucesso!');
                window.location.href = 'ver_professores.php';
            </script>";
        } else {
            echo "<script>
                alert('Professor não encontrado ou já excluído.');
                window.location.href = 'ver_professores.php';
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro ao excluir: " . $e->getMessage() . "');
            window.location.href = 'ver_professores.php';
        </script>";
    }
} else {
    echo "<script>
        alert('ID inválido.');
        window.location.href = 'ver_professores.php';
    </script>";
}
?>
