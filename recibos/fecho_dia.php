<?php
//call the FPDF library
require('../fpdf/fpdf.php');
include_once'../dbconnect.php';
include_once'../conexao.php';

session_start();
//company query
$select4=$pdo->prepare("select * from empresa");
$select4->execute();
$row=$select4->fetch(PDO::FETCH_ASSOC);
$company_name=$row['nome'];
$company_nuit=$row['nuit'];
$company_contacto=$row['contacto'];

$dia = $_GET['dia'];

$query1 = "SELECT SUM(emola) as emolatotal FROM tbl_control_payment WHERE data = ?";
$date = $dia;

// Prepare the statement
if ($stmt = $mysqli->prepare($query1)) {
                  // Bind the parameter
	$stmt->bind_param("s", $date);

                  // Execute the query
	$stmt->execute();

                  // Bind the result variable
	$stmt->bind_result($emola_total);

                  // Fetch the result
	$stmt->fetch();

                  // Close the statement
	$stmt->close();
} else {
	die("Error: " . $mysqli->error);
} 



//Pos sum for day
$query2 = "SELECT SUM(pos) as postotal FROM tbl_control_payment WHERE data = ?";
$date = $dia;
              // Prepare the statement
if ($stmt = $mysqli->prepare($query2)) {
                  // Bind the parameter
	$stmt->bind_param("s", $date);
                  // Execute the query
	$stmt->execute();
                  // Bind the result variable
	$stmt->bind_result($pos_total);
                  // Fetch the result
	$stmt->fetch(); 
                  // Close the statement
	$stmt->close();
} else {
	die("Error: " . $mysqli->error);
} 



$select21=$pdo->prepare("select sum(cash) as cashtotal from tbl_control_payment where data=:fromdate ");
$select21->bindParam(':fromdate',$dia);  
$select21->execute();
$row=$select21->fetch(PDO::FETCH_OBJ);
$cash_total=$row->cashtotal;



//Recebido to be a cash-->

$select2=$pdo->prepare("select sum(valor_recebido-troco) as cash2 from tbl_control_payment where data=:fromdate ");
$select2->bindParam(':fromdate',$dia);  
$select2->execute();
$row=$select2->fetch(PDO::FETCH_OBJ);
$cash2_total=$row->cash2;



//Mpesa sum for day
$select3=$pdo->prepare("select sum(mpesa) as mpesatotal from tbl_control_payment where data=:fromdate ");
$select3->bindParam(':fromdate',$dia);  
$select3->execute();
$row=$select3->fetch(PDO::FETCH_OBJ);
$mpesa_total=$row->mpesatotal;                    


//create pdf obje
$pdf = new FPDF('P','mm',array(82,210));

//add new page
$pdf->AddPage();
//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',16);
//Cell(width , height , text , border , end line , [align] 
//$pdf->Image('../images/laprona.png', 29,2,15);
//$pdf->Ln(9);
$pdf->SetFont('Courier','B',15);
$pdf->Cell(60,4,''.$company_name.'',0,1,'C');
$pdf->Ln(2);
$pdf->SetFont('Courier','B',9);
$pdf->Cell(60,4,'NUIT: '.$company_nuit.'',0,1,'C');
$pdf->Cell(60,2.5,'Contacto: '.$company_contacto.'',0,1,'C');
$pdf->Ln(1);


$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'CASH:',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($cash_total+$cash2_total,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'MPESA',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($mpesa_total,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);


$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'E-MOLA',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($emola_total,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'POS',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($pos_total,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'TOTAL',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($cash_total+$cash2_total+$mpesa_total+$emola_total+$pos_total,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);


$pdf->Ln(2);
$pdf->SetX(7);
$hora=date('H'); $min=date('i'); $seg=date('s');
//$pdf->Cell(17,3,'Data:'.$result['data'].' Hora:'.$hora.':'.$min.':'.$seg.'' ,0,0,'');
$pdf->Cell(30,3,' ',0,2,'R');
$pdf->SetX(7);
$pdf->Cell(0,2,'  ===============================',0,1,'');
$pdf->Cell(17,2,'     Fecho do dia '.$dia.'         ',0,1, '');
$pdf->Cell(0,2,'===============================',0,1,'');
$pdf->SetFont('Courier','B',8);
$pdf->Cell(0,2,'Processado no computador! SivaERP',0,1,'R');
$pdf->Cell(0,2,'====================================',0,1,'');
$pdf->Cell(0,2,'        Powerd by ProConsult       ',0,1,'R');
$pdf->Cell(0,2,'====================================',0,1,'');
$pdf->SetFont('Courier','B',9);
//$pdf->Cell(17,5,'        Obrigado, Volte sempre! ',0,1,'');
$pdf->Ln(3);


$pdf->Output();
?>