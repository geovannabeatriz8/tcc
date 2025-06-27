<?php
include('../../conexao.php');

if (isset($_POST['nome']) && isset($_POST['serie']) && isset($_POST['email'])) {
    $nome = $_POST['nome'];
    $serie = $_POST['serie'];
    $email = $_POST['email'];
} else {
    // Lidar com o erro ou exibir uma mensagem de alerta
    echo "Erro: Alguns campos obrigatórios não foram preenchidos.";
}
    $sql = "INSERT INTO alunos (nome, serie, email) VALUES (:nome, :serie, :email)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':serie', $serie);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {([$nome, $serie, $email]);
        echo "Aluno cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar aluno!";
    }
?>