<?php
// Arquivo para visualizar informações do PHP
// Só deve ser acessível por desenvolvedores

session_start();
include_once 'session.php';
include_once 'conexao.php';

// Verificar se o usuário tem permissões de desenvolvedor
$sql_user = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE id = '".$_SESSION['id']."'");
$user_data = mysqli_fetch_array($sql_user);

// Só permite acesso se for admin ou desenvolvedor
if($user_data['nivel'] != 'admin' && $user_data['nivel'] != 'dev') {
    header('Location: index.php');
    exit();
}

phpinfo();
?>
