<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(0);

include_once'header.php';

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
              <?php
              $query = "SELECT SUM(emola) as emolatotal FROM tbl_control_payment WHERE data = ?";
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
              $query = "SELECT SUM(pos) as postotal FROM tbl_control_payment WHERE data = ?";
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
             
             
             $select=$pdo->prepare("select sum(subtotal) as total,count(invoice_id) as invoice from tbl_invoice  where order_date=:fromdate ");
             $select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
             
             $select->execute();
             
             $row=$select->fetch(PDO::FETCH_OBJ);
             
             $net_total=$row->total;
             
             //$stotal=$row->stotal;
             
             $invoice=$row->invoice;                    
             
             ?>

             <?php
             
             
             $select1=$pdo->prepare("select sum(total) as total1,count(invoice_id) as invoice from tbl_invoice  where order_date=:fromdate ");
             $select1->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
             
             $select1->execute();
             
             $row=$select1->fetch(PDO::FETCH_OBJ);
             
             $net_total1=$row->total1;
             
             //$stotal=$row->stotal;
             
             //$invoice=$row->invoice;                    
             
             ?>
               <!--Cash sum for day-->
             <?php
             $select2=$pdo->prepare("select sum(cash) as cashtotal from tbl_control_payment where data=:fromdate ");
             $select2->bindParam(':fromdate',$_POST['date_1']);  
             $select2->execute();
             $row=$select2->fetch(PDO::FETCH_OBJ);
             $cash_total=$row->cashtotal;
                             
             ?>

             <!--Recebido to be a cash-->
            <?php
             $select2=$pdo->prepare("select sum(valor_recebido-troco) as cash2 from tbl_control_payment where data=:fromdate ");
             $select2->bindParam(':fromdate',$_POST['date_1']);  
             $select2->execute();
             $row=$select2->fetch(PDO::FETCH_OBJ);
             $cash2_total=$row->cash2;
                             
             ?>
              <!--Mpesa sum for day-->
             <?php
             $select3=$pdo->prepare("select sum(mpesa) as mpesatotal from tbl_control_payment where data=:fromdate ");
             $select3->bindParam(':fromdate',$_POST['date_1']);  
             $select3->execute();
             $row=$select3->fetch(PDO::FETCH_OBJ);
             $mpesa_total=$row->mpesatotal;                    
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
              <!-- /.col -->
              

              <!-- fix for small devices only -->
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
                  <span class="info-box-icon bg-green"> <i class="fa fa-bar-chart" style="font-size:45px"> <a href="../recibos/fecho_dia.php?dia=<?php echo $_POST['date_1']?>" target="_blank"><img src="../images/pdf.ico" width="30px" title="Fecho do Dia: <?php echo number_format($cash_total+$cash2_total+$mpesa_total+$emola_total+$pos_total,2); ?>"></a></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">CASH: <b><?php echo $cash_total+$cash2_total; ?></b> MT</span>
                    <span class="info-box-text">M-PESA: <b><?php echo $mpesa_total; ?></b> MT</span>
                    <span class="info-box-text">E-MOLA: <b><?php echo $emola_total; ?></b> MT</span>
                    <span class="info-box-text">POS: <b><?php echo $pos_total; ?></b> MT</span>
                  </div>
                </div>               
              </div> 
              <!-- /.col -->
            </div>
            <!-- /.row -->                                                                  
            <br>
            <div style="overflow-x:auto;" >
            <table id="salesreporttable" class="table table-striped">
              <thead>
                <tr>
                  <th>Data</th>
                  <th>Mesa</th> 
                  <th>Nome</th> 
                  <th>N Recibo</th> 
                  <th>Usuario</th>  
                  <th align="left">Total</th>
                  <th>Reimprimir</th>  
                  <th>Conteudo</th>  
                </tr>                    
              </thead>              
              <tbody>
                
                <?php
                $select=$pdo->prepare("select * from tbl_invoice  where order_date=:fromdate order by invoice_id DESC");
                $select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
                
                $select->execute();
                
                while($row=$select->fetch(PDO::FETCH_OBJ)  ){

                  $sql1 = mysqli_query($mysqli,"SELECT * FROM tbl_mesa where cod_mesa = $row->mesa");
                  $res1 = mysqli_fetch_array($sql1);
                  $id_mesa_db = $res1['id'];
                  $id_m_db = $res1['cod_mesa'];


                  $sql = mysqli_query($mysqli, "SELECT * FROM client_order_detalhes WHERE id =
                            (select MAX(id) from client_order_detalhes where invoice_id=$row->invoice_id ) order by id");

                  $res = mysqli_fetch_array($sql);
                  $nome_cliente_db = $res['nome'];

                  
                  echo'
                  <tr>
                  <td>'.$row->order_date.'</td>  
                  <td>'.$row->mesa .'</td>
                  <td>'.$nome_cliente_db.'</td>
                  <td align="left">'.$row->invoice_id .'</td>
                   <td>'.$row->customer_name.'</td>
                  <td align="left"><span class="label label-success">'.$row->total ." MT".'</span></td>
                  <td align="center"><a href="../recibo1/pdf.php?id='.$row->invoice_id.'" target="_blank"><img src="../images/print.png" width="20" height="20" title="Imprimir rebido da venda"></a></td>
                  <td align="center"><a href="detalhe_bar.php?id='.$row->invoice_id.'"><span class="glyphicon glyphicon-plus"></span></a></td>
                  
                  ';
                  
                }          
                ?>                       
              </tbody>               
            </table>                                                                 
            </div>
          
    
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
