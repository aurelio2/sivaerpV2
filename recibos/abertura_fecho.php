<?php
//call the FPDF library
require('../fpdf/fpdf.php');
include_once'../dbconnect.php';
include_once'../conexao.php';
include_once "../session.php";

//company query
$select4=$pdo->prepare("select * from empresa");
$select4->execute();
$row=$select4->fetch(PDO::FETCH_ASSOC);
$company_name=$row['nome'];
$company_nuit=$row['nuit'];
$company_contacto=$row['contacto'];


 $sql1 = mysqli_query($mysqli, "SELECT * 
          FROM tbl_caixa 
          WHERE id_user = '$idUser' 
            AND estado = '2' 
           AND closed_by = '$idUser'  
            AND id = (SELECT MAX(id) FROM tbl_caixa WHERE id_user = '$idUser')");
        $res1 = mysqli_fetch_array($sql1);
        $data_c = $res1['data'];
        $data_f = $res1['data_final'];
        $caixa_estado = $res1['estado']; // Obtém o estado da caixa
        $aberto_money = $res1['valor_inicial']; // Obtém o estado da caixa
        $fecho_money = $res1['valor_final']; // Obtém o valor final da caixa anterior
        $fecho_hora_inicial = $res1['hora']; // Obtém o valor final da caixa anterior
        $fecho_hora_final = $res1['hora_final']; // Obtém o valor final da caixa anterior



 $sql2 = mysqli_query($mysqli, "SELECT * FROM tbl_invoice where user ='$idUser'");
 $res2 = mysqli_fetch_array($sql2); 
 $user = $res2['customer_name'];        

//$dia = $_GET['dia'];

//Somatorio de todas as dividas
$queryDivi = "SELECT SUM(valor) as totalDivida FROM tbl_devida WHERE data = ? and user='$idUser' and estado=0";
$date = $data_c;

// Prepare the statement
if ($stmt = $mysqli->prepare($queryDivi)) {
    // Bind the parameter
	$stmt->bind_param("s", $date);
  // Execute the query
	$stmt->execute();
  // Bind the result variable
	$stmt->bind_result($totalDivida);
  // Fetch the result
	$stmt->fetch();
  // Close the statement
	$stmt->close();
} else {
	die("Error: " . $mysqli->error);
} 




$query1 = "SELECT SUM(emola) as emolatotal FROM tbl_control_payment WHERE data = ? and user='$idUser'";
$date = $data_c;

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
$query2 = "SELECT SUM(pos) as postotal FROM tbl_control_payment WHERE data = ? and user='$idUser'";
$date = $data_c ;
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


$select21=$pdo->prepare("SELECT sum(cash) as cashtotal from tbl_control_payment where data=:fromdate and user='$idUser'");
$select21->bindParam(':fromdate',$data_c);  
$select21->execute();
$row=$select21->fetch(PDO::FETCH_OBJ);
$cash_total=$row->cashtotal;

//Recebido to be a cash-->
$select2=$pdo->prepare("SELECT sum(valor_recebido-troco) as cash2 from tbl_control_payment where data=:fromdate  and user='$idUser'");
$select2->bindParam(':fromdate',$data_c);  
$select2->execute();
$row=$select2->fetch(PDO::FETCH_OBJ);
$cash2_total=$row->cash2;

//Mpesa sum for day
$select3=$pdo->prepare("SELECT sum(mpesa) as mpesatotal from tbl_control_payment where data=:fromdate and user='$idUser'");
$select3->bindParam(':fromdate',$data_c);  
$select3->execute();
$row=$select3->fetch(PDO::FETCH_OBJ);
$mpesa_total=$row->mpesatotal;        

$totalGeral = $cash_total+$cash2_total+$mpesa_total+$emola_total+$pos_total;


//create pdf obje
$pdf = new FPDF('P','mm',array(82,210));

//add new page
$pdf->AddPage();
//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',16);
//Cell(width , height , text , border , end line , [align] 
//$pdf->Image('../images/laprona.png', 29,2,15);
//$pdf->Cell(60,4,$nome_db,0,1,'C');
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
$pdf->Cell(15,2,'HORA INICIAL:',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,$fecho_hora_inicial,0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'HORA FINAL:',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,$fecho_hora_final,0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'ABERTO:',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($aberto_money,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'FECHO:',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($fecho_money,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

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
$pdf->Cell(15,2,'DIVIDA',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($totalDivida,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(1);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'TOTAL',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($totalGeral,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(4);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('courier','B',9);
$pdf->Cell(15,2,'USUARIO',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(50,2,$user,0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);

$pdf->Ln(2);
$pdf->SetX(7);
$hora=date('H'); $min=date('i'); $seg=date('s');
//$pdf->Cell(17,3,'Data:'.$result['data'].' Hora:'.$hora.':'.$min.':'.$seg.'' ,0,0,'');
$pdf->Cell(30,3,' ',0,2,'R');
$pdf->SetX(7);
$pdf->Cell(0,2,'  ===============================',0,1,'');
$pdf->Cell(17,2,'     Fecho do dia '.$data_c.'         ',0,1, '');
$pdf->Cell(0,2,'===============================',0,1,'');
$pdf->SetFont('Courier','B',8);
$pdf->Cell(0,2,'Processado no computador! SivaERP',0,1,'R');
$pdf->Cell(0,2,'====================================',0,1,'');
$pdf->Cell(0,2,'        Powerd by SIVA       ',0,1,'R');
$pdf->Cell(0,2,'====================================',0,1,'');
$pdf->SetFont('Courier','B',9);
//$pdf->Cell(17,5,'        Obrigado, Volte sempre! ',0,1,'');
$pdf->Ln(3);


$pdf->Output();
?>