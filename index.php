<?php 
include 'conexao.php';

// Função para listar alunos
function listar_alunos() {
    global $conn;
    $stmt = $conn->query('SELECT * FROM Alunos');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para listar professores
function listar_professores() {
    global $conn;
    $stmt = $conn->query('SELECT * FROM Professores');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para listar livros
function listar_livros() {
    global $conn;
    $stmt = $conn->query('SELECT * FROM Livros');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemplo de uso
$alunos = listar_alunos();
$livros = listar_livros();
$professores = listar_professores();

?>
<h1>Cadastro de Alunos</h1>
    
    <!-- Formulário de entrada de dados -->
    <form action="./back_end/aluno/salvar_al.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required>
        <br><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <input type="submit" value="Cadastrar">
    </form>
    <form method="POST">
        <button type="submit" name="mostrar_alunos">Mostrar Todos os Alunos</button>
    </form>

    <h1>Cadastro de Professores</h1>
    
    <!-- Formulário de entrada de dados -->
    <form action="./back_end/professor/salvar_professor.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="cpf">cpf:</label>
        <input type="text" id="cpf" name="cpf" required>
        <br><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br><br> 

        <label for="senha">senha:</label>
        <input type="senha" id="senha" name="senha" required>
        <br><br>

        <input type="submit" value="Cadastrar">
    </form>

    <h1>Cadastro de livros</h1>  

    <!-- Formulário de entrada de dados -->
    <form action="../tcc/back_end/livro_cad/cadastrar_livro.php" method="POST">

        <label for="nome_livro">Nome_livro:</label>
        <input type="text" id="nome_livro" name="nome_livro">
        <br><br>

        <label for="nome_autor">nome_autor:</label>
        <input type="text" id="nome_autor" name="nome_autor">
        <br><br>

        <label for="isbn">isbn:</label>
        <input type="text" id="isbn" name="isbn">
        <br><br>

        <input type="submit" value="Cadastrar">
    </form> 


    <h1>Cadastrar Empréstimo</h1>  

<form action="back_end/cadastrar_emp/emp.php" method="POST">
    <label for="aluno_nome">Aluno:</label>
    <input type="text" name="aluno_nome" required>
    <br>

    <label for="livro_titulo">Livro:</label>
    <input type="text" name="livro_titulo" required>
    <br>

    <label for="professor_nome">Professor:</label>
    <input type="text" name="professor_nome" required>
    <br>

    <label for="data_retirada">Data de Retirada:</label>
    <input type="date" name="data_retirada" required>
    <br>

    <label for="data_devolucao">Data de Devolução:</label>
    <input type="date" name="data_devolucao" required>
    <br>

    <button type="submit">Registrar Empréstimo</button>

    <?php if (!empty($mensagem)): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>
</form>
</body>
</html>
 