<?php
include_once "../session.php";
include_once '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cliente = $_POST['txtnome'] ?? '';
    $ref = $_POST['id'] ?? '';
    $invoice = '00000';

    // Substitua $mysqli pelo objeto de conexão ao banco de dados
    $stmt = "INSERT INTO client_order_detalhes VALUES (NULL, '$nome_cliente', '$ref', '$invoice')";

    if (mysqli_query($mysqli, $stmt)) {
        header("Location: createorder.php?id=" . $ref);
        exit();
    } else {
        echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
    }
}
?>