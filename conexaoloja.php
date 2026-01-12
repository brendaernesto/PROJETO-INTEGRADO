<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco_loja = "loja";

$conn_loja = mysqli_connect($host, $usuario, $senha, $banco_loja);

if (!$conn_loja) {
    die("Erro na conexão com o banco de dados LOJA: " . mysqli_connect_error());
}
?>
