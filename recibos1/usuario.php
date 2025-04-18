<?php
include_once'../dbconnect.php';
include_once'../conexao.php';
require_once 'autoload.php';
use Dompdf\Dompdf;
error_reporting(0);

$consul_s = $pdo->prepare("select * from tbl_user");
$consul_s->execute();
	
$html= '<div style="font-family: Courier; font-size: 13px;">
<div style="font-family: Courier; font-size: 13px;">
<br>
<p align="center"><b>Lista de usaurios de Sistema</b></p>
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
$html.='<th width="150" align="center">Nome completo</th>';
$html.='<th width="150" align="left">Usuario</th>';
$html.='<th width="150">&nbsp;Senha</th>';
$html.='<th width="90" >Nivel de Acesso</th>';
$html.='</tr></tbody>';

while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {

$html .= '<tr>';
$html .= '<td align="left">&nbsp;'.$row->username.'</td>';
$html .= '<td align="center">'.$row->useremail.'</td>';
$html .= '<td align="center">********************</td>';
$html .= '<td align="center">'.$row->role.'</td>';	
$html .= '</tr>';
}
$html .= '</table><br><br>';
	
$mes=date('m');
$dia=date('d');
$ano=date('Y');
if($mes==1){$mes='Janeiro';}elseif($mes==2){$mes='Fevereiro';}elseif($mes==3){$mes='Mar&ccedil;o';}elseif($mes==4){$mes='Abril';}
if($mes==5){$mes='Maio';}elseif($mes==6){$mes='Junho';}elseif($mes==7){$mes='Julho';}elseif($mes==8){$mes='Agosto';}
if($mes==9){$mes='Setembro';}elseif($mes==10){$mes='Outubro';}elseif($mes==11){$mes='Novembro';}elseif($mes==12){$mes='Dezembro';}
$html.='<div align=center>&nbsp; Farmacia, aos '.$dia.' de '.$mes.' de '.$ano.'<br>';
$html.='
<div id="footer">
Maxwell Software | Email: amax.a.roupa@gmail.com | Website: www.amax.it
            <p class="page"></p>
        </div>
';	

	
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');//, 'landscape'
$dompdf->render();
$dompdf->stream(
	"Relatorio.pdf",
		array(
			"Attachment" =>false //para realizar download automatico alterar para true
		)
	);
?>