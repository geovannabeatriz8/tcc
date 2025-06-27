<?php
include('../../conexao.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$email = $_POST['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO professores (nome, cpf, senha, email) VALUES (:nome, :cpf, :senha, :email)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "Professor cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar professor!";
    }
}
?>