<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
error_reporting(0);



include_once'header.php';

?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Lista de Devedores 
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
      <li class="active">Here</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content container-fluid">
        <div class="box box-warning">
          <form  action="" method="post" name="">
          
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <!--divida sum for day-->
             <?php
              

              $query = "SELECT SUM(valor) as soma FROM tbl_devida where estado ='0'";

              
              $consulta = $pdo->prepare($query);
             
              $consulta->execute();
              $resultado = $consulta->fetch(PDO::FETCH_OBJ);
      
             ?>

          <!-- Info boxes -->

          <div class="row">
            <!-- fix for small devices only -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-green"> <i class="fa fa-bar-chart" style="font-size:45px"></i></span>
                  <div class="info-box-content">
                    	Total de dividas
                      <h2> <b><?php echo number_format($resultado->soma,2); ?></b> MT<h2></span>
                   		
                  </div>
                </div>               
              </div> 
            <!-- /.col -->
            <!-- /.col -->
          </div>
          <!-- /.row -->                                  
          <br>

          <div style="overflow-x:auto;" >                         
            <table id="salesreporttable" class="table table-striped">
              <thead>
                <tr>
                  <th>Ord</th>
                  <th>Cod.venda</th>
                  <th>Usuario</th>
                  <th>Mesa</th>
                  <th>Cliente</th>
                  <th>Celular</th>
                  <th>Data</th> 
                  <th>Divida</th>
                  <th>Estado</th>
                </tr>    
              </thead> 

              <tbody>
                 <?php
              
                
                $sqli = mysqli_query($mysqli,"SELECT * FROM tbl_devida WHERE estado = 0");
                 $i=1;
                while($linha = mysqli_fetch_array($sqli)){
                  $invoice = $linha['id_invoice'];

                  //pegando nome
                  $cons = mysqli_query($mysqli, "select * from client_order_detalhes where invoice_id = $invoice ");
                  $res=mysqli_fetch_array($cons);

                  //pegando user, e mesa
                  $cons1 = mysqli_query($mysqli, "select * from tbl_invoice where invoice_id = $invoice ");
                  $res1=mysqli_fetch_array($cons1);

                  //pegar divida e celular
                  $cons2 = mysqli_query($mysqli, "select * from tbl_invoice where invoice_id = $invoice ");
                  $res2=mysqli_fetch_array($cons2);
                   
                  echo'
                  <tr>
                  <td>'.$i++.'</td>
                  <td>'.$invoice.'</td>
                  <td>'.$res1['customer_name'].'</td>
                  <td>'.$res1['mesa'].'</td>
                  <td>'.$res['nome'].'</td>
                  <td>'.$linha['celular'].'</td>
                  <td>'.$linha['data'].'</td>
                  ';
                  if($linha['estado']=='0'){
                    echo '  <td align="left"><span class="label label-danger">'.$linha['valor'] ." MT".'</span>
                  <a href="control_devida.php?id='.$invoice.'&id_divida='.$linha[0].'"><img src="../images/icons8-request_money.png" width="30" height="30" title="Processar pagamento"></a></span>
                  </td>';
                    echo '<td align="left"><span class="label label-danger">Não paga
                    </td>';
                  }else{
                    echo '<td align="left"><span class="label label-success">SEM DIVIDA</span>
                  
                  </td>';

                    echo '<td align="left"><span class="label label-success">Pago</span></td>';
                  }
                  

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

<script>

    //Date picker

    $('#datepicker1').datepicker({

      autoclose: true

    });


    //Date picker

    $('#datepicker2').datepicker({

      autoclose: true

    });  

    $('#salesreporttable').DataTable({

      "order":[[0,"desc"]]    

    });

  </script>

  <?php

  include_once'footer.php';

  ?>













