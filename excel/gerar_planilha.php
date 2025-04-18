 <?php
	
	//include("../conexao.php");
	include("../dbconnect.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Relatorio de Entradas</title>
	<head>
	<body>
		<?php
		$data1 = $_GET['dia'];
		$data2 = $_GET['dia2'];



		// Definimos o nome do arquivo que será exportado
		$arquivo = 'Entrdas-'.$data1.'-'.$data2.'.xls';
		
		// Criamos uma tabela HTML com o formato da planilha
		$html = '';
		$html .= '<table border="1">';
		$html .= '<tr style="background-Color: #EEEEEE;">';
		$html .= '<td colspan="5" align="center">Planilha de Entrada de Produto</tr>';
		$html .= '</tr>';
		
		
		$html .= '<tr>';
		$html.='<tr style="background-Color: #EEEEEE;">';
		$html.='<th width="80" align="center">CODIGO</th>';
		$html.='<th width="180" align="left">PRODUTO</th>';
		$html.='<th width="80">QUANTIDADE</th>';
		$html.='<th width="90" align="center">QTD.ANTERIOR</th>';
		$html.='<th width="90" align="center">TOTAL</th>';
		$html.='<th width="90" align="center">DATA</th>';

		$html .= '</tr>';
		$consul_s = $pdo->prepare("select * from tbl_entradas where data BETWEEN '$data1' and '$data2'");
		$consul_s->execute();
		//Selecionar todos os itens da tabela 
		while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
			$html .= '<tr>';
			$html .= '<td align="left">'.$row->idproduto.'</td>';
			$html .= '<td align="left">'.$row->produto.'</td>';
			$html .= '<td align="center">'.$row->quantidade.'</td>';
			$html .= '<td align="center">'.$row->qtd_anterior.'</td>';
			$html .= '<td align="center">'.(number_format($row->qtd_anterior)+number_format($row->quantidade)).'</td>';		
			$html .= '<td align="center">'.$row->data.'</td>';

			$html .= '</tr>';
			
		}
		// Configurações header para forçar o download
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo
		echo $html;
		exit; ?>
	</body>
</html>