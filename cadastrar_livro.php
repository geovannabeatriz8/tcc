<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');

    if ($titulo !== '' || $autor !== '' || $isbn !== '') {
        try {
            $sql = "INSERT INTO livros (titulo, autor, isbn) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$titulo, $autor, $isbn]);
            $mensagem = "Livro cadastrado com sucesso!";
        } catch (PDOException $e) {
            $mensagem = "Erro ao cadastrar livro: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha pelo menos um campo: título, autor ou ISBN.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Cadastrar Livro</title>
<style>
    body { font-family: Arial, sans-serif; background: #eef2f3; padding: 20px; }
    .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #aaa; }
    label { display: block; margin-top: 15px; font-weight: bold; }
    input[type="text"] { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
    button { margin-top: 20px; padding: 10px; width: 100%; background: #1d44b8; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #163791; }
    .btn-voltar {
        margin-bottom: 20px;
        background: #777;
        width: auto;
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        color: white;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-voltar:hover {
        background: #555;
    }
    .mensagem { margin-top: 20px; font-weight: bold; color: green; text-align: center; }
    .erro { color: red; }
    .preview-list {
        margin-top: 20px;
        max-height: 320px;
        overflow-y: auto;
        background: #fafafa;
        padding: 10px;
        border-radius: 8px;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }
    .preview-item {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding: 8px 5px;
        transition: background-color 0.2s;
    }
    .preview-item:hover {
        background-color: #e0e7ff;
    }
    .preview-item img {
        width: 50px;
        height: 75px;
        object-fit: cover;
        border-radius: 4px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        margin-right: 15px;
    }
    .preview-info {
        flex: 1;
    }
    .preview-info strong {
        display: block;
        color: #1d44b8;
        font-size: 16px;
        margin-bottom: 4px;
    }
    .preview-info small {
        color: #555;
    }
    .btn-select {
        background-color: #1d44b8;
        border: none;
        color: white;
        padding: 6px 14px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    .btn-select:hover {
        background-color: #163791;
    }
</style>
</head>
<body>

<div class="container">
 

    <h2>Cadastrar Livro</h2>

    <form method="POST" id="formLivro">
        <label for="titulo">Título</label>
        <input type="text" name="titulo" id="titulo" placeholder="Digite o título do livro">

        <label for="autor">Autor</label>
        <input type="text" name="autor" id="autor" placeholder="Digite o autor do livro">

        <label for="isbn">ISBN</label>
        <input type="text" name="isbn" id="isbn" placeholder="Digite o ISBN">

        <button type="submit">Cadastrar</button>
    </form>

    <div class="mensagem <?= strpos($mensagem, 'Erro') !== false ? 'erro' : '' ?>">
        <?= htmlspecialchars($mensagem) ?>
    </div>

    <div class="preview-list" id="previewList"></div>
</div>

<script>
const tituloInput = document.getElementById('titulo');
const autorInput = document.getElementById('autor');
const isbnInput = document.getElementById('isbn');
const previewList = document.getElementById('previewList');

async function buscarLivros() {
    const termos = [];
    if (isbnInput.value.trim() !== '') termos.push(isbnInput.value.trim());
    if (tituloInput.value.trim() !== '') termos.push(tituloInput.value.trim());
    if (autorInput.value.trim() !== '') termos.push(autorInput.value.trim());

    if (termos.length === 0) {
        previewList.innerHTML = '';
        return;
    }

    const query = termos.join(' ');

    if (query.length < 3) {
        previewList.innerHTML = '';
        return;
    }

    try {
        const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=5`);
        const data = await response.json();

        if (data.totalItems > 0) {
            previewList.innerHTML = '';
            data.items.forEach(item => {
                const info = item.volumeInfo;
                const thumbnail = (info.imageLinks && (info.imageLinks.thumbnail || info.imageLinks.smallThumbnail)) || '';
                const title = info.title || 'Título não disponível';
                const authors = info.authors ? info.authors.join(', ') : 'Autor não disponível';
                const industryIdentifiers = info.industryIdentifiers || [];
                let isbn = '';
                for (const iden of industryIdentifiers) {
                    if (iden.type === "ISBN_13" || iden.type === "ISBN_10") {
                        isbn = iden.identifier;
                        break;
                    }
                }

                const div = document.createElement('div');
                div.classList.add('preview-item');

                div.innerHTML = `
                    <img src="${thumbnail}" alt="Capa do livro">
                    <div class="preview-info">
                        <strong>${title}</strong>
                        <small>${authors}</small>
                        <br>
                        <small>ISBN: ${isbn || 'N/A'}</small>
                    </div>
                    <button class="btn-select">Selecionar</button>
                `;

                const btn = div.querySelector('.btn-select');
                btn.addEventListener('click', () => {
                    document.getElementById('titulo').value = title;
                    document.getElementById('autor').value = authors;
                    document.getElementById('isbn').value = isbn;
                    previewList.innerHTML = '';
                });

                previewList.appendChild(div);
            });
        } else {
            previewList.innerHTML = `<p>Nenhum livro encontrado</p>`;
        }
    } catch (error) {
        previewList.innerHTML = `<p>Erro ao buscar livros</p>`;
        console.error('Erro na API Google Books:', error);
    }
}

tituloInput.addEventListener('input', buscarLivros);
autorInput.addEventListener('input', buscarLivros);
isbnInput.addEventListener('input', buscarLivros);
</script>

</body>
<a href="painel_livros.php" class="btn-voltar">← Voltar ao Painel de Livros</a>


</html>
