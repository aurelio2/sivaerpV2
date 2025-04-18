<?php
include_once'../dbconnect.php';
require_once 'autoload.php';
use Dompdf\Dompdf;
$id=$_GET['id'];

$soma=$pdo->prepare("select tbl_invoice_details.product_id, tbl_invoice_details.invoice_id, tbl_invoice_details.qty, SUM(tbl_invoice_details.price*tbl_invoice_details.qty),SUM(tbl_invoice_details.t_sub) as tsubtotal,tbl_invoice_details.t_iva, tbl_product.pname as produto, tbl_product.pcategory, tbl_product.pid, tbl_invoice.invoice_id, tbl_invoice.customer_name,tbl_invoice.order_date FROM tbl_invoice_details INNER JOIN tbl_product ON tbl_product.pid = tbl_invoice_details.product_id INNER JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_details.invoice_id WHERE tbl_invoice_details.invoice_id =$id");
$soma->execute();
$resumo=$soma->fetch(PDO::FETCH_OBJ);


	
$html= '<div style="font-family: Courier; font-size: 13px;">
<center>
<b>INN PARADISE HOTEL</DIV>
<div style="font-family: Courier; font-size: 10px;">
<center>
<b>EMAIL: innparadise2122@gmail.com<br>
CONTACTO 84600600 | NUIT 120111868<br>
Endere&ccedil;o: Estrada Nacional n&#186; 10 - Quelimane
</center>
<br><br>
=========================================<br>
<table border=0 cellspacing="0" cellpadding="0">
<tr><td><b>Data</td><td>&nbsp;<b>'.date('d/m/Y').'</td><td><b>Hora </td><td><b>'.date('H:i:s').'</td></tr>
<tr><td width="10"><b>Cliente</td><td width="70">&nbsp;<b>CLIENTE GERAL</td><td width="23"><b><b>NumRef. </td><td width="10"><b>'.$id.'</td></tr>
</table>
=========================================<br><br>
';

$html.= '<table border=0 cellspacing="0" cellpadding="0" style="font-family: Courier; font-size: 10px;">';	
$html.='<tbody>';
$html.= '
<tr><td colspan="3" align="right"><b>Operador: '.$resumo->customer_name.'</td></tr>
<tr><td colspan=3>-----------------------------------------</td></tr>
<tr>
<td width="10"><b>QTD</td>
<td width="">&nbsp;<b>DESCRICAO</td>
<td align=right width="35"><b>TOTAL</td>
</tr>
<tr><td colspan=3>-----------------------------------------</td></tr>
</tbody>';

$select=$pdo->prepare("select tbl_invoice_details.product_id, tbl_invoice_details.invoice_id, tbl_invoice_details.qty, SUM(tbl_invoice_details.price*tbl_invoice_details.qty),SUM(tbl_invoice_details.t_sub) as tsubtotal,tbl_invoice_details.t_iva, tbl_product.pname as produto, tbl_product.pcategory, tbl_product.pid, tbl_invoice.invoice_id, tbl_invoice.customer_name,tbl_invoice.order_date FROM tbl_invoice_details INNER JOIN tbl_product ON tbl_product.pid = tbl_invoice_details.product_id INNER JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_details.invoice_id WHERE tbl_invoice_details.invoice_id= $id");
$select->execute();

while ($item=$select->fetch(PDO::FETCH_OBJ)) {
$html.= '<tr>';
$html.= '<td align=center><b>'.$item->qty.'</td>';
$html.= '<td><b>'.$item->produto.'</td>';
$html.= '<td align="right"><b>'.number_format($item->preco*$item->qty,2).'</td>';
$html.= '</tr>';
}

$html.='</table>';
$html.='<table border=0 cellspacing="0" cellpadding="0" style="font-family: Courier; font-size: 10px;">';
$html.= '<tr>';
$html.= '<td colspan=3>----------------------------------------</td>';
$html.= '</tr>';
$html.= '<tr>
			<td width="125" align="right"><b>TOTAL</td>
			<td align="center" width="40"><b>'.number_format($resumo->preco,2).'</td>
		</tr>';
$html.= '<tr><td width="122" align="right">------</td><td>-------------</td></tr>';
$html.='</table>
		<table border=0 width="100%" cellspacing="0" cellpadding="0">
			<tr><td align="right" width="110"><b>Dinheiro Entregue</td><td align="right" width="43"><b>'.number_format($resumo->pago,2).'</td></tr>
			<tr><td align="right" width="110"><b>Troco</td><td align="right" width="43"><b>'.number_format($resumo->troco,2).'</td></tr>
			<tr><td colspan=2 align="right">=========================</td></tr>
		</table>
<p align="center"><b>Este talão é apenas comprovativo de <br>pagamento, não substituiu recibo.</p>
';
$html.='<center><b>Obrigado pela prefer&ecirc;ncia<br>Volte Sempre!<br><br><br></center></div>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('B7','portrait');//, 'landscape'
$dompdf->render();
$dompdf->stream(
	"Relatorio.pdf",
		array(
			"Attachment" =>false //para realizar download automatico alterar para true
		)
	);
?>