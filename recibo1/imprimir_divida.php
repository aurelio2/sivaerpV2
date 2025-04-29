<?php
// Importar a biblioteca FPDF e a conexão com o banco de dados
require('../fpdf/fpdf.php');
include_once '../dbconnect.php';
include_once '../conexao.php';
include_once'../config_imagem.php';

// Capturar o número de celular da URL ou do formulário
$numero = $_GET['number'];

// Buscar informações da empresa
$sql3 = mysqli_query($mysqli, "SELECT * FROM empresa");
$res3 = mysqli_fetch_array($sql3);
$nome_db = $res3['nome'];
$nuit_db = $res3['nuit'];
$contacto_db = $res3['contacto'];

// Criar o objeto PDF
$pdf = new FPDF('P', 'mm', array(82, 210));
$pdf->AddPage();
$pdf->Image('../' . $nome_imagem_logo, 27, 2, 30);
$pdf->Ln(18);

// Adicionar as informações da empresa
$pdf->SetFont('Courier', 'B', 15);
$pdf->Cell(60, 4, $nome_db, 0, 1, 'C');
$pdf->SetFont('Courier', 'B', 8);
$pdf->Ln(5);
$pdf->Cell(60, 2.5, 'Contacto: ' . $numero, 0, 1, 'C');

// Variável para acumular o total geral de todas as dívidas
$total_geral = 0;

// Buscar todas as faturas (`id_invoice`) da `tbl_devida` com o celular fornecido e estado `0`
$query_devida = "SELECT id_invoice, data FROM tbl_devida WHERE celular = '$numero' AND estado = '0'";
$result_devida = mysqli_query($mysqli, $query_devida);

if (mysqli_num_rows($result_devida) > 0) {
    while ($row_devida = mysqli_fetch_array($result_devida)) {
        $invoice_id = $row_devida['id_invoice'];
        $data_divida = $row_devida['data'];

        // Adicionar informações da fatura ao PDF
        $pdf->Ln(3);
        $pdf->Cell(60, 2.5, 'Recibo N: ' . $invoice_id, 0, 1, 'C');
        $pdf->Cell(60, 2.5, 'Data: ' . $data_divida, 0, 1, 'C');
        $pdf->Cell(0, 2, '---------------------------------------', 0, 1, 'C');

        // Adicionar cabeçalhos da tabela de produtos
        $pdf->SetX(7);
        $pdf->SetFont('Courier', 'B', 8);
        $pdf->Cell(11, 2, 'Qtd', 0, 0, 'L');
        $pdf->Cell(30, 2, 'Artigo', 0, 0, 'L');
        $pdf->Cell(20, 2, 'TOTAL', 0, 1, 'R');
        $pdf->Cell(0, 2, '---------------------------------------', 0, 1, 'C');

        // Inicializar variável para o total da dívida atual
        $total_divida = 0;

        // Buscar os detalhes da fatura na `tbl_invoice_details`
        $query_invoice_details = "SELECT * FROM tbl_invoice_details WHERE invoice_id = '$invoice_id'";
        $result_invoice_details = mysqli_query($mysqli, $query_invoice_details);

        while ($item = mysqli_fetch_array($result_invoice_details)) {
            $valor = $item['price'] * $item['qty'];
            $total_divida += $valor;  // Somar ao total da dívida
            $total_geral += $valor;   // Somar ao total geral

            // Exibir os detalhes dos produtos no PDF
            $pdf->SetX(7);
            $pdf->SetFont('Courier', 'B', 8);
            $pdf->Cell(11, 2.5, $item['qty'], 0, 0, 'L');
            $pdf->Cell(30, 2.5, $item['product_name'], 0, 0, 'L');
            $pdf->Cell(20, 2.5, number_format($valor, 2), 0, 1, 'R');
        }

        // Exibir a linha divisória
        $pdf->Cell(0, 2, '---------------------------------------', 0, 1, 'C');

        // Exibir o total da dívida atual
        $pdf->SetX(7);
        $pdf->SetFont('Courier', 'B', 8);
        $pdf->Cell(30, 2.5, 'Total Divida: ', 0, 0, 'L');
        $pdf->Cell(20, 2.5, number_format($total_divida, 2), 0, 1, 'R');
        $pdf->Cell(0, 2, '---------------------------------------', 0, 1, 'C');
    }

    // Exibir o total geral de todas as dívidas
    $pdf->Ln(5);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(60, 5, 'Total Geral de Todas as Dividas: ', 0, 1, 'C');
    $pdf->Cell(60, 5, number_format($total_geral, 2), 0, 1, 'C');

} else {
    // Caso não haja faturas associadas ao número fornecido
    $pdf->Cell(60, 10, 'Nenhuma divida encontrada.', 0, 1, 'C');
}

// Gerar o PDF
$pdf->Output();
?>
