<?php
// 1. INICIALIZAÇÃO E CONEXÃO
include 'conexaoloja.php'; // Garante que a variável $conn_loja esteja disponível
include 'session_control.php';
// Pega a mensagem da sessão (após um redirecionamento) e depois a limpa
$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

// 2. FUNÇÕES DO BANCO DE DADOS
function listarProdutos($conn) {
    $sql = "SELECT * FROM produtos ORDER BY id DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function obterProduto($conn, $id) {
    global $conn_loja; // Garante que a conexão está no escopo da função
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn_loja->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// 3. PROCESSAMENTO DAS AÇÕES
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'adicionar' || $acao === 'editar') {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = (float)str_replace(',', '.', $_POST['preco'] ?? 0);
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        $imagem = $_POST['imagem'] ?? 'produto_default.png';

        if ($acao === 'adicionar' && !empty($nome)) {
            $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade, imagem) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn_loja->prepare($sql);
            $stmt->bind_param("ssdis", $nome, $descricao, $preco, $quantidade, $imagem);
            $_SESSION['mensagem'] = $stmt->execute() ? "Produto adicionado com sucesso!" : "Erro: " . $stmt->error;
        } elseif ($acao === 'editar' && !empty($nome) && ($id = (int)$_POST['id'] ?? 0) > 0) {
            $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, quantidade = ?, imagem = ? WHERE id = ?";
            $stmt = $conn_loja->prepare($sql);
            $stmt->bind_param("ssdisi", $nome, $descricao, $preco, $quantidade, $imagem, $id);
            $_SESSION['mensagem'] = $stmt->execute() ? "Produto atualizado com sucesso!" : "Erro: " . $stmt->error;
        } else {
            $_SESSION['mensagem'] = "Erro: Dados inválidos.";
        }
    }
    header("Location: crudprodutos.php");
    exit();
}

if (($_GET['acao'] ?? '') === 'excluir' && ($id = (int)($_GET['id'] ?? 0)) > 0) {
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn_loja->prepare($sql);
    $stmt->bind_param("i", $id);
    $_SESSION['mensagem'] = $stmt->execute() ? "Produto excluído com sucesso!" : "Erro: " . $stmt->error;
    header("Location: crudprodutos.php");
    exit();
}

// 4. PREPARAÇÃO DOS DADOS PARA EXIBIÇÃO
$modoEdicao = ($_GET['acao'] ?? '') === 'editar' && isset($_GET['id']);
$produtoEditando = $modoEdicao ? obterProduto($conn_loja, (int)$_GET['id']) : null;
$produtos = listarProdutos($conn_loja);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair Care - Gerenciar Conteúdo</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* --- ESTILOS GERAIS --- */
        body { font-family: 'Open Sans', sans-serif; background-color: #fdfaf7; }
        .crud-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .crud-header { text-align: center; margin-bottom: 30px; }
        .crud-header h1 { font-family: 'Montserrat', sans-serif; font-size: 32px; color: #5a2c1a; margin-bottom: 10px; }
        .mensagem { padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-weight: 600; }
        .mensagem.sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .mensagem.erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; transition: all 0.3s ease; }
        .btn-primary { background-color: #5a2c1a; color: white; }
        .btn-primary:hover { background-color: #b36c47; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-secondary:hover { background-color: #545b62; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-small { padding: 8px 16px; font-size: 12px; }

        /* --- ESTILOS DO MENU DE ABAS (NOVO) --- */
        .tabs-container {
            display: flex;
            border-bottom: 2px solid #e0d9d4;
            margin-bottom: 30px;
        }
        .tab-link {
            padding: 12px 25px;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 18px;
            font-weight: 600;
            color: #6c757d;
            text-decoration: none;
            margin-bottom: -2px; /* Alinha com a borda inferior */
            transition: color 0.3s ease, border-bottom-color 0.3s ease;
        }
        .tab-link.active {
            color: #5a2c1a;
            border-bottom: 2px solid #5a2c1a;
        }
        .tab-content {
            display: none; /* Esconde todas as abas por padrão */
        }
        .tab-content.active {
            display: block; /* Mostra apenas a aba ativa */
        }

        /* --- ESTILOS DO FORMULÁRIO --- */
        .form-container { background-color: #fff8f2; padding: 30px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .form-container h2 { font-family: 'Montserrat', sans-serif; color: #5a2c1a; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: 600; color: #5a2c1a; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; font-family: 'Open Sans', sans-serif; }
        .form-group textarea { min-height: 80px; resize: vertical; }

        /* --- ESTILOS DOS CARDS DE PRODUTOS --- */
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .produto-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Garante que a imagem não ultrapasse as bordas arredondadas */
        }
        .produto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        /* --- CSS DA IMAGEM (MODIFICADO) --- */
        .produto-card-imagem {
            width: 100%;
            height: 220px; /* Altura fixa para todas as imagens */
            object-fit: cover; /* Garante que a imagem cubra a área sem distorcer */
            display: block;
        }

        .produto-card-conteudo { padding: 20px; flex-grow: 1; }
        .produto-card-conteudo h3 { font-family: 'Montserrat', sans-serif; color: #5a2c1a; margin: 0 0 10px 0; }
        .produto-card-conteudo p { font-size: 14px; color: #666; margin: 0 0 15px 0; }
        .produto-info { display: flex; justify-content: space-between; align-items: center; font-weight: 600; }
        .produto-preco { color: #5a2c1a; font-size: 18px; }
        .produto-estoque { background-color: #fbe9d7; color: #5a2c1a; padding: 4px 10px; border-radius: 12px; font-size: 12px; }
        .produto-card-acoes {
            padding: 0 20px 20px 20px;
            display: flex;
            gap: 10px;
        }

    </style>
</head>
<body>

    <div class="crud-container">
        
        <div class="crud-header">
            <h1>Painel de Gerenciamento</h1>
        </div>
        
        <div class="tabs-container">
            <a href="crudnovidades.php" class="tab-link active" data-tab="novidades">Novidades</a>
            <a href="#" class="tab-link" data-tab="usuario">Usuário</a>
        </div>

        <div id="novidades" class="tab-content active">
            <main>
                <?php if (!empty($mensagem)): ?>
                    <div class="mensagem <?= strpos(strtolower($mensagem), 'erro') !== false ? 'erro' : 'sucesso' ?>">
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php endif; ?>

                <div class="form-container" id="formulario">
                    <h2><?= $modoEdicao ? 'Editar Produto' : 'Adicionar Novo Produto' ?></h2>
                    
                    <form method="POST" action="crudprodutos.php">
                        <input type="hidden" name="acao" value="<?= $modoEdicao ? 'editar' : 'adicionar' ?>">
                        <?php if ($modoEdicao): ?>
                            <input type="hidden" name="id" value="<?= $produtoEditando['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nome">Nome do Produto *</label>
                                <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($produtoEditando['nome'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="preco">Preço (R$)</label>
                                <input type="text" id="preco" name="preco" placeholder="Ex: 129,90" value="<?= number_format($produtoEditando['preco'] ?? 0, 2, ',', '.') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="quantidade">Quantidade em Estoque</label>
                                <input type="number" id="quantidade" name="quantidade" min="0" value="<?= $produtoEditando['quantidade'] ?? '0' ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="imagem">Imagem (nome do arquivo)</label>
                                <select id="imagem" name="imagem" class="form-control">
                                    <option value="produto_default.png" <?= (($produtoEditando['imagem'] ?? '') === 'produto_default.png') ? 'selected' : '' ?>>Padrão</option>
                                    <option value="produtoamarelo_redondo.png" <?= (($produtoEditando['imagem'] ?? '') === 'produtoamarelo_redondo.png') ? 'selected' : '' ?>>Produto Amarelo</option>
                                    <option value="produtoinfantil_redondo.png" <?= (($produtoEditando['imagem'] ?? '') === 'produtoinfantil_redondo.png') ? 'selected' : '' ?>>Produto Infantil</option>
                                    <option value="produtoverde_redondo.png" <?= (($produtoEditando['imagem'] ?? '') === 'produtoverde_redondo.png') ? 'selected' : '' ?>>Produto Verde</option>
                                    <option value="belezahair_round.png" <?= (($produtoEditando['imagem'] ?? '') === 'belezahair_round.png') ? 'selected' : '' ?>>Beleza Hair</option>
                                </select>
                            </div>

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="descricao">Descrição</label>
                                <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva os benefícios e características do produto..."><?= htmlspecialchars($produtoEditando['descricao'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px; justify-content: flex-start;">
                            <button type="submit" class="btn btn-primary">
                                <?= $modoEdicao ? 'Salvar Alterações' : 'Adicionar Produto' ?>
                            </button>
                            
                            <?php if ($modoEdicao): ?>
                                <a href="crudprodutos.php" class="btn btn-secondary">Cancelar Edição</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="produtos-section">
                    <h2>Catálogo Atual (<?= count($produtos) ?>)</h2>
                    
                    <?php if (empty($produtos)): ?>
                        <p style="text-align: center; padding: 40px; background-color: #fff; border-radius: 8px;">Nenhum produto cadastrado ainda. Use o formulário acima para começar!</p>
                    <?php else: ?>
                        <div class="produtos-grid">
                            <?php foreach ($produtos as $produto): ?>
                                <div class="produto-card">
                                    <img src="imgs/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="produto-card-imagem" onerror="this.src='imgs/produto_default.png';">
                                    
                                    <div class="produto-card-conteudo">
                                        <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                                        <p><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
                                        
                                        <div class="produto-info">
                                            <span class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                                            <span class="produto-estoque"><?= $produto['quantidade'] ?> em estoque</span>
                                        </div>
                                    </div>
                                    
                                    <div class="produto-card-acoes">
                                        <a href="crudprodutos.php?acao=editar&id=<?= $produto['id'] ?>#formulario" class="btn btn-secondary btn-small">Editar</a>
                                        <a href="crudprodutos.php?acao=excluir&id=<?= $produto['id'] ?>" class="btn btn-danger btn-small" onclick="return confirm('Tem certeza que deseja excluir o produto \'<?= htmlspecialchars(addslashes($produto['nome'])) ?>\'?')">Excluir</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>

        <div id="usuario" class="tab-content">
             <div class="form-container">
                <h2>Painel do Usuário</h2>
                <p>Esta área pode ser usada para exibir informações do usuário logado, configurações de conta, histórico de pedidos, etc.</p>
                </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const tabId = this.getAttribute('data-tab');

                    // 2. ALTERAÇÃO NO JAVASCRIPT: a lógica agora só roda se a aba NÃO for 'novidades'
                    if (tabId !== 'novidades') {
                        e.preventDefault(); // Impede a navegação SÓ para as outras abas

                        // Desativa todas as abas e conteúdos
                        tabLinks.forEach(item => item.classList.remove('active'));
                        tabContents.forEach(item => item.classList.remove('active'));

                        // Ativa a aba e o conteúdo clicado
                        this.classList.add('active');
                        document.getElementById(tabId).classList.add('active');
                    }
                    // Se a aba for 'novidades', o script não faz nada e o link funciona normalmente.
                });
            });
        });
    </script>

</body>
</html>