<?php
include_once'../dbconnect.php';
include_once'../conexao.php';
require_once '../dompdf/autoload.inc.php';
error_reporting(1);
use Dompdf\Dompdf;

$data1 = $_GET['dia'];
$data2 = $_GET['dia2'];

$sql3 = mysqli_query($mysqli,"SELECT * FROM empresa");
$res3 = mysqli_fetch_array($sql3);
$nome_db = $res3['nome'];
$nuit_db = $res3['nuit'];
$contacto_db = $res3['contacto'];
$address_db = $res3['address'];

$html = '
<div style="font-family: Courier; font-size: 13px; text-align: center; margin-bottom: 12px;">
    <!--<img src="../images/Siva.png" alt="" width="135px" style="margin-bottom: 10px;"><br>-->
    <b>'.$nome_db.'</b><br>
    Transferencia de Armazem para LOJA / BAR / COZINHA
    <div style="text-align: left; font-family: Arial; font-size: 14px; margin-top: 10px;">
        NUIT: <b>'.$nuit_db.'</b><br>
        Cell: +285 <b>'.$contacto_db.' </b><br>
        Localização: <b>'.$address_db.' </b><br>
    </div>
</div>

<p style="text-align: center; font-family: Arial; font-size: 14px;">
    MAPA DE CONTROLE DE <b>'.$nome_db.'</b>, REFERENTE AO DIA <b>' . $data1 . '</b> até DIA <b>' . $data2 . '</b>
</p>

<table style="width: 100%; font-family: Courier; font-size: 13px; border-collapse: collapse; text-align: center;">
    <thead>
        <tr style="background-color: #EEEEEE;">
            <th style="border: 1px solid #CCCCCC; padding: 5px;">Ordem</th>
            <th style="border: 1px solid #CCCCCC; padding: 5px;">ID do Produto</th>
            <th style="border: 1px solid #CCCCCC; padding: 5px;">Inicial</th>
            <th style="border: 1px solid #CCCCCC; padding: 5px;">Transferido</th>
            <th style="border: 1px solid #CCCCCC; padding: 5px;">Final</th>
            <th style="border: 1px solid #CCCCCC; padding: 5px;">Data de Transferência</th>
        </tr>
    </thead>
    <tbody>
';

$select = $pdo->prepare("
    SELECT 
        product_id, 
        SUM(quantity) AS total_quantity, 
        date_transfer 
    FROM tbl_transfer_history 
    WHERE date_transfer BETWEEN :fromdate AND :todate 
    GROUP BY product_id, date_transfer
");
$select->bindParam(':fromdate', $data1);  
$select->bindParam(':todate', $data2);  
$select->execute();

$i=1;

while ($row = $select->fetch(PDO::FETCH_OBJ)) {
    $produto = $row->product_id;

    // Buscar informações do produto
    $cons = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE pid = '$produto'");
    $res = mysqli_fetch_array($cons);
    $product_name = $res['pname'];

    // Buscar informações do estoque inicial
    $cons1 = mysqli_query($mysqli, "SELECT * FROM tbl_armazem WHERE pid = '$produto'");
    $res1 = mysqli_fetch_array($cons1);
    $stock_inicial = $res1['pstock'];

    //$stock_final = $stock_inicial + $row->total_quantity;

    // Adicionar linhas à tabela
    $html .= '
    <tr>
        <td style="border: 1px solid #CCCCCC; padding: 5px;">' . $i++ . '</td>
        <td style="border: 1px solid #CCCCCC; padding: 5px;">' . $product_name . '</td>
        <td style="border: 1px solid #CCCCCC; padding: 5px;">' . number_format($stock_inicial+$row->total_quantity) . '</td>
        <td style="border: 1px solid #CCCCCC; padding: 5px;">' . number_format($row->total_quantity) . '</td>
        <td style="border: 1px solid #CCCCCC; padding: 5px;">' . number_format($stock_inicial) . '</td>
        <td style="border: 1px solid #CCCCCC; padding: 5px;">' . $row->date_transfer . '</td>
    </tr>';
}

$html .= '
    </tbody>
</table>

<div style="text-align: center; font-family: Courier; font-size: 13px; margin-top: 20px;">
    <b>'.$nome_db.'</b>, aos ' . date('d') . ' de ' . date('F') . ' de ' . date('Y') . '<br><br>
    O RESPONSÁVEL<br><br>
    _________________________________<br><br>
</div>

<div id="footer" style="position: fixed; bottom: 0; width: 100%; text-align: right; font-family: Arial; font-size: 10px; border-top: 1px solid gray; padding-top: 5px;">
    Impresso no dia ' . date('d/m/Y') . ' Hora: ' . date('H:i') . '<br>
    <b>SIVA SOFTWARE Todos os direitos reservados</b>
</div>
';	

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','landscape');//, 'landscape'
$dompdf->render();
$dompdf->stream(
	"Relatorio de Transferincia do dia ".$data1." ate ".$data2.".pdf",
	array(
			"Attachment" =>false //para realizar download automatico alterar para true
		)
);
?>