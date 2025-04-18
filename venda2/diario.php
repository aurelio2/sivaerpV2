<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(0);

include_once'cabecalho_user.php';


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Relatorio diario <img src="../images/icons8-tear_off_calendar.png">
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
      <li class="active">Here</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
          <form  action="" method="post" name="">
           
            <div class="box-header with-border">
              <h3 class="box-title">Relatorio do dia : <?php echo $_POST['date_1']?> 
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="box-body">                  
              <div class="row">            
                <div class="col-md-5">            
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="date" class="form-control pull-right" id="datepicker1" name="date_1"  data-date-format="AAAA-MM-DD" id="date_1" value="<?php echo date('Y-m-d');?>">
                  </div>           
                </div>      
                
                <div class="col-md-2">
                  <div align="left">

                   <input type="submit" name="btndatefilter" value="Buscar" class="btn btn-success">

                 </div>
                 
               </div>                           
             </div>  
             
             <br>
              <!--Emola sum for day-->
             <?php
              $query = "SELECT SUM(emola) as emolatotal FROM tbl_control_payment WHERE data = ? and user='$idUser'";
              $date = $_POST['date_1'];

              // Prepare the statement
              if ($stmt = $mysqli->prepare($query)) {
                  // Bind the parameter
                  $stmt->bind_param("s", $date);

                  // Execute the query
                  $stmt->execute();

                  // Bind the result variable
                  $stmt->bind_result($emola_total);

                  // Fetch the result
                  $stmt->fetch();

                  // Close the statement
                  $stmt->close();
              } else {
                  die("Error: " . $mysqli->error);
              } 
      
             ?>

             <!--Pos sum for day-->
             <?php
              $query = "SELECT SUM(pos) as postotal FROM tbl_control_payment WHERE data = ? and user='$idUser'";
              $date = $_POST['date_1'];
              // Prepare the statement
              if ($stmt = $mysqli->prepare($query)) {
                  // Bind the parameter
                  $stmt->bind_param("s", $date);
                  // Execute the query
                  $stmt->execute();
                  // Bind the result variable
                  $stmt->bind_result($pos_total);
                  // Fetch the result
                  $stmt->fetch(); 
                  // Close the statement
                  $stmt->close();
              } else {
                  die("Error: " . $mysqli->error);
              } 
            ?>
           
             <?php
             $select=$pdo->prepare("SELECT sum(subtotal) as total,count(invoice_id) as invoice from tbl_invoice  where order_date=:fromdate and customer_name='$Nome' and user='$idUser' ");
             $select->bindParam(':fromdate',$_POST['date_1']);  
             $select->execute();
             $row=$select->fetch(PDO::FETCH_OBJ);
             $net_total=$row->total;
             $invoice=$row->invoice;                    
             ?>

             <?php
             $select1=$pdo->prepare("SELECT sum(total) as total1,count(invoice_id) as invoice from tbl_invoice  where order_date=:fromdate and customer_name='$Nome' and user='$idUser'");
             $select1->bindParam(':fromdate',$_POST['date_1']);  
             
             $select1->execute();             
             $row=$select1->fetch(PDO::FETCH_OBJ);            
             $net_total1=$row->total1;                 
             ?>

             <!--Cash sum for day-->
             <?php
             $select2=$pdo->prepare("select sum(cash) as cashtotal from tbl_control_payment where data=:fromdate and user='$idUser'");
             $select2->bindParam(':fromdate',$_POST['date_1']);  
             $select2->execute();
             $row=$select2->fetch(PDO::FETCH_OBJ);
             $cash_total=$row->cashtotal;
                             
             ?>

              <!--Recebido to be a cash-->
            <?php
             $select2=$pdo->prepare("select sum(valor_recebido-troco) as cash2 from tbl_control_payment where data=:fromdate and user='$idUser'");
             $select2->bindParam(':fromdate',$_POST['date_1']);  
             $select2->execute();
             $row=$select2->fetch(PDO::FETCH_OBJ);
             $cash2_total=$row->cash2;
                             
             ?>

              <!--Mpesa sum for day-->
             <?php
             $select3=$pdo->prepare("select sum(mpesa) as mpesatotal from tbl_control_payment where data=:fromdate and user='$idUser'");
             $select3->bindParam(':fromdate',$_POST['date_1']);  
             $select3->execute();
             $row=$select3->fetch(PDO::FETCH_OBJ);
             $mpesa_total=$row->mpesatotal;                    
             ?>

              <!--divida sum for day-->
             <?php
              $query = "SELECT SUM(valor) as dividatotal FROM tbl_devida WHERE data = ? and estado ='0' and user='$idUser'";
              $date = $_POST['date_1'];

              // Prepare the statement
              if ($stmt = $mysqli->prepare($query)) {
                  // Bind the parameter
                  $stmt->bind_param("s", $date);

                  // Execute the query
                  $stmt->execute();

                  // Bind the result variable
                  $stmt->bind_result($dividatotal);

                  // Fetch the result
                  $stmt->fetch();

                  // Close the statement
                  $stmt->close();
              } else {
                  die("Error: " . $mysqli->error);
              } 
      
             ?>
             
             <!-- Info boxes -->
             <div class="row">
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><img src="../images/icons8-shopping_cart.png"></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Total de vendas</span>
                    <span class="info-box-number"><h2><?php echo number_format($invoice); ?></h2></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
  
              <div class="clearfix visible-sm-block"></div>
              <!-- /.col -->
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-yellow"> MT</span>

                  <div class="info-box-content">
                    <span class="info-box-text">Total</span>
                    <span class="info-box-number"><h2><?php echo number_format($net_total,2); ?></h2></span>
					     </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-yellow"> MT</span>

                  <div class="info-box-content">
                    <span class="info-box-text">Total com Imposto</span>
                    <span class="info-box-number"><h2><?php echo number_format($net_total1,2); ?></h2></span>
               </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
			  
			         <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-blue"> <i class="fa fa-pie-chart" style="font-size:42px"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Relatorio</span>
                    <span class="info-box-number"><h2> <a href="../recibos/diario_bar.php?dia=<?php echo $_POST['date_1']?>" target="_blank">Diario</a> 					
                  </div>
                </div>               
              </div>

               <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-green"> <i class="fa fa-bar-chart" style="font-size:45px"> <a href="../recibos/fecho_dia.php?dia=<?php echo $_POST['date_1']?>" target="_blank"><img src="../images/pdf.ico" width="30px" title="Fecho do Dia: <?php echo number_format($cash_total+$cash2_total+$mpesa_total+$emola_total+$pos_total,2); ?>"></a> </i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">CASH: <b><?php echo $cash_total+$cash2_total; ?></b> MT</span>
                    <span class="info-box-text">M-PESA: <b><?php echo $mpesa_total; ?></b> MT</span>
                    <span class="info-box-text">E-MOLA: <b><?php echo $emola_total; ?></b> MT</span>
                    <span class="info-box-text">POS: <b><?php echo $pos_total; ?></b> MT</span>

                  </div>
                </div>               
              </div> 
               <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-green"> <i class="fa fa-bar-chart" style="font-size:45px"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total de divida
                      <h2> <b><?php echo number_format($dividatotal,2); ?></b> MT<h2></span>
                      
                  </div>
                </div>               
              </div> 
            
              <!-- /.col -->
            </div>
            <!-- /.row -->                                                                  
            <br>

            <table id="salesreporttable" class="table table-striped">
              <thead>
                <tr>
                  <th>Data</th>
                  <th>Usuario</th>  
                  <th>Cliente</th>  
                  <th align="left">Subtotal</th>
                  <th align="left">Total</th>
                  <th>Reimprimir</th>  
                  <th>Conteudo</th>
                  <th>Divida</th>
                </tr>                    
              </thead>              
              <tbody>
                
                <?php
                $select=$pdo->prepare("SELECT * from tbl_invoice  where order_date=:fromdate and customer_name='$Nome' and user='$idUser'");
                $select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
                
                $select->execute();
                
                while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                  $id_invoice = $row->invoice_id;


                  $seach = mysqli_query($mysqli,"SELECT * FROM tbl_devida where id_invoice = $id_invoice and user='$idUser'");
                  $ver=mysqli_fetch_array($seach);

                  $seachCliente = mysqli_query($mysqli,"SELECT * FROM client_order_detalhes where invoice_id = $id_invoice");
                  $verCliente=mysqli_fetch_array($seachCliente);
                  
                  echo'
                  <tr>
                  <td>'.$row->order_date.'</td>
                  <td>'.$row->customer_name.'</td>
                  <td>'.$verCliente['nome'].'</td>
                  <td align="left"><span class="label label-success">'.$row->subtotal ." MT".'</span></td>
                  <td align="left"><span class="label label-success">'.$row->total ." MT".'</span></td>
                  <td align="center"><a href="../recibo1/pdf.php?id='.$row->invoice_id.'" target="_blank"><img src="../images/print.png" width="20" height="20" title="Imprimir rebido da venda"></a></td>
                  <td align="center"><a href="detalhe_bar.php?id='.$row->invoice_id.'"><span class="glyphicon glyphicon-plus"></span></a></td>
                
                  
                  ';
                  if($ver['estado']==0){
                    echo '  <td align="left"><span class="label label-danger">'.$ver['valor'] ." MT".'</span>
                  <a href="control_devida.php?id='.$row->invoice_id.'&id_divida='.$ver[0].'"><img src="../images/icons8-request_money.png" width="30" height="30" title="Processar pagamento"></a></span>
                  </td>';
                    
                  }else if($ver['estado']==1){
                   echo '<td align="left"><span class="label label-success">Paga</span></td>';
                  }
                  
                  
                }          
                ?>                       
              </tbody>               
            </table>                                                                 
           
		      
		
          </div>

        </form>
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






