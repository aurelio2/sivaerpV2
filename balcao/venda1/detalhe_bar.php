
<?php
include_once'../dbconnect.php';
include_once'../conexao.php';
error_reporting(0);
session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="Admin"){
  header('location:../index.php');
}

include_once 'headeruser.php';

	$id = $_GET['id'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Datalhes do pedido nº <b><?php echo $id?><b>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
		<br>Detalhes desta compra<br><br><br>
		<table id="salesreporttable" class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Produto</th>  
                  <th>Quantidade</th>
                  <th>Preco Unit</th>
                  <th align="right">Total</th>
                </tr>                    
              </thead>              
              <tbody>
         <?php
		 $cons = mysqli_query($mysqli,"SELECT * FROM tbl_invoice_details WHERE invoice_id = $id");
		 while($res=mysqli_fetch_array($cons)){
		 
				echo'
                  <tr>
                  <td rowsapan=2></td>
                  <td>'.$res['product_name'].'</td>
                  <td>'.$res['qty'].'</td>
                  <td>'.number_format($res['price'],2).'</td>
                  <td>'.number_format($res['total'],2).'</td>
                  ';
                }          
                ?>                       
              </tbody>               
            </table>     

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






