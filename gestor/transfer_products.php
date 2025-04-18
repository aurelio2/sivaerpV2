<?php
// Configuração da conexão com o banco de dados
include_once '../conexao.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $mysqli->real_escape_string($_POST['product_id']);
    $quantityToTransfer = intval($_POST['quantity']);
    
    if ($quantityToTransfer <= 0) {
        echo '<script>
                alert("Quantidade inválida!");
                window.location.href = "list_armazem.php";
              </script>';
        exit();
    }

    // Verificar o produto no estoque atual
    $queryStock = "SELECT pstock FROM tbl_armazem WHERE pid = ?";
    $stmtStock = $mysqli->prepare($queryStock);

    if (!$stmtStock) {
        die("Erro ao preparar a consulta: " . $mysqli->error);
    }

    $stmtStock->bind_param("s", $productId);
    $stmtStock->execute();
    $resultStock = $stmtStock->get_result();
    $rowStock = $resultStock->fetch_assoc();

    if (!$rowStock || $rowStock['pstock'] < $quantityToTransfer) {
        echo '<script>
                alert("Estoque insuficiente para transferir!");
                window.location.href = "list_armazem.php";
              </script>';
        exit();
    }

    // Reduzir o estoque na tabela `tbl_armazem`
    $newStock = $rowStock['pstock'] - $quantityToTransfer;
    $updateStock = "UPDATE tbl_armazem SET pstock = ? WHERE pid = ?";
    $stmtUpdateStock = $mysqli->prepare($updateStock);

    if (!$stmtUpdateStock) {
        die("Erro ao preparar a consulta de atualização: " . $mysqli->error);
    }

    $stmtUpdateStock->bind_param("is", $newStock, $productId);
    $stmtUpdateStock->execute();

    // Verificar se o produto já existe na tabela de destino (tbl_product)
    $queryDest = "SELECT pstock FROM tbl_product WHERE pid = ?";
    $stmtDest = $mysqli->prepare($queryDest);

    if (!$stmtDest) {
        die("Erro ao preparar a consulta para a tabela de destino: " . $mysqli->error);
    }

    $stmtDest->bind_param("s", $productId);
    $stmtDest->execute();
    $resultDest = $stmtDest->get_result();
    $rowDest = $resultDest->fetch_assoc();

    if ($rowDest) {
        // Produto já existe: atualizar a quantidade
        $newDestStock = $rowDest['pstock'] + $quantityToTransfer;
        $updateDest = "UPDATE tbl_product SET pstock = ? WHERE pid = ?";
        $stmtUpdateDest = $mysqli->prepare($updateDest);

        if (!$stmtUpdateDest) {
            die("Erro ao preparar a consulta de atualização no destino: " . $mysqli->error);
        }

        $stmtUpdateDest->bind_param("is", $newDestStock, $productId);
        $stmtUpdateDest->execute();
    } else {
        // Produto não existe: inserir um novo registro
        $insertDest = "INSERT INTO tbl_product (pid, pname, pcategory, purchaseprice, saleprice, pstock, pdescription, fornecedor, iva, codebar) 
                       SELECT pid, pname, pcategory, purchaseprice, saleprice, ?, '', '', '', '' 
                       FROM tbl_armazem WHERE pid = ?";
        $stmtInsertDest = $mysqli->prepare($insertDest);

        if (!$stmtInsertDest) {
            die("Erro ao preparar a consulta de inserção: " . $mysqli->error);
        }

        $stmtInsertDest->bind_param("is", $quantityToTransfer, $productId);
        $stmtInsertDest->execute();
    }

    // Registrar a transferência na tabela `tbl_transfer_history`
    $insertHistory = "INSERT INTO tbl_transfer_history (product_id, quantity, date_transfer) VALUES (?, ?, NOW())";
    $stmtHistory = $mysqli->prepare($insertHistory);

    if (!$stmtHistory) {
        die("Erro ao preparar a consulta para o histórico: " . $mysqli->error);
    }

    $stmtHistory->bind_param("si", $productId, $quantityToTransfer);
    $stmtHistory->execute();

    echo '<script>
            alert("Produto transferido com sucesso!");
            window.location.href = "list_armazem.php";
          </script>';
}
?>
