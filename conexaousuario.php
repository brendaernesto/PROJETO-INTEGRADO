<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "admin";

$conn = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
    die("Erro na conexão com o banco de dados ADMIN: " . mysqli_connect_error());
}
?>
