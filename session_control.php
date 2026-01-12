<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Expira sessão após 2 minutos de inatividade
if (isset($_SESSION['ultimo_ativo']) && (time() - $_SESSION['ultimo_ativo'] > 120)) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=1");
    exit();
}

// Atualiza o tempo da última atividade
$_SESSION['ultimo_ativo'] = time();
?>
