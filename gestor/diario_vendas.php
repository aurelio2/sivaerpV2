<?php
include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
require_once 'autoload.php';
use Dompdf\Dompdf;

$data = $_GET['dia'];
$consul_s = $pdo->prepare("select * from tbl_product");
$consul_s->execute();
	
$html= '<div style="font-family: Courier; font-size: 13px;">
<div style="font-family: Courier; font-size: 13px;">
<br>
<p align="center">MAPA DE CONTROLE DE PA Yembe Bottle Store, REFERENTE AO DIA '.$data.'</p>
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
$html.= '<table style="font-family: Courier; font-size: 13px; ">';	
$html.='<tbody>';
$html.='<tr style="background-Color: #EEEEEE;">';
$html.='<th width="200" align="center">Produto</th>';
$html.='<th width="50" align="cenetr">Stock Inicial</th>';
$html.='<th width="50" align="cenetr">Entrada</th>';
$html.='<th width="50" align="center">Saida</th>';
$html.='<th width="50" align="center">Ajuste</th>';
$html.='<th width="50" align="center">Stock final</th>';
$html.='<th width="50" align="center">Pre&ccedil;o Unit.</th>';
$html.='<th width="70" align="center">Valor Total</th>';
$html.='<th width="50" align="center" hidden>Imposto/Iva (17%)</th>';
//$html.='<th width="40" align="center">Imp</th>';
$html.='</tr></tbody>';

while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
$idprod=$row->pid;
$consul = mysqli_query($mysqli,"select tbl_invoice_details.product_id as id, SUM(tbl_invoice_details.qty) as saida, tbl_invoice_details.qty, tbl_invoice_details.order_date,tbl_invoice_details.t_iva
						from tbl_invoice_details 
						where order_date = '$data' and product_id = $idprod");
$sai = mysqli_fetch_array($consul);

$cons = mysqli_query($mysqli, "select SUM(quantidade) as quantidade, data,ajust, idproduto from tbl_entradas where idproduto = $idprod and data = '$data'");
$res=mysqli_fetch_array($cons);
$stock=$row->pstock;
$qtd=$res['quantidade'];
$saida=$sai['saida'];
$stock_final = $sai['saida'];
$qtds=$res['quantidade'];
$stock = $stock+$saida-$qtd;
$ajuste=$res['ajust'];

$stock_final2 = $stock+$qtd-$saida;

$stock_inicial = $row->pstock;;


if($saida<1){
	$total = '-';
}else{
	$total=number_format($saida* $row->saleprice,2);
	$saida = $saida;
}

$html .= '<tr>';
$html .= '<td align="left">'.$row->pname.'</td>';
$html .= '<td align="center">'.$stock_inicial.'</td>';
$html .= '<td align="center">'.$qtds.'</td>';
$html .= '<td align="center">'.$saida.'</td>';	
if($ajuste==0){
	$html .= '<td align="center">-</td>';		
}else{
	$html .= '<td align="center">'.$ajuste.'</td>';	
}

$html .= '<td align="center">'.$stock_final2.'</td>';				
$html .= '<td align="right">'.number_format($row->saleprice,2).'&nbsp;</td>';				
$html .= '<td align="right">'.$total.'&nbsp;</td>';
$html .= '<td align="center">'.$sai['t_iva'].'</td>';
//$html .= '<td align="center">'.$row->iva.'</td>';						
$html .= '</tr>';
}
$consulta = $pdo->prepare("select tbl_invoice_details.product_id, SUM(tbl_invoice_details.total) as soma,SUM(t_iva) as soma_iva, tbl_product.pid from tbl_invoice_details 
							INNER JOIN tbl_product ON tbl_product.pid = tbl_invoice_details.product_id
							where order_date = '$data'");
		$consulta->execute();
		$resultado=$consulta->fetch(PDO::FETCH_OBJ);
	
$html .= '<tr style="background-Color: #EEEEEE;">
<td colspan=7>Total - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td>

<td align="right"> '.number_format($resultado->soma,2).' MT&nbsp;</td>
<td align="center">  '.number_format($resultado->soma_iva,2).' MT&nbsp;&nbsp;
</tr>';

$html .= '</table><br><br>';

		

$mes=date('m');
$dia=date('d');
$ano=date('Y');
if($mes==1){$mes='Janeiro';}elseif($mes==2){$mes='Fevereiro';}elseif($mes==3){$mes='Mar&ccedil;o';}elseif($mes==4){$mes='Abril';}
if($mes==5){$mes='Maio';}elseif($mes==6){$mes='Junho';}elseif($mes==7){$mes='Julho';}elseif($mes==8){$mes='Agosto';}
if($mes==9){$mes='Setembro';}elseif($mes==10){$mes='Outubro';}elseif($mes==11){$mes='Novembro';}elseif($mes==12){$mes='Dezembro';}
$html.='<div align=center>&nbsp; PA Yembe Bottle Store, aos '.$dia.' de '.$mes.' de '.$ano.'<br>';
$html.='<br><br>O RESPONSAVEL<br>';
$html.='<br>_________________________________</div>

<div id="footer">
Maxwell System Todos os direitos reservados
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