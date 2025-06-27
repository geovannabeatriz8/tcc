<?php
// Defina suas variáveis corretamente
$host = 'localhost';   // Host do banco de dados, geralmente localhost
$db   = 'tcc'; // Nome do banco de dados
$user = 'root'; // Usuário do banco de dados
$pass = ''; // Senha do banco de dados (geralmente em XAMPP é vazio)

// Tente a conexão com o banco de dados usando PDO
try {
    // Estabelece a conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Define o modo de erro do PDO para exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Caso haja erro na conexão, ele será tratado aqui
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}




