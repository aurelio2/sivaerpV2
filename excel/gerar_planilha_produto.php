 <?php
	
	//include("../conexao.php");
	include("../dbconnect.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>LISTA DE PRODUTOS</title>
	<head>
	<body>
		<?php
		


		// Definimos o nome do arquivo que será exportado
		$arquivo = 'Produtos.xls';
		
		// Criamos uma tabela HTML com o formato da planilha
		$html = '';
		$html .= '<table border="1">';
		$html .= '<tr style="background-Color: #EEEEEE;">';
		$html .= '<td colspan="5" align="center">Planilha de Produto</tr>';
		$html .= '</tr>';
		
		
		$html .= '<tr>';
		$html.='<tr style="background-Color: #EEEEEE;">';
		$html.='<th width="100" align="center">CODIGO</th>';
		$html.='<th width="200" align="left">PRODUTO</th>';
		$html.='<th width="140">CATEGORIA</th>';
		$html.='<th width="140" align="center">PREÇO DE VENDA</th>';
		$html .= '</tr>';
		$consul_s = $pdo->prepare("select * from tbl_product");
		$consul_s->execute();
		//Selecionar todos os itens da tabela 
		while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
			$html .= '<tr>';
			$html .= '<td align="left"> '.$row->pid.'</td>';
			$html .= '<td align="left">'.$row->pname.'</td>';
			$html .= '<td align="center">'.$row->pcategory.'</td>';	
			$html .= '<td align="center">'.number_format($row->saleprice,2).' MT</td>';
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