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
      Relátorio Periodico de devedores 
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
            <div class="box-header with-border">
              <h3 class="box-title">De : <?php echo $_POST['date_1']?> -- Ate : <?php echo $_POST['date_2']?> Numero: <span style="color: red;"><?php echo $_POST['txtnumero']; ?></span></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="date" class="form-control pull-right"  name="date_1"  data-date-format="AAAA-MM-DD" >
                  </div>
                </div>   
                <div class="col-md-3">
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="date_2"  data-date-format="AAAA-MM-DD" >
                </div> 
              </div>
              <div class="col-md-3">
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-sort-numeric-asc"></i>
                  </div>
                  <input type="text" class="form-control pull-right" name="txtnumero"  data-date-format="AAAA-MM-DD" placeholder="Numero do celuar">
                </div> 
              </div>               
              <div class="col-md-2">
               <div align="left">
                <input type="submit" name="btndatefilter" value="Buscar" class="btn btn-success">
              </div>
            </div>              
          
          </div>  
         
          <br>
          <br> 
              <!--divida sum for day-->
             
             <?php
                $date_1 = isset($_POST['date_1']) && !empty($_POST['date_1']) ? $_POST['date_1'] : null;
                $date_2 = isset($_POST['date_2']) && !empty($_POST['date_2']) ? $_POST['date_2'] : null;
                $numero = isset($_POST['txtnumero']) && !empty($_POST['txtnumero']) ? $_POST['txtnumero'] : null;

                // Iniciar a query sem restrições de filtro
                $query = "SELECT SUM(valor) as soma FROM tbl_devida WHERE estado = '0'";

                // Verificar se os filtros de data foram fornecidos e adicioná-los à consulta
                if ($date_1 && $date_2) {
                    $query .= " AND data BETWEEN :data1 AND :data2";
                }

                // Verificar se o número de celular foi fornecido e adicioná-lo à consulta
                if ($numero) {
                    $query .= " AND (celular = :numero OR celular IS NULL)";
                }

                // Preparar a consulta
                $consulta = $pdo->prepare($query);

                // Se os filtros de data existirem, adicionar os parâmetros
                if ($date_1 && $date_2) {
                    $consulta->bindParam(':data1', $date_1);
                    $consulta->bindParam(':data2', $date_2);
                }

                // Se o filtro de número existir, adicionar o parâmetro
                if ($numero) {
                    $consulta->bindParam(':numero', $numero);
                }

                // Executar a consulta
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
                    <span class="info-box-text"><a href="../recibos/lista_devedores.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>&numero=<?php echo $_POST['txtnumero'] ?>" target="_blank">Total de dividas</a>
                      <h2> <b><?php echo number_format($resultado->soma,2); ?></b> MT<h2></span>
                   		
                  </div>
                </div>  
                <a href="../recibo1/imprimir_divida.php?number=<?php echo $_POST['txtnumero'];?>" target="_blank">Imprimir </a>             
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
              $data1 = isset($_POST['date_1']) && !empty($_POST['date_1']) ? $_POST['date_1'] : null;
              $data2 = isset($_POST['date_2']) && !empty($_POST['date_2']) ? $_POST['date_2'] : null;
              $numero = isset($_POST['txtnumero']) && !empty($_POST['txtnumero']) ? $_POST['txtnumero'] : null;

              // Iniciar a query com a condição de estado
              $query = "SELECT * FROM tbl_devida WHERE estado IN ('0', '1')";

              // Se os filtros de data foram fornecidos, adicioná-los à consulta
              if ($data1 && $data2) {
                  $query .= " AND data BETWEEN '$data1' AND '$data2'";
              }

              // Se o número de celular foi fornecido, adicionar à consulta
              if ($numero) {
                  $query .= " AND celular = '$numero'";
              }

              // Executar a consulta
              $sqli = mysqli_query($mysqli, $query);

              // Verificar se a consulta retornou resultados
              if (mysqli_num_rows($sqli) > 0) {
                  while ($linha = mysqli_fetch_array($sqli)) {
                      $invoice = $linha['id_invoice'];

                      // Pegar nome do cliente
                      $cons = mysqli_query($mysqli, "SELECT * FROM client_order_detalhes WHERE invoice_id = $invoice");
                      $res = mysqli_fetch_array($cons);

                      // Pegar dados do usuário e da mesa
                      $cons1 = mysqli_query($mysqli, "SELECT * FROM tbl_invoice WHERE invoice_id = $invoice");
                      $res1 = mysqli_fetch_array($cons1);

                      // Pegar dívidas e celular
                      $cons2 = mysqli_query($mysqli, "SELECT * FROM tbl_invoice WHERE invoice_id = $invoice");
                      $res2 = mysqli_fetch_array($cons2);

                      // Exibir os resultados na tabela
                      echo '
                      <tr>
                          <td>' . $invoice . '</td>
                          <td>' . $res1['customer_name'] . '</td>
                          <td>' . $res1['mesa'] . '</td>
                          <td>' . $res['nome'] . '</td>
                          <td>' . $linha['celular'] . '</td>
                          <td>' . $linha['data'] . '</td>';

                      if ($linha['estado'] == '0') {
                          echo '<td align="left"><span class="label label-danger">' . $linha['valor'] . ' MT</span>
                              <a href="control_devida.php?id=' . $invoice . '&id_divida=' . $linha[0] . '"><img src="../images/icons8-request_money.png" width="30" height="30" title="Processar pagamento"></a></span>
                          </td>';
                          echo '<td align="left"><span class="label label-danger">Não paga</span></td>';
                      } else {
                          echo '<td align="left"><span class="label label-success">SEM DÍVIDA</span></td>';
                          echo '<td align="left"><span class="label label-success">Pago</span></td>';
                      }

                      echo '</tr>';
                  }
              } else {
                  echo '<tr><td colspan="8">Nenhum resultado encontrado.</td></tr>';
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













