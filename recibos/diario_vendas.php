<?php
include_once'../dbconnect.php';
include_once'../conexao.php';
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$data = $_GET['dia'];
$consul_s = $pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, (p.saleprice * t.qty) as venda, (p.purchaseprice * t.qty) as compra, (p.saleprice * t.qty) - (p.purchaseprice * t.qty) as lucrototal
	FROM tbl_invoice_details t
	INNER JOIN tbl_product p ON p.pid = t.product_id
	WHERE order_date='$data'
	");
$consul_s->execute();



$select1=$pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, (p.saleprice * t.qty) as venda, (p.purchaseprice * t.qty) as compra, (p.saleprice * t.qty) - (p.purchaseprice * t.qty) as lucrototal, SUM((p.saleprice * t.qty) - (p.purchaseprice * t.qty)) as soma
	FROM tbl_invoice_details t
	INNER JOIN tbl_product p ON p.pid = t.product_id
	WHERE order_date ='$data'");

$select1->execute();

$row=$select1->fetch(PDO::FETCH_OBJ);

$net_total_lucros=$row->soma;
$net_compra =$row->compra;
$net_venda =$row->venda;


$select=$pdo->prepare("SELECT sum(subtotal) as total,count(invoice_id) as invoice from tbl_invoice  where order_date='$data' ");
$select->execute();

$row=$select->fetch(PDO::FETCH_OBJ);

$net_total=$row->total;

/*---------------------------------------------------------*/
//mostrando total de vendas e compra e Quantidades vendidos
$select2 = $pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, sum(p.saleprice) as venda1, sum(p.purchaseprice) as compra1, SUM(t.qty) as quantidadesV
	FROM tbl_invoice_details t
	INNER JOIN tbl_product p ON p.pid = t.product_id
	WHERE order_date ='$data'");

$select2->execute();
$row=$select2->fetch(PDO::FETCH_OBJ);
$net_total_vendas=$row->venda1;
$net_total_compras =$row->compra1;
$net_qtd_vendidas =$row->quantidadesV;

$sql3 = mysqli_query($mysqli,"SELECT * FROM empresa");
$res3 = mysqli_fetch_array($sql3);
$nome_db = $res3['nome'];
$nuit_db = $res3['nuit'];
$contacto_db = $res3['contacto'];

$html= '<div style="font-family: Courier; font-size: 13px;">
<div style="font-family: Courier; font-size: 13px;">
<br>
<p align="center">MAPA DE CONTROLE DE '.$nome_db.' de MARGENS, REFERENTE AO DIA '.$data.'</p>
<style>
table {
	border-top:#FFFFFF 1px solid;
	border-collapse: collapse;
	border:#FFF8DC 1px double;
	border-spacing:0px;
}

table, th {
	border-top:#FFFFFF 1px solid;
	border-left:#CCCCCC 1px solid;
	border-right:#CCCCCC 1px solid;
	border-bottom:#CCCCCC 1px solid;
	text-align: center;
	
}
table, td {
	border-bottom: 1px solid #ddd;
	border-top:#FFFFFF 1px solid;
	border-left:#CCCCCC 1px solid;
	border-right:#CCCCCC 1px solid;
	border-bottom:#CCCCCC 1px solid;
}
#footer {
position: fixed;
bottom: 0;
width: 100%;
text-align: right;
border-top: 1px solid gray;
}
#footer.page:after{ 
content: counter(page); 
}
</style>
';
$html.= '<table style="font-family: Courier; font-size: 16px; ">';	
$html.='<tbody>';
$html.='<tr style="background-Color: rgb(203, 209, 252);">';
$html.='<th width="200" align="center">Produto</th>';
$html.='<th width="50" align="cenetr">Preco de Compra</th>';
$html.='<th width="50" align="cenetr">Preco de Venda</th>';
$html.='<th width="50" align="center">Quantidade</th>';
$html.='<th width="80" align="center">Total</th>';
$html.='<th width="100" align="center">Margem</th>';
//$html.='<th width="40" align="center">Imp</th>';
$html.='</tr></tbody>';

while($row=$consul_s->fetch(PDO::FETCH_OBJ)  ){


	$html .= '<tr>';
	$html .= '<td align="left">'.strtoupper($row->pname).'</td>';
	$html .= '<td align="center">'.number_format($row->purchaseprice,2).'</td>';
	$html .= '<td align="center">'.number_format($row->saleprice,2).'</td>';
	$html .= '<td align="center">'.$row->qty.'</td>';

	$html .= '<td align="center">'.number_format($row->venda,2).'</td>';				
	$html .= '<td align="center">'.number_format($row->lucrototal,2).'</td>';						
	$html .= '</tr>';
}


$html .= '<tr style="background-Color: white;">
<td colspan=1>TOTAL - - - - - - - - - - - - - - - - -</td>
<td align="center">'.number_format($net_total_compras,2).'</td>

<td align="center">'.number_format($net_total_vendas,2).'</td>
<td align="center">'.$net_qtd_vendidas.'</td>


<td align="center">'.number_format($net_total,2).'</td>


<td align="center">'.number_format($net_total_lucros,2).'</td>

</tr>';

$html .= '</table><br><br>';



$mes=date('m');
$dia=date('d');
$ano=date('Y');
if($mes==1){$mes='Janeiro';}elseif($mes==2){$mes='Fevereiro';}elseif($mes==3){$mes='Mar&ccedil;o';}elseif($mes==4){$mes='Abril';}
if($mes==5){$mes='Maio';}elseif($mes==6){$mes='Junho';}elseif($mes==7){$mes='Julho';}elseif($mes==8){$mes='Agosto';}
if($mes==9){$mes='Setembro';}elseif($mes==10){$mes='Outubro';}elseif($mes==11){$mes='Novembro';}elseif($mes==12){$mes='Dezembro';}
$html.='<div align=center>&nbsp; '.$nome_db.', aos '.$dia.' de '.$mes.' de '.$ano.'<br>';
$html.='<br><br>O RESPONSAVEL<br>';
$html.='<br>_________________________________</div>

<div id="footer">
SIVA SOFTWARE Todos os direitos reservados
<p class="page"></p>
</div>';	

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','landscape');//, 'landscape'
$dompdf->render();
$dompdf->stream(
	"Relatorio diario do ".$dia.".pdf",
	array(
			"Attachment" =>false //para realizar download automatico alterar para true
		)
);
?>