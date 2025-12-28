<?php
// Arquivo central para definir o nome da imagem utilizada no sistema
$nome_imagem_logo = 'images/Siva.png'; // Caminho relativo da imagem

// Definições padrão das imagens
//Get default imagem as Logo

// Definir numero diretamente como 1
$numero = 1;

// Condição para definir a imagem correta baseada no numero
if ($numero == 1) {
    $nome_imagem_tipo = '../images/restaurant.png'; // Imagem de restaurant (com caminho relativo para subdiretórios)
} elseif ($numero == 2) {
    $nome_imagem_tipo = '../images/icons8-cash_register_45.png'; // Imagem de ponto de venda (com caminho relativo para subdiretórios)
} else {
    $nome_imagem_tipo = '../images/Siva.png'; // Imagem padrão caso não seja 1 ou 2 (com caminho relativo para subdiretórios)
}
?>
