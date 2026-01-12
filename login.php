<?php
session_start();
include 'conexaousuario.php'; 

// Recebe os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];

// Consulta o banco
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou o usuário
if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Verifica a senha
    if (password_verify($password, $usuario['senha'])) {
        // Salva na sessão
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['tipo'] = $usuario['tipo'];
        $_SESSION['id'] = $usuario['id'];

        // Redireciona baseado no tipo
        if ($usuario['tipo'] === 'admin') {
            header("Location: crudprodutos.php");
        } else {
            header("Location: produtos.php");
        }
        exit();
    }
}

// Se falhar (usuário ou senha incorretos)
header("Location: index.php?erro=1");
exit();
?>