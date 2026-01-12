<?php
session_start();
include 'conexaoloja.php'; // Garante que a variável $conn_loja esteja disponível

// Função para listar todos os itens da tabela 'novidades'
function listarNovidades($conn) {
    $sql = "SELECT * FROM novidades ORDER BY codigo DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Função para obter um item específico
function obterNovidade($conn, $codigo) {
    $sql = "SELECT * FROM novidades WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Processar ações
$acao = $_REQUEST['acao'] ?? 'listar';
$mensagem = '';

// Adicionar item
if ($acao === 'adicionar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? 0;
    $itens = $_POST['itens'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;
    
    if (!empty($itens) && $codigo > 0) {
        $verificar = obterNovidade($conn_loja, $codigo);
        if ($verificar) {
            $mensagem = "Erro: Código de produto já existe! Use um código diferente.";
        } else {
            $sql = "INSERT INTO novidades (codigo, itens, quantidade) VALUES (?, ?, ?)";
            $stmt = $conn_loja->prepare($sql);
            $stmt->bind_param("isi", $codigo, $itens, $quantidade);
            
            if ($stmt->execute()) {
                $mensagem = "Produto adicionado com sucesso!";
            } else {
                $mensagem = "Erro ao adicionar produto: " . $conn_loja->error;
            }
        }
    } else {
        $mensagem = "Código e nome do produto são obrigatórios!";
    }
}

// Editar item
if ($acao === 'editar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? 0;
    $itens = $_POST['itens'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;
    
    if (!empty($itens) && $codigo > 0) {
        $sql = "UPDATE novidades SET itens = ?, quantidade = ? WHERE codigo = ?";
        $stmt = $conn_loja->prepare($sql);
        $stmt->bind_param("sii", $itens, $quantidade, $codigo);
        
        if ($stmt->execute()) {
            $mensagem = "Produto atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar produto: " . $conn_loja->error;
        }
    } else {
        $mensagem = "Dados inválidos para edição!";
    }
}

// Excluir item
if ($acao === 'excluir' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? 0;
    if ($codigo > 0) {
        $sql = "DELETE FROM novidades WHERE codigo = ?";
        $stmt = $conn_loja->prepare($sql);
        $stmt->bind_param("i", $codigo);
        
        if ($stmt->execute()) {
            $mensagem = "Produto excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir produto: " . $conn_loja->error;
        }
    }
}

// Obter lista de itens para exibição
$produtos = listarNovidades($conn_loja); // Variável renomeada para clareza

// Se a ação for 'editar', busca os dados para preencher o formulário
$produtoEditando = null; // Variável renomeada para clareza
if ($acao === 'editar' && isset($_GET['codigo'])) {
    $produtoEditando = obterNovidade($conn_loja, $_GET['codigo']);
}

// NOVO: Pega o nome da página atual para destacar o menu
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair Care - Gerenciar Produtos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* --- ESTILOS GERAIS --- */
        body { font-family: 'Open Sans', sans-serif; background-color: #fdfaf7; }
        .crud-container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .crud-header { text-align: center; margin-bottom: 30px; }
        .crud-header h1 { font-family: 'Montserrat', sans-serif; font-size: 32px; color: #5a2c1a; margin-bottom: 10px; }
        .mensagem { padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-weight: 600; }
        .mensagem.sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .mensagem.erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-container { background-color: #fff8f2; padding: 30px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-container h2 { font-family: 'Montserrat', sans-serif; color: #5a2c1a; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: 600; color: #5a2c1a; margin-bottom: 5px; }
        .form-group input { padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; font-family: 'Open Sans', sans-serif; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; transition: all 0.3s ease; }
        .btn-primary { background-color: #5a2c1a; color: white; }
        .btn-primary:hover { background-color: #b36c47; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-secondary:hover { background-color: #545b62; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-small { padding: 8px 16px; font-size: 12px; }
        
        /* --- ESTILOS DO MENU (ADICIONADO) --- */
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
            margin-bottom: -2px;
            transition: color 0.3s ease, border-bottom-color 0.3s ease;
        }
        .tab-link.active {
            color: #5a2c1a;
            border-bottom: 2px solid #5a2c1a;
        }

        /* --- ESTILOS DOS CARDS --- */
        .novidades-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .novidade-card { background-color: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 4px solid #5a2c1a; }
        .novidade-card:hover { transform: translateY(-5px); }
        .novidade-codigo { background-color: #5a2c1a; color: white; padding: 8px 12px; border-radius: 50%; font-weight: bold; font-size: 14px; display: inline-block; margin-bottom: 15px; min-width: 40px; text-align: center; }
        .novidade-nome { font-family: 'Montserrat', sans-serif; font-size: 18px; color: #5a2c1a; margin-bottom: 10px; }
        .novidade-info p { margin: 5px 0; font-size: 14px; }
        .novidade-acoes { display: flex; gap: 10px; justify-content: flex-start; margin-top: 15px; }
        .quantidade-badge { background-color: #fbe9d7; color: #5a2c1a; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; }
    </style>
</head>

<body>
    <div class="page-container">
        <main>
            <div class="crud-container">

                <div class="tabs-container">
                    <a href="crudprodutos.php" class="tab-link <?php if ($paginaAtual == 'crudprodutos.php') echo 'active'; ?>">
                        Produto
                    </a>
                    <a href="painel_usuario.php" class="tab-link <?php if ($paginaAtual == 'painel_usuario.php') echo 'active'; ?>">
                        Usuário
                    </a>
                </div>

                <div class="crud-header">
                    <h1>Gerenciar Produtos</h1>
                    <p>Adicione, edite ou remova produtos do catálogo</p>
                </div>

                <?php if (!empty($mensagem)): ?>
                    <div class="mensagem <?= strpos($mensagem, 'Erro') !== false ? 'erro' : 'sucesso' ?>">
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <h2><?= $produtoEditando ? 'Editar Produto' : 'Adicionar Novo Produto' ?></h2>
                    
                    <form method="POST" action="?acao=<?= $produtoEditando ? 'editar' : 'adicionar' ?>">
                        <?php if ($produtoEditando): ?>
                            <input type="hidden" name="codigo" value="<?= $produtoEditando['codigo'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="codigo">Código *</label>
                                <input type="number" id="codigo" name="codigo" required min="1"
                                       value="<?= $produtoEditando['codigo'] ?? '' ?>"
                                       <?= $produtoEditando ? 'readonly' : '' ?>>
                                <?php if (!$produtoEditando): ?>
                                    <small style="color: #666; font-size: 12px;">Deve ser um código novo.</small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="itens">Nome do Produto *</label>
                                <input type="text" id="itens" name="itens" required maxlength="50"
                                       value="<?= htmlspecialchars($produtoEditando['itens'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" id="quantidade" name="quantidade" min="0"
                                       value="<?= $produtoEditando['quantidade'] ?? '' ?>">
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">
                                <?= $produtoEditando ? 'Atualizar Produto' : 'Adicionar Produto' ?>
                            </button>
                            
                            <?php if ($produtoEditando): ?>
                                <a href="crudnovidades.php" class="btn btn-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="novidades-section">
                    <h2>Produtos Cadastrados (<?= count($produtos) ?>)</h2>
                    
                    <?php if (empty($produtos)): ?>
                        <p style="text-align: center; color: #666; font-style: italic;">Nenhum produto cadastrado ainda.</p>
                    <?php else: ?>
                        <div class="novidades-grid">
                            <?php foreach ($produtos as $produto): ?>
                                <div class="novidade-card">
                                    <div class="novidade-codigo"><?= $produto['codigo'] ?></div>
                                    <h3 class="novidade-nome"><?= htmlspecialchars($produto['itens']) ?></h3>
                                    <div class="novidade-info">
                                        <p><strong>Quantidade:</strong> <span class="quantidade-badge"><?= $produto['quantidade'] ?> unidades</span></p>
                                    </div>
                                    <div class="novidade-acoes">
                                        <a href="?acao=editar&codigo=<?= $produto['codigo'] ?>" class="btn btn-secondary btn-small">Editar</a>
                                        
                                        <form method="POST" action="?acao=excluir" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                            <input type="hidden" name="codigo" value="<?= $produto['codigo'] ?>">
                                            <button type="submit" class="btn btn-danger btn-small">Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>