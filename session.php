<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'conexao.php';

// Verifica se a sessão está ativa
if (!isset($_SESSION["txt_email"]) || !isset($_SESSION["txt_senha"])) {
    header("Location: ../index.php");
    exit;
}

// Inicializa variáveis
$usuario_cookie = null;
$Nome = null;
$idUser = null;

// Verifica se o cookie existe
if (isset($_COOKIE['txt_email']) && !empty($_COOKIE['txt_email'])) {
    $usuario_cookie = $_COOKIE['txt_email'];

    // Consulta ao banco de dados
    $sql = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE useremail = '$usuario_cookie'");

    // Verifica se há resultados antes de acessar os dados
    if ($sql && mysqli_num_rows($sql) > 0) {
        $linha = mysqli_fetch_array($sql);
        $Nome = $linha["username"];
        $idUser = $linha["userid"];
    } else {
        $Nome = "Usuário não encontrado";
        $idUser = null;
    }
}
?>
