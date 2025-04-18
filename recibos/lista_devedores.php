<?php
include_once'../dbconnect.php';
include_once'../conexao.php';
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
error_reporting(0);
$data1 = $_GET['dia'];
$data2 = $_GET['dia2'];
$numero = $_GET['numero'];

// Monta a consulta base
$query = "SELECT * FROM tbl_devida WHERE (data BETWEEN :data1 AND :data2) AND valor != 0 and estado='0'";

// Verifica se o número de celular foi fornecido e adiciona à condição se não estiver vazio
if (!empty($numero)) {
    $query .= " AND (celular = :numero OR celular IS NULL)";
}

$consul_s = $pdo->prepare($query);
$consul_s->bindParam(':data1', $data1);
$consul_s->bindParam(':data2', $data2);

// Só vincula o parâmetro :numero se ele não estiver vazio
if (!empty($numero)) {
    $consul_s->bindParam(':numero', $numero);
}

$consul_s->execute();
	
$html= '<div style="font-family: Courier; font-size: 13px;">
<div style="font-family: Courier; font-size: 13px;">
<br>
<p align="center">MAPA DE CONTROLE DE <b>KM</b> DE <b>DEVEDORES</b>, REFERENTE AO DIA '.$data1.' ATE DIA '.$data2.'</p>
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
$html.='<th width="10" align="center">Ord</th>';
$html.='<th width="200" align="center">Nome</th>';
$html.='<th width="200" align="center">Numero</th>';
$html.='<th width="200" align="left">Divida</th>';
$html.='<th width="100" align="left">Data</th>';
$html.='</tr></tbody>';
$i=1;
while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
	$idorder=$row->id_invoice;
	$cons = mysqli_query($mysqli, "select * from client_order_detalhes where invoice_id = $idorder ");

$res=mysqli_fetch_array($cons);

$html .= '<tr>';
$html .= '<td align="center">'.$i++.'</td>';
$html .= '<td align="center">'.$res['nome'].'</td>';
$html .= '<td align="center">'.$row->celular.'</td>';
$html .= '<td align="center">'.number_format($row->valor,2).'</td>';
$html .= '<td align="center">'.$row->data.'</td>';

}
$query = "SELECT SUM(valor) as soma FROM tbl_devida WHERE data BETWEEN :data1 AND :data2 and estado ='0'";

if (isset($_GET['numero']) && $_GET['numero'] !== '') {
    $query .= " AND (celular = :numero OR celular IS NULL)";
}

$consulta = $pdo->prepare($query);
$consulta->bindParam(':data1', $data1);
$consulta->bindParam(':data2', $data2);

if (isset($_GET['numero']) && $_GET['numero'] !== '') {
    $consulta->bindParam(':numero', $_GET['numero']);
}

		$consulta->execute();
		$resultado=$consulta->fetch(PDO::FETCH_OBJ);
	
$html .= '<tr style="background-Color: #EEEEEE;">
<td colspan=4>Total - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - - - -</td>
<td align="center">&nbsp;&nbsp;&nbsp;'.number_format($resultado->soma,2).' &nbsp;&nbsp;

</td>
</tr>';

$html .= '</table><br><br>';
		

$mes=date('m');
$dia=date('d');
$ano=date('Y');
if($mes==1){$mes='Janeiro';}elseif($mes==2){$mes='Fevereiro';}elseif($mes==3){$mes='Mar&ccedil;o';}elseif($mes==4){$mes='Abril';}
if($mes==5){$mes='Maio';}elseif($mes==6){$mes='Junho';}elseif($mes==7){$mes='Julho';}elseif($mes==8){$mes='Agosto';}
if($mes==9){$mes='Setembro';}elseif($mes==10){$mes='Outubro';}elseif($mes==11){$mes='Novembro';}elseif($mes==12){$mes='Dezembro';}
$html.='<div align=center>&nbsp; KM, aos '.$dia.' de '.$mes.' de '.$ano.'<br>';
$html.='<br><br>O RESPONSAVEL<br>';
$html.='<br>_________________________________</div>

<div id="footer">
SIVA Todos os direitos reservados
	<p class="page"></p>
</div>';	

	
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','landscape');//, 'landscape'
$dompdf->render();
$dompdf->stream(
	"Relatorio periodico.pdf",
		array(
			"Attachment" =>false //para realizar download automatico alterar para true
		)
	);
?>