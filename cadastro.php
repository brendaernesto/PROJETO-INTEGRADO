<?php
// Configurações do banco
$host = "localhost";        // geralmente localhost
$dbname = "admin";
$username = "root";  // seu usuário do banco
$password = "";    // sua senha do banco

// Conecta ao banco
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com banco de dados: " . $e->getMessage());
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    // Validação simples
    if (!$nome || !$email || !$senha) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Criptografa a senha para segurança
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash,
        ]);
        header ("Location: index.php");
        echo "Cadastro realizado com sucesso!";
        exit ();

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // código de violação de chave única (email duplicado)
            echo "Este e-mail já está cadastrado.";
        } else {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
