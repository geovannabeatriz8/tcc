<?php
include '../../conexao.php'; // Inclui a conexão com o banco de dados

// Verifica se o formulário de pesquisa foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pesquisar'])) {
    // Obtém os dados do formulário
    $nome_livro = isset($_POST['nome_livro']) ? $_POST['nome_livro'] : '';
    $nome_autor = isset($_POST['nome_autor']) ? $_POST['nome_autor'] : '';
    $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : '';
    
    // Monta a URL da API com os parâmetros de pesquisa
    $query = [];
    if ($isbn) {
        $query[] = "isbn:" . urlencode($isbn);
    }
    if ($nome_livro) {
        $query[] = "intitle:" . urlencode($nome_livro);
    }
    if ($nome_autor) {
        $query[] = "inauthor:" . urlencode($nome_autor);
    }
    
    // Se não houver parâmetros de pesquisa, exibe uma mensagem
    if (empty($query)) {
        echo "<p>Por favor, insira pelo menos um critério de pesquisa.</p>";
    } 
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . implode('+', $query);
    
        // Inicializa o cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
        // Executa a requisição
        $response = curl_exec($ch);
    
        // Verifica se ocorreu algum erro
        if (curl_errno($ch)) {
            echo 'Erro: ' . curl_error($ch);
            curl_close($ch);
            exit; // Encerra a execução se houver erro
        }
    
        // Fecha o cURL
        curl_close($ch);
    
        // Converte a resposta JSON para um array PHP
        $data = json_decode($response, true);
    
        // Verifica se há resultados
        if (isset($data['items'])) {
            echo '<div class="livros-container">';
    
            foreach ($data['items'] as $item) {
                $book = $item['volumeInfo'];
    
                // Extrair informações do livro
                $titulo = $book['title'];
                $autores = isset($book['authors']) ? implode(', ', $book['authors']) : 'Autor desconhecido';
                $descricao = isset($book['description']) ? $book['description'] : 'Descrição não disponível';
                $isbn = isset($book['industryIdentifiers'][0]['identifier']) ? $book['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível';
                $imagem = isset($book['imageLinks']['thumbnail']) ? $book['imageLinks']['thumbnail'] : 'imagem_indisponivel.jpg';
    
                // Exibir cada livro com as informações
                echo '<div class="livro-item">';
                echo '<img src="' . htmlspecialchars($imagem) . '" alt="' . htmlspecialchars($titulo) . '" class="imagem-livro">';
                echo '<h3>' . htmlspecialchars($titulo) . '</h3>';
                echo '<p><strong>Autores:</strong> ' . htmlspecialchars($autores) . '</p>';
                echo '<p><strong>Descrição:</strong> ' . htmlspecialchars($descricao) . '</p>';
                echo '<p><strong>ISBN:</strong> ' . htmlspecialchars($isbn) . '</p>';
                
                // Formulário para cadastrar o livro
                echo '<form method="post" action="#">'; // Ação para o mesmo script
                echo '<input type="hidden" name="titulo" value="' . htmlspecialchars($titulo) . '">';
                echo '<input type="hidden" name="autores" value="' . htmlspecialchars($autores) . '">';
                echo '<input type="hidden" name="descricao" value="' . htmlspecialchars($descricao) . '">';
                echo '<input type="hidden" name="isbn" value="' . htmlspecialchars($isbn) . '">';
                echo '<button type="submit" name="cadastrar">Cadastrar Livro</button>';
                echo '</form>';
                echo '</div>'; // Fim do livro-item
            }
    
            echo '</div>'; // Fim do container de livros
        } else {
            echo "<p>Nenhum livro encontrado.</p>";
        }
    }
    
      else {
    // Exibe o formulário de pesquisa
    echo '<form method="post" action="#">';
    echo '<input type="text" name="isbn" placeholder="ISBN">';
    echo '<input type="text" name="nome_livro" placeholder="Nome do Livro">';
    echo '<input type="text" name="nome_autor" placeholder="Nome do Autor">';
    echo '<button type="submit" name="pesquisar">Buscar Livro</button>';
    echo '</form>';
}

// Código para cadastrar o livro no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])) {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autores'];
    $isbn = $_POST['isbn'];

    // Prepara a consulta SQL para inserir o livro
    $stmt = $conn->prepare("INSERT INTO livros (titulo, autor, isbn) VALUES (?, ?, ?)");
    
    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute([$titulo, $autor, $isbn])) {
        echo "<p>Livro cadastrado com sucesso!</p>";
    } else {
        echo "<p>Erro ao cadastrar o livro.</p>";
    }
}

// Fecha a conexão com o banco de dados
$conn = null;
?>