<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nome'])) {
    header("Location: login.php");
    exit();
}

$professor_id = $_SESSION['usuario_id'];
$professor_nome = $_SESSION['nome'];

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_nome = trim($_POST['aluno_nome'] ?? '');
    $livro_titulo = trim($_POST['livro_titulo'] ?? '');
    $data_retirada = $_POST['data_retirada'] ?? '';
    $data_devolucao_prevista = $_POST['data_devolucao_prevista'] ?? '';

    if (!$aluno_nome || !$livro_titulo || !$data_retirada || !$data_devolucao_prevista) {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {
        // Buscar aluno
        $stmt = $conn->prepare("SELECT id FROM alunos WHERE nome = ?");
        $stmt->execute([$aluno_nome]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$aluno) {
            $erro = "Aluno não encontrado. Use a lista de sugestões.";
        }

        // Buscar livro
        $stmt = $conn->prepare("SELECT id FROM livros WHERE titulo = ?");
        $stmt->execute([$livro_titulo]);
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$livro) {
            $erro = "Livro não encontrado. Use a lista de sugestões.";
        }

        // Validar datas: devolução maior ou igual à retirada
        if (!$erro) {
            if ($data_devolucao_prevista < $data_retirada) {
                $erro = "Data de devolução prevista não pode ser anterior à data de retirada.";
            }
        }

        if (!$erro) {
            $stmt = $conn->prepare("INSERT INTO emprestimos (aluno_id, professor_id, livro_id, data_retirada, data_devolucao_prevista) VALUES (?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$aluno['id'], $professor_id, $livro['id'], $data_retirada, $data_devolucao_prevista]);
                $sucesso = "Empréstimo registrado com sucesso! Devolução prevista para $data_devolucao_prevista.";
            } catch (PDOException $e) {
                $erro = "Erro ao registrar empréstimo: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Fazer Empréstimo</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #eef2f3;
    margin: 0; padding: 20px;
    display: flex;
    justify-content: center;
}
.container {
    background: white;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    width: 420px;
}
h2 {
    color: #1d44b8;
    margin-bottom: 25px;
    text-align: center;
}
label {
    font-weight: bold;
    display: block;
    margin-bottom: 8px;
    margin-top: 18px;
}
input[type="text"], input[type="date"] {
    width: 100%;
    padding: 10px 14px;
    border: 1.8px solid #1d44b8;
    border-radius: 8px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
    position: relative;
    z-index: 1;
}
input[type="text"]:focus, input[type="date"]:focus {
    border-color: #163791;
    outline: none;
    box-shadow: 0 0 8px #163791cc;
}
button {
    margin-top: 30px;
    background: #1d44b8;
    color: white;
    border: none;
    padding: 12px 0;
    width: 100%;
    font-size: 18px;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s;
    font-weight: 700;
}
button:hover {
    background: #163791;
}
.voltar-btn {
    margin-top: 15px;
    display: block;
    text-align: center;
    background: #ccc;
    color: #333;
    padding: 10px 0;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s, color 0.3s;
}
.voltar-btn:hover {
    background: #999;
    color: white;
}
.msg {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
}
.sucesso {
    background-color: #d4edda;
    color: #155724;
    border: 1.5px solid #c3e6cb;
}
.erro {
    background-color: #f8d7da;
    color: #721c24;
    border: 1.5px solid #f5c6cb;
}
/* autocomplete */
.autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-top: none;
    z-index: 9999;
    background-color: #fff;
    max-height: 160px;
    overflow-y: auto;
    border-radius: 0 0 8px 8px;
    left: 0;
    right: 0;
}
.autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ddd;
}
.autocomplete-items div:hover {
    background-color: #1d44b8;
    color: white;
}
.autocomplete {
    position: relative;
    display: block;
    width: 100%;
    z-index: 0;
}
</style>
</head>
<body>
<div class="container">
    <h2>Fazer Empréstimo</h2>

    <?php if ($erro): ?>
        <div class="msg erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <div class="msg sucesso"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off" id="emprestimoForm">
        <label for="aluno_nome">Nome do Aluno</label>
        <div class="autocomplete">
            <input type="text" id="aluno_nome" name="aluno_nome" placeholder="Digite o nome do aluno" required />
        </div>

        <label for="livro_titulo">Título do Livro</label>
        <div class="autocomplete">
            <input type="text" id="livro_titulo" name="livro_titulo" placeholder="Digite o título do livro" required />
        </div>

        <label for="data_retirada">Data da Retirada</label>
        <input type="date" id="data_retirada" name="data_retirada" required value="<?= date('Y-m-d') ?>" />

        <label for="data_devolucao_prevista">Data de Devolução Prevista</label>
        <input type="date" id="data_devolucao_prevista" name="data_devolucao_prevista" required value="<?= date('Y-m-d', strtotime('+7 days')) ?>" />

        <button type="submit">Registrar Empréstimo</button>
    </form>
    <a href="painel_livros.php" class="voltar-btn">← Voltar</a>
</div>

<script>
function autocomplete(inp, url) {
    let currentFocus;

    inp.addEventListener("input", function() {
        let val = this.value;
        closeAllLists();
        if (!val) return false;
        currentFocus = -1;

        let listContainer = document.createElement("DIV");
        listContainer.setAttribute("id", this.id + "autocomplete-list");
        listContainer.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(listContainer);

        fetch(url + "?term=" + encodeURIComponent(val))
        .then(res => res.json())
        .then(data => {
            data.forEach(item => {
                let itemText = item.nome || item.titulo || "";
                let itemDiv = document.createElement("DIV");
                itemDiv.innerHTML = "<strong>" + itemText.substr(0, val.length) + "</strong>" + itemText.substr(val.length);
                itemDiv.addEventListener("click", function() {
                    inp.value = itemText;
                    closeAllLists();
                });
                listContainer.appendChild(itemDiv);
            });
        });
    });

    inp.addEventListener("keydown", function(e) {
        let list = document.getElementById(this.id + "autocomplete-list");
        if (list) list = list.getElementsByTagName("div");
        if (e.keyCode == 40) { // down
            currentFocus++;
            addActive(list);
        } else if (e.keyCode == 38) { // up
            currentFocus--;
            addActive(list);
        } else if (e.keyCode == 13) { // enter
            e.preventDefault();
            if (currentFocus > -1) {
                if (list) list[currentFocus].click();
            }
        }
    });

    function addActive(list) {
        if (!list) return false;
        removeActive(list);
        if (currentFocus >= list.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (list.length - 1);
        list[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(list) {
        for (let i = 0; i < list.length; i++) {
            list[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        let items = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < items.length; i++) {
            if (elmnt != items[i] && elmnt != inp) {
                items[i].parentNode.removeChild(items[i]);
            }
        }
    }
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

autocomplete(document.getElementById("aluno_nome"), "autocomplete_alunos.php");
autocomplete(document.getElementById("livro_titulo"), "autocomplete_livros.php");
</script>
</body>
</html>

