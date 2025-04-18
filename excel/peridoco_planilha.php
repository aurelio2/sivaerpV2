<!--**
 * @author Cesar Szpak - Celke -   cesar@celke.com.br
 * @pagina desenvolvida usando framework bootstrap,
 * o código é aberto e o uso é free,
 * porém lembre -se de conceder os créditos ao desenvolvedor.
 *-->
 <?php
	
	include("../conexao.php");
	include("../connect.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Lista de ocorrencias</title>
	<head>
	<body>
		<?php
		$data1 = $_GET['dia'];
		$data2 = $_GET['dia2'];



		// Definimos o nome do arquivo que será exportado
		$arquivo = 'ocorrencias- do dia-'.$data1.'-ate dia-'.$data2.'.xls';
		
		// Criamos uma tabela HTML com o formato da planilha
		$html = '';
		$html .= '<table border="1">';
		$html .= '<tr style="background-Color: #EEEEEE;">';
		$html .= '<td colspan="5" align="center">Planilha de Ocorrencia</tr>';
		$html .= '</tr>';
		
		
		$html .= '<tr>';
		$html.='<tr style="background-Color: #EEEEEE;">';
		$html.='<th width="150" align="center">Ocorrencias</th>';
		$html.='<th width="90" align="left">Instituição/Cliente</th>';
		$html.='<th width="100">Estado de Avarias</th>';
		$html.='<th width="90" align="center">Data</th>';
		$html.='<th width="90" align="center">Funcionario</th>';
		$html .= '</tr>';
		$consul_s = $pdo->prepare("select * from oco where data BETWEEN '$data1' and '$data2'");
		$consul_s->execute();
		//Selecionar todos os itens da tabela 
		while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
			$html .= '<tr>';
			$html .= '<td align="left">'.$row->anote.'</td>';
			$html .= '<td align="center">'.$row->inst.'</td>';
			$html .= '<td align="center">'.$row->estado.'</td>';
			$html .= '<td align="center">'.$row->data.'</td>';
			$html .= '<td align="center">'.$row->usuario.'</td>';					
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