<?php
// Conectar ao banco de dados
include '../../conexao.php';

try {
    $dsn = "mysql:host=localhost;dbname={$dbname};charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexão falhou: " . $e->getMessage());
}

// Função para adicionar empréstimo
function adicionar_emprestimo($aluno_id, $professor_id, $livro_id, $data_retirada, $data_devolucao = null) {
    global $pdo;  // Agora o global está dentro da função

    // Verifica se a data de devolução foi informada
    if (!empty($data_devolucao)) {
        // Obtém o mês e ano atual e da data de devolução
        $mes_atual = date('m');
        $ano_atual = date('Y');
        $mes_devolucao = date('m', strtotime($data_devolucao));
        $ano_devolucao = date('Y', strtotime($data_devolucao));

        // Verifica se a data de devolução é válida
        if ($ano_devolucao < $ano_atual || ($ano_devolucao == $ano_atual && $mes_devolucao < $mes_atual)) {
            echo "Erro: A data de devolução não pode ser no mês ou ano anterior ao atual.";
            return;
        } else {
            echo "Data de devolução válida.";
        }
    } else {
        $data_devolucao = null;  // Se não foi informada, define como null
    }

    // Definir data de retirada, se não for informada
    if (empty($data_retirada)) {
        $data_retirada = date('Y-m-d H:i:s');  // Define a data atual
    }

    // Preparar a query para inserir o empréstimo
    $stmt = $pdo->prepare('
        INSERT INTO emprestimos (aluno_id, professor_id, livro_id, data_retirada, data_devolucao)
        VALUES (?, ?, ?, ?, ?)
    ');

    $stmt->execute([$aluno_id, $professor_id, $livro_id, $data_retirada, $data_devolucao]);
    echo "Empréstimo cadastrado com sucesso!";
}

// Processando o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber os dados do formulário
    $aluno_nome = $_POST['aluno_nome'] ?? null;
    $livro_titulo = $_POST['livro_titulo'] ?? null;
    $professor_nome = $_POST['professor_nome'] ?? null;
    $data_retirada = $_POST['data_retirada'] ?? null;
    $data_devolucao = $_POST['data_devolucao'] ?? null;

    // Buscar IDs no banco antes de inserir
    function buscarId($tabela, $campo, $valor) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id FROM $tabela WHERE $campo = ?");
        $stmt->execute([$valor]);
        return $stmt->fetchColumn();  // Retorna o ID ou FALSE se não encontrar
    }

    $aluno_id = buscarId('alunos', 'nome', $aluno_nome);
    $livro_id = buscarId('livros', 'titulo', $livro_titulo);
    $professor_id = buscarId('professores', 'nome', $professor_nome);

    // Verificar se encontrou os IDs
    if (!$aluno_id || !$livro_id || !$professor_id) {
        die("Erro: Aluno, livro ou professor não encontrados no banco de dados.");
    }

    // Chama a função para adicionar o empréstimo
    adicionar_emprestimo($aluno_id, $professor_id, $livro_id, $data_retirada, $data_devolucao);
}

