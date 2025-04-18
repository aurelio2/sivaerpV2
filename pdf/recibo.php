<?php
//call the FPDF library
require('../fpdf/fpdf.php');
include_once'../dbconnect.php';

session_start();
$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_saidas where id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);



//create pdf object
$pdf = new FPDF('P','mm',array(80,155));

//add new page
$pdf->AddPage();

//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',14);
//Cell(width , height , text , border , end line , [align] )
$pdf->Image('../images/logo_barb.png', 27,2,25);
$pdf->Ln(18);
//$pdf->Cell(60,8,'Acliven service',1,1,'C');

$pdf->SetFont('Courier','B',8);

$pdf->Cell(60,2.5,'Address: Maputo cidade',0,1,'C');
$pdf->Cell(60,2.5,'NUIT: 120262971',0,1,'C');
$pdf->Cell(60,2.5,'Phone Number: 841101674',0,1,'C');
$pdf->Cell(60,2.5,'E-mail Address: qani.cortecabelereiro',0,1,'C');

$pdf->Ln(3);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(11,2,'Qtd',0,0,'L');
$pdf->Cell(0,2,'Corte/Pentiando',0,0,'L');   //70
$pdf->Cell(4,2,'TOTAL',0,1,'R');
$select=$pdo->prepare("select * from tbl_itens_saidos where id_saida=$id");
$select->execute();
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');


while($item=$select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(7);
    $valor=$item->valor*$item->quant;
$pdf->SetFont('Courier','B',8);
$pdf->Cell(11,2.5,$item->quant,0,0,'L');
$pdf->Cell(10,2.5,$item->produto,0,0,'L');   
$pdf->Cell(46,2.5,number_format($item->valor*$item->quant,2),0,1,'R');  
    
}
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(17,2,'TOTAL',0,0,'');   
$pdf->Cell(10,2,'',0,0,'');
$pdf->Cell(68,2,number_format($row->total,2 ),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');


$pdf->Cell(20,2,'',0,1,'');   

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(17,3,'Operador:'.$row->user.'',0,1,'');
$pdf->SetX(7);
$pdf->Cell(17,3,'Data: '.$row->data.'',0,0,'L');
$pdf->Cell(50,3,'Hora: '.$row->hora.'',0,2,'R');


$pdf->SetX(7);
$pdf->Cell(0,2,'======================================',0,1,'');
$pdf->Cell(17,5,'        Obrigado, Volte sempre! ',0,1,'');


$pdf->Output();
?>