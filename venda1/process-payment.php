<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $paymentMethod = $_POST['paymentMethod'];
    $additionalInput = isset($_POST['additionalInput']) ? $_POST['additionalInput'] : '';

    echo "Método de Pagamento: $paymentMethod<br>";
    echo "Valor Adicional: $additionalInput";
}
?>