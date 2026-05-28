<?php
include_once '../dbconnect.php';
include_once '../conexao.php';
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$data = $_GET['dia'];
$consul_s = $pdo->prepare("select * from tbl_product");
$consul_s->execute();

$sql3 = mysqli_query($mysqli,"SELECT * FROM empresa");
$res3 = mysqli_fetch_array($sql3);
$nome_db = $res3['nome'];
$nuit_db = $res3['nuit'];
$contacto_db = $res3['contacto'];

$select = $pdo->prepare("select sum(saleprice*pstock) as compra from tbl_product");
$select->execute();
$row2=$select->fetch(PDO::FETCH_OBJ);
$total_compras=$row2->compra;

$totalCompra = 0;
$totalStockFinal=0;


$select1 = $pdo->prepare("select sum(pstock) as stockfinal from tbl_product");
$select1->execute();
$row1=$select1->fetch(PDO::FETCH_OBJ);
$total_stock_final=$row1->stockfinal;
    
$html= '<div style="font-family: Courier; font-size: 14px;">
<div style="font-family: Courier; font-size: 14px;">

<p align="center">MAPA DE CONTROLE DE '.$nome_db.', REFERENTE AO DIA '.$data.'</p>
<style>
table {
  border-top:#FFFFFF 5px solid;
  border-collapse: collapse;
  border:#FFF8DC 5px double;
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
  border-bottom: 5px solid #ddd;
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
    border-top: 1px solid black;
}
#footer.page:after{ 
    content: counter(page); 
}
</style>
';
$html.= '<table style="font-family: Courier; font-size: 16px; ">';  
$html.='<tbody>';
$html.='<tr style="background-Color:rgb(203, 209, 252);">';
$html.='<th width="200" align="center">Produto</th>';
$html.='<th width="50" align="cenetr">Stock Inicial</th>';
$html.='<th width="50" align="cenetr">Entrada</th>';
$html.='<th width="50" align="center">Saida</th>';
$html.='<th width="50" align="center">Ajuste</th>';
$html.='<th width="50" align="center">Stock final</th>';
$html.='<th width="50" align="center">Pre&ccedil;o Unit.</th>';
$html.='<th width="90" align="center">Valor Total</th>';
$html.='<th width="40" align="center" hidden>Imposto/Iva (16%)</th>';
$html.='</tr></tbody>';

while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
$idprod=$row->pid;
$consul = mysqli_query($mysqli,"select tbl_invoice_details.product_id as id, SUM(tbl_invoice_details.qty) as saida, tbl_invoice_details.qty, tbl_invoice_details.order_date,tbl_invoice_details.t_iva,tbl_invoice_details.qty as finalStock
                        from tbl_invoice_details 
                        where order_date = '$data' and product_id = $idprod");
$sai = mysqli_fetch_array($consul);

$cons = mysqli_query($mysqli, "select SUM(quantidade) as quantidade, data,ajust, idproduto from tbl_entradas where idproduto = $idprod and data = '$data'");
$res=mysqli_fetch_array($cons);
$stock=$row->pstock;
// $qtd=$res['quantidade'];
// $saida=$sai['saida'];
$qtd = isset($res['quantidade']) ? $res['quantidade'] : 0;
$saida = isset($sai['saida']) ? $sai['saida'] : 0;
$stock_final = $sai['saida'];
$qtds=$res['quantidade'];
$stock = ($stock+$saida)-$qtd;
$ajuste=$res['ajust'];

// $stock_final2 = ($stock+$qtd)-$saida;
$stock_final2 = $stock - $saida + $qtd;

 // Verifica se houve algum resultado
    if ($sai) {
        $id = $sai['id'];
        $saida = $sai['saida'];
        $qty = $sai['qty'];
        $orderDate = $sai['order_date'];
        $tIva = $sai['t_iva'];

        // Agora, obtenha o purchaseprice do produto
        // $consul_purchase = mysqli_query($mysqli,"SELECT saleprice FROM tbl_product WHERE pid = $id");
        // $row_purchase = mysqli_fetch_array($consul_purchase);
        // $purchasePrice = $row_purchase['saleprice'];

          // Soma ao total
       // $totalCompra += $purchasePrice * $qty;

        $totalStockFinal = $saida;
    } else {
        //echo "Nenhum resultado encontrado para Product ID $idprod <br>";
    }

if($saida<1){
    $total = '-';
}else{
    $total=number_format($saida* $row->saleprice,2);
    $saida = $saida;
}

$html .= '<tr>';
$html .= '<td align="left">'.strtoupper($row->pname).'</td>';
$html .= '<td align="center">'.number_format($stock, 1).'</td>';
$html .= '<td align="center">'.($qtds ? number_format($qtds, 1) : '-').'</td>';
$html .= '<td align="center">'.($saida === '-' ? '-' : number_format($saida, 1)).'</td>';  
if($ajuste==0){
    $html .= '<td align="center">-</td>';       
}else{
    $html .= '<td align="center">'.number_format($ajuste, 1).'</td>'; 
}

$html .= '<td align="center">'.number_format($stock_final2, 1).'</td>';               
$html .= '<td align="right">'.number_format($row->saleprice,2).'&nbsp;</td>';               
$html .= '<td align="right">'.$total.'&nbsp;</td>';
$html .= '<td align="center">'.$sai['t_iva'].'</td>';                    
$html .= '</tr>';
}
$consulta = $pdo->prepare("select tbl_invoice_details.product_id, SUM(tbl_invoice_details.total) as soma,SUM(t_iva) as soma_iva, tbl_product.pid,SUM(tbl_invoice_details.qty) as saidaFinal from tbl_invoice_details 
                            INNER JOIN tbl_product ON tbl_product.pid = tbl_invoice_details.product_id
                            where order_date = '$data'");
        $consulta->execute();
        $resultado=$consulta->fetch(PDO::FETCH_OBJ);

//Somatorio de todas as dividas
$queryDivi = "SELECT SUM(valor) as totalDivida FROM tbl_devida WHERE data = ? and  estado=0";
$date = $data;

// Prepare the statement
if ($stmt = $mysqli->prepare($queryDivi)) {
    // Bind the parameter
	$stmt->bind_param("s", $date);
  // Execute the query
	$stmt->execute();
  // Bind the result variable
	$stmt->bind_result($totalDivida);
  // Fetch the result
	$stmt->fetch();
  // Close the statement
	$stmt->close();
} else {
	die("Error: " . $mysqli->error);
} 

$html .= '<tr style="background-Color: white;">
<td colspan=7>MERCADORIA ANTERIOR- - - - - - - - - - - - - - - - - - - - - - - - - - - - - --</td>

<td align="right"> '.number_format($total_compras+$resultado->soma,2).' MT&nbsp;</td>
<td align="center">  -----&nbsp;&nbsp;
</tr>';

$html .= '<tr style="background-Color: white;">
<td colspan=7>STOCK ANT- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --</td>

<td align="right"> '.number_format($total_stock_final+$resultado->saidaFinal, 1).'&nbsp;</td>
<td align="center">  -----&nbsp;&nbsp;
</tr>';
    
$html .= '<tr style="background-Color: white;">
<td colspan=7>TOTAL DE ITENS VENDIDOS - - - - - - - - - - - - - - - - - - - - - - - - - - ---</td>

<td align="right"> '.number_format($resultado->saidaFinal, 1).' &nbsp;</td>
<td align="center">  '.number_format($resultado->soma_iva,2).' MT&nbsp;&nbsp;
</tr>';
$html .= '<tr style="background-Color: white;">
<td colspan=7>TOTAL VENDIDO - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ---</td>

<td align="right">'.number_format($resultado->soma,2). '<b>('.number_format($resultado->soma-$totalDivida).')</b>MT</td>
<td align="center">  '.number_format($resultado->soma_iva,2).' MT&nbsp;&nbsp;
</tr>';
$html .= '<tr style="background-Color:white;">
<td colspan=7>TOTAL DIVIDA - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --</td>
<td align="right"> '.number_format($totalDivida,2).' MT&nbsp;</td>
<td align="center">  -----&nbsp;&nbsp;
</tr>';

$html .= '<tr style="background-Color: white;">
<td colspan=7>STOCK TOTAL ACTUAL- - - - - - - - - - - - - - - - - - - - - - - - - - - - - ---</td>

<td align="right"> '.number_format($total_stock_final, 1).'&nbsp;</td>
<td align="center">  -----&nbsp;&nbsp;
</tr>';


$html .= '<tr style="background-Color: white;">
<td colspan=7>MERCADORIA ACTUAL- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --</td>

<td align="right"> '.number_format($total_compras,2).' MT&nbsp;</td>
<td align="center">  -----&nbsp;&nbsp;
</tr>';









$html .= '</table><br><br>';

        

$mes=date('m');
$dia=date('d');
$ano=date('Y');
if($mes==1){$mes='Janeiro';}elseif($mes==2){$mes='Fevereiro';}elseif($mes==3){$mes='Mar&ccedil;o';}elseif($mes==4){$mes='Abril';}
if($mes==5){$mes='Maio';}elseif($mes==6){$mes='Junho';}elseif($mes==7){$mes='Julho';}elseif($mes==8){$mes='Agosto';}
if($mes==9){$mes='Setembro';}elseif($mes==10){$mes='Outubro';}elseif($mes==11){$mes='Novembro';}elseif($mes==12){$mes='Dezembro';}
$html.='<div align=center>&nbsp; '.$nome_db.', aos '.$dia.' de '.$mes.' de '.$ano.'<br>';
$html.='<br><br>O RESPONSAVEL<br>';
$html.='<br>_________________________________</div>

<div id="footer">
SiVA Software Todos os direitos reservados
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