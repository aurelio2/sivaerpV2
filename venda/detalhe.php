
<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(0);

include_once 'cabecalho_user.php';
	$id = $_GET['id'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Datalhes da venda nº <b><?php echo $id?><b>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
        <div class="box-header with-border">
		<br>Detalhes deste venda<br><br><br>
		<table id="salesreporttable" class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Produto</th>  
                  <th>Qtd</th>
                  <th>Preco Unit</th>
                </tr>                    
              </thead>              
              <tbody>
         <?php
		 $cons = mysqli_query($mysqli,"SELECT * FROM tbl_itens_saidos WHERE id_saida = $id");
		 while($res=mysqli_fetch_array($cons)){
		 
				echo'
                  <tr>
                  <td rowsapan=2></td>
                  <td>'.$res['produto'].'</td>
                  <td>'.$res['quant'].'</td>
                  <td>'.number_format($res['valor'],2).'</td>
                  ';
                }          
                ?>                       
              </tbody>               
            </table>     

      </div>
      <a href="diario.php"><img src="../images/icons8-circled_left.png" width="40" height="40" title="Voltar para pagina de relatorio"> </a>
      
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

include_once'footer.php';

?>






