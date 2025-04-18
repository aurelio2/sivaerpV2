
<?php
include_once'../dbconnect.php';
include_once'../conexao.php';
error_reporting(0);
session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="Admin"){
  header('location:../index.php');
}

include_once 'headeruser.php';

$data = date('Y-m-d');
$consul_s = $pdo->prepare("select * from tbl_product order by pcategory, pname");
$consul_s->execute();

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Datalhes do dia
    </h1>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
		<link rel="stylesheet" href="../include/tabela.css" />

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
		<br><br>
		<div align="center">
		<table class="grid" style="font-size: 15;" align="center" width="90%">
              <thead>
                <tr>
                  <th></th>
                  <th width="30%">Nome Produto</th>  
                  <th width="10%">Stock Inicial</th>
                  <th width="10%">Entrada</th>
                  <th width="10%">Saida</th>
                  <th width="10%">Stock final</th>
                  <th width="15%">Presta&ccedil;o Unit.</th>
                  <th align="right" width="15%">Valor Total</th>
                </tr>                    
              </thead>              
              <tbody>
         <?php
			
			while ($row = $consul_s->fetch(PDO::FETCH_OBJ)) {
			$idprod=$row->pid;
			$consul = mysqli_query($mysqli,"select * from tbl_saidas1 
									where data = '$data' and idproduto = $idprod");
			$sai = mysqli_fetch_array($consul);

			$consulta = mysqli_query($mysqli,"select SUM(qty) as qty from tbl_invoice_details 
									where order_date = '$data' and product_id = $idprod");
			$saidas = mysqli_fetch_array($consulta);

			$cons = mysqli_query($mysqli, "select SUM(quantidade) as quantidade, data, idproduto from tbl_entradas where idproduto = $idprod and data = '$data'");
			$res=mysqli_fetch_array($cons);
			$stock=$row->pstock;
			$qtd=$res['quantidade'];

			$stock_final = (($sai['stock_inicial']+$qtd)-$saidas['qty']);
					?>
			
                  <tr onMouseOver="style.backgroundColor='#FFF8DC'" onMouseOut="style.backgroundColor=''">
                 <?php
				 echo' 
				  <td></td>
                  <td>'.$row->pname.'</td>
                  <td align="center">'.$sai['stock_inicial'].'</td>
                  <td align="center">'.$qtd.'</td>
                  <td align="center">'.$saidas['qty'].'</td>
                  <td align="center">'.$stock_final.'</td>
                  <td align="right">'.number_format($row->saleprice,2).'&nbsp;&nbsp;&nbsp;</td>
                  <td align="right">'.number_format(($row->saleprice)*$saidas['qty'],2).'&nbsp;&nbsp;&nbsp;</td>
                  ';
                }          
                ?>                       
              </tbody>               
             
		<?php
		$consulta = $pdo->prepare("select tbl_invoice_details.product_id, SUM(tbl_invoice_details.total) as soma, tbl_product.pid, tbl_product.pcategory from tbl_invoice_details 
							INNER JOIN tbl_product ON tbl_product.pid = tbl_invoice_details.product_id
							where order_date = '$data'");
		$consulta->execute();
		$resultado=$consulta->fetch(PDO::FETCH_OBJ);
	
		echo '<tr style="background-Color: #EEEEEE;"><td></td>
		<td colspan=6>&nbsp;&nbsp;&nbsp;Total </td>
		<td align="right">'.number_format($resultado->soma,2).'&nbsp;&nbsp;</td></tr>';
		?>
		</table> 
		<br>
		<p>Para que os dados sejam validados, &eacute; importante clicar no bot&atilde;o Aceitar </p>
		<br>
		<?php
		
		if(isset($_POST['ok'])){
	
		$update=mysqli_query($mysqli,"UPDATE tbl_saidas1 
										JOIN tbl_product ON tbl_saidas.idproduto =tbl_product.pid 
										SET tbl_saidas1.stock_final = tbl_product.pstock 
										WHERE tbl_saidas1.data = '$data'");
		echo '<meta http-equiv="refresh" content="0;url=mesa.php">';
		}
			

			?>
		<form action="" method="post">
		<button type="submit" name="ok" class="btn btn-success btn-sm btnadd">ACEITAR</button>
		</form>
		<BR><BR>
			
		</div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script  type="text/javascript">
   

   $('#salesreporttable').DataTable({
    
    "order":[[0,"desc"]]    
    
    
    
  });
</script>


<?php

include_once'../venda/footer.php';

?>






