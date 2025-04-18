<?php
include_once '../dbconnect.php';
include_once '../conexao.php';
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Defina as datas e o usuário que deseja usar no SELECT
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

$query = "SELECT i.customer_name, d.product_name, d.qty, d.price, d.total
FROM tbl_invoice AS i
JOIN tbl_invoice_details AS d
ON i.invoice_id = d.invoice_id
WHERE d.order_date BETWEEN '$data1' AND '$data2' AND i.user = $user";

$result = $mysqli->query($query);

$html = '<html><body>';
$html .= '<h2>MAPA DE CONTROLE DE SEU NOME, REFERENTE AO DIA ' . $data1 . ' ate DIA ' . $data2 . '</h2>';
$html .= '<style>';
$html .= 'table {border-top:#FFFFFF 1px solid; border-collapse: collapse; border:#FFF8DC 1px double; border-spacing:0px;}';
$html .= 'table, th {border-top:#FFFFFF 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; text-align: center;}';
$html .= 'table, td {border-bottom: 1px solid #ddd; border-top:#FFFFFF 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid;}';
$html .= '#footer {position: fixed; bottom: 0; width: 100%; text-align: right; border-top: 1px solid gray;}';
$html .= '#footer.page:after{content: counter(page);}';
$html .= '</style>';
$html .= '<table style="font-family: Courier; font-size: 13px;">';
$html .= '<tbody>';
$html .= '<tr style="background-Color: #EEEEEE;">';
$html .= '<th width="200" align="center">Produto</th>';
$html .= '<th width="100" align="center">Preco</th>';
$html .= '<th width="100" align="center">Quantidade</th>';
$html .= '<th width="100" align="center">TOTAL</th>';
$html .= '</tr>';

while ($row = $result->fetch_assoc()) {
	$html .= '<tr>';
	$html .= '<td>'.$row['product_name'].'</td>';
	$html .= '<td>'.$row['price'].'</td>'; 
    $html .= '<td>' . $row['qty'] . '</td>';
    $html .= '<td>' . number_format($row['total'], 2) . '</td>';
    $html .= '</tr>';
}

$dia = date('d');
$mes = date('m');
$ano = date('Y');



$html .= '</tbody></table>';
$html .= '<br><br>';
$html .= '<div align=center>&nbsp; KM, aos ' . $dia . ' de ' . $mes . ' de ' . $ano . '<br>';
$html .= '<br><br>O RESPONSAVEL<br>';
$html .= '<br>_________________________________</div>';
$html .= '<div id="footer">SIVA SOFTWARE Todos os direitos reservados<p class="page"></p></div>';
$html .= '</body></html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream(
	"Relatorio periodico de lucros do dia " . $data1 . " ate " . $data2 . ".pdf",
	array(
		"Attachment" => false
	)
);
?>
