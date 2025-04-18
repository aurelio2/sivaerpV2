    <?php
//call the FPDF library
require('../fpdf/fpdf.php');
include_once'../dbconnect.php';
include_once'../conexao.php';

session_start();
$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_invoice where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$sql1 = mysqli_query($mysqli,"SELECT * FROM tbl_mesa where cod_mesa = $row->mesa");
$res1 = mysqli_fetch_array($sql1);
$id_mesa_db = $res1['id'];
$id_m_db = $res1['cod_mesa'];


$sql = mysqli_query($mysqli, "SELECT * FROM client_order_detalhes WHERE id =
          (select MAX(id) from client_order_detalhes where invoice_id=$id )");

$res = mysqli_fetch_array($sql);
$nome_cliente_db = $res['nome'];

//obter informacoes de devidas
$sql4 = mysqli_query($mysqli, "SELECT * FROM tbl_devida where id_invoice=$id");
$res4 = mysqli_fetch_array($sql4);
$celular_cliente_db = $res4['celular'];


//controller  e pagamento
$sql2 = mysqli_query($mysqli,"SELECT * FROM tbl_control_payment where id_invoice = $id");
$res2 = mysqli_fetch_array($sql2);
$cash_db = $res2['cash'];
$mpesa_db = $res2['mpesa'];
$emola_db = $res2['emola'];
$pos_db = $res2['pos'];

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
$pdf->Image('../images/casaCego.jpeg', 27,2,25);
$pdf->Ln(18);
$pdf->SetFont('Courier','B',15);
$pdf->Cell(60,4,$nome_db,0,1,'C');
$pdf->SetFont('Courier','B',8);
$pdf->Cell(60,4,'NUIT: '.$nuit_db.'',0,1,'C');
$pdf->Cell(60,2.5,'Contacto:'.$contacto_db.'',0,1,'C');
$pdf->Cell(60,2.5,'Recibo N:'.$row->invoice_id.'',0,1,'C');
$pdf->Cell(60,4,'Mesa: '.$row->mesa.'',0,1,'C');
$pdf->Cell(60,4,'Nome: '.$nome_cliente_db.'',0,1,'C');

$pdf->Ln(3);
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(11,2,'Qtd',0,0,'L');
//$pdf->Cell(11,2,'Preco',0,0,'L');
$pdf->Cell(0,2,'Artigo',0,0,'L');   //70
$pdf->Cell(1,2,'TOTAL',0,1,'R');
$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select->execute();
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');

while($item=$select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(7);
    $valor=$item->price*$item->qty;
$pdf->SetFont('Courier','B',8);
$pdf->Cell(11,2.5,$item->qty,0,0,'L');
//$pdf->Cell(11,2.5,number_format($item->price,2),0,0,'L');
$pdf->Cell(10,2.5,$item->product_name,0,0,'L');   
$pdf->Cell(45,2.5,number_format($item->price*$item->qty,2),0,1,'R');  
    
}
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');


if($res4['valor']==0){
$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(15,2,'DIVIDA',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,'PAGA',0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
}else{
$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(15,2,'DIVIDA',0,0,'');   
$pdf->Cell(10,4,' ',0,0,'');
$pdf->Cell(68,2,number_format($row->total,2),0,1,'C');
$pdf->Cell(0,2,'---------------------------------------',0,1,'C');
$pdf->SetX(7);
}

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(17,3,'Operador:'.$row->customer_name.'',0,1,'');
$pdf->SetX(7);
$hora=date('H'); $min=date('i'); $seg=date('s');
$pdf->Cell(17,3,'Data:'.$row->order_date.' Hora:'.$hora.':'.$min.':'.$seg.'' ,0,0,'');
$pdf->Cell(30,3,' ',0,2,'R');
$pdf->SetX(7);
$pdf->Cell(0,2,'  ====================================',0,1,'');
$pdf->Cell(17,2,'        Recibo de Pagamento         ',0,1, '');
$pdf->Cell(0,2,'====================================',0,1,'');
$pdf->Cell(17,2,'Processado no computador! SIVAERP',0,1,'');
$pdf->Cell(0,2,'====================================',0,1,'');

$pdf->Cell(17,5,'        Obrigado, Volte sempre! ',0,1,'');
$pdf->Ln(3);


$pdf->Output();
?>