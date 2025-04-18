<?php
include_once "../dbconnect.php"; //conexao
require_once 'autoload.php';
use Dompdf\Dompdf;

$id=$_GET['id']; 
$select=$pdo->prepare("select * from tbl_detalhes_requisicao where requisicao_id=$id");
$select->execute();     //where invoice_no=$id                
//$row = $select->fetch(PDO::FETCH_ASSOC) ;
$row=$select->fetch(PDO::FETCH_OBJ);

$html= '<div style="text-align: center;">
<img src="../images/acliven01.pn" align="center"><br>
<br><br>
REQUISIÇÃO EXTERNA Nº 0
<br><br><br>
</div>

Requisita-se a <b></b><br>

Para <b><i></i></b><br><br>
Os artigos abaixo indicados:<br>
<p align=right>Requisição interna nº  

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
</style>
<style>
/** Define the margins of your page **/
@page {
	margin: 100px 25px;
}

header {
	position: fixed;
	top: -60px;
	left: 0px;
	right: 0px;
	height: 50px;

	/** Extra personal styles 
	background-color: #03a9f4;
	color: white;**/
	text-align: center;
	line-height: 10px;
}
/*rodape*/
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


	
$html.= '<br><table width=100%>';	
$html.= '<thead>';	
$html.= '<tr style="background-Color: #000000;color:#FFFFFF;">';	
$html.= '<td width="230">Item</td>';	
$html.= '<td align="center" width="50">Quantidade</td>';	
$html.= '</tr>';	
$html.= '</thead>';	

//$select=$pdo->prepare("select * from tbl_remesa where idrequisicao=$id");
//ß$select->execute();

//while($item=$select->fetch(PDO::FETCH_OBJ)){
$html.='<tbody>';
$html.='<tr><td align="center"></td>';
$html.='<td></td>';
$html.='<td align=center></td>';
$html.='</tbody>';
//}




$html.='<div align=center><br><br>O Presidente do ...<br>';
$html.='<br><img src="../images/signiture.jpg" width="100" height="80"><br>
____________________________________<br>/ AURELIO MAXWELL ALBINO /</div>

<div id="footer">
SIGECA -  <i>Software</i>
            <p class="page"></p>
     
Impresso em '.date('d/m/Y').'</i></div>';   

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');//, 'landscape'
$dompdf->render();
//$dompdf->setFont('defaultFont', 'Courier');
// Output the generated PDF to Browser
$dompdf->stream(
	"Requisicao.pdf",
		array(
			"Attachment" =>false //para realizar download automatico alterar para true
		)
	);
?>