<?php
//call the FPDF library
require('../fpdf/fpdf.php');
include_once'../dbconnect.php';
include_once'../conexao.php';
include_once'../session.php';
include_once'../config_imagem.php';
include_once __DIR__ . '/config_pdf.php';

$data1 = $_GET['dia'];
$data2 =$_GET['dia2'];
$user = $_GET['user'];

$sql = mysqli_query($mysqli, "SELECT * FROM tbl_caixa WHERE estado = '2' and data='$data1' and data_final='$data2' and id_user='$user' and closed_by='$user'");
$res = mysqli_fetch_array($sql);
$data_c = $res['data'];
$data_final = $res['data_final'];
$aberto_money = $res['valor_inicial'];
$fecho_money = $res['valor_final'];
$user_id1 = $res['id_user'];
$user_id2 = $res['closed_by'];

$sql1 = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE userid ='$user'");
$res1 = mysqli_fetch_array($sql1);
$usuario = $res1['username'];

$query = "SELECT i.customer_name, d.product_name, d.qty, d.price, d.total
FROM tbl_invoice AS i
JOIN tbl_invoice_details AS d
ON i.invoice_id = d.invoice_id
WHERE d.order_date BETWEEN '$data1' AND '$data2' AND i.user = $user";

$result = $mysqli->query($query);



//empresa
$sql3 = mysqli_query($mysqli,"SELECT * FROM empresa");
$res3 = mysqli_fetch_array($sql3);
$nome_db = $res3['nome'];
$nuit_db = $res3['nuit'];
$contacto_db = $res3['contacto'];

//create pdf obje
$pdf = new FPDF('P','mm',array(82,210));

//add new page
$pdf->AddPage();
$logoPath = __DIR__ . '/../' . $nome_imagem_logo;
if ($mostrar_logo == 1 && !empty($nome_imagem_logo) && file_exists($logoPath)) {
    $pdf->Image($logoPath, 27, 2, 25);
    $pdf->Ln(18);
}
//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',16);
//Cell(width , height , text , border , end line , [align] 
$pdf->Cell(60,4,$nome_db,0,1,'C');
$pdf->Ln(2);
$pdf->SetFont('Courier','B',20);
$pdf->Ln(2);
$pdf->Cell(60,4,'FECHO',0,1,'C');
$pdf->Ln(2);
$pdf->SetFont('Courier','B',9);
$pdf->Ln(1);
$pdf->Cell(60,2.5,'USUARIO:'.$usuario.'',0,1,'C');
$pdf->Cell(60,2.5,'Data I:'.$data1.'',0,1,'C');
//$pdf->Cell(60,2.5,'Valor I:'.number_format($fecho_money,2).'',0,1,'C');
$pdf->Cell(60,2.5,'Data F:'.$data2.'',0,1,'C');
//$pdf->Cell(60,2.5,'Valor F:'.number_format($aberto_money,2).'',0,1,'C');

$pdf->Ln(3);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('Courier','B',9);
$pdf->Cell(9,2,'Qtd',0,0,'L');
$pdf->Cell(0,2,'Preco',0,0,'L');  
$pdf->Cell(0,2,'TOTAL',0,1,'R');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');

while($row = $result->fetch_assoc()){
    $pdf->SetX(7);
    $pdf->SetFont('Courier','B',9);
    $pdf->Cell(8,2.5,$row['qty'],0,0,'L');
    $pdf->Cell(11,2.5,$row['price'],0,0,'L');
    $pdf->Cell(10,2.5,$row['product_name'],0,0,'L');
    $pdf->Cell(39,2.5,number_format($row['price'] * $row['qty'], 2),0,1,'R');
    
}
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');

$select = $pdo->prepare("SELECT SUM(subtotal) as subtotal FROM tbl_invoice WHERE order_date BETWEEN :data1 AND :data2 AND user = :user");
$select->bindParam(':data1', $data1);
$select->bindParam(':data2', $data2);
$select->bindParam(':user', $user);
$select->execute();
while ($item = $select->fetch(PDO::FETCH_OBJ)) {
    $subtotal = $item->subtotal; // Atribuir o valor à variável $subtotal
    $pdf->SetX(7);
    $pdf->SetFont('courier', 'B', 9);
    $pdf->Cell(15, 2, 'TOTAL', 0, 0, '');
    $pdf->Cell(10, 4, ' ', 0, 0, '');
    $pdf->Cell(68, 2, number_format($subtotal, 2), 0, 1, 'C');
    $pdf->Cell(0, 2, '---------------------------------------', 0, 1, 'C');
    $pdf->SetX(7);
}

$pdf->SetX(7);
$hora=date('H'); $min=date('i'); $seg=date('s');
//$pdf->Cell(17,3,'Data:'' Hora:'.$hora.':'.$min.':'.$seg.'' ,0,0,'');
$pdf->Cell(30,3,' ',0,2,'R');
$pdf->SetX(7);
$pdf->Cell(0,2,'  ===============================',0,1,'');
$pdf->Cell(17,2,'        Recibo de Pagamento         ',0,1, '');
$pdf->Cell(0,2,'===============================',0,1,'');
$pdf->SetFont('Courier','B',8);
$pdf->Cell(0,2,'Processado no computador! ProConsult',0,1,'R');
$pdf->Cell(0,2,'====================================',0,1,'');
$pdf->SetFont('Courier','B',9);
$pdf->Cell(17,5,'        Obrigado, Volte sempre! ',0,1,'');
$pdf->Ln(3);


$pdf->Output();
?>