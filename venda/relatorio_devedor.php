<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

error_reporting(0);
include_once'cabecalho_user.php';

// Buscar role do usuário logado
$user_role = '';
if(isset($_SESSION['txt_email'])) {
    $sql_role = mysqli_query($mysqli, "SELECT role FROM tbl_user WHERE useremail = '".$_SESSION['txt_email']."'");
    if($sql_role && mysqli_num_rows($sql_role) > 0) {
        $user_data = mysqli_fetch_array($sql_role);
        $user_role = strtolower($user_data['role']);
    }
}

?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Relátorio de devedores 
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
              <h3 class="box-title">De : <?php echo $_POST['date_1']?> </span></h3>
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
                $date_1 = isset($_POST['date_1']) ? $_POST['date_1'] : null;
               
                // Iniciar a query sem restrições de filtro
                $query = "SELECT SUM(valor) as soma FROM tbl_devida WHERE estado = '0'";

                // Verificar se os filtros de data foram fornecidos e adicioná-los à consulta
                if ($date_1) {
                    $query .= " AND data = :data1";
                }

              
                // Preparar a consulta
                $consulta = $pdo->prepare($query);

                // Se os filtros de data existirem, adicionar os parâmetros
                if ($date_1) {
                    $consulta->bindParam(':data1', $date_1);
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
             

              // Iniciar a query com a condição de estado
              $query = "SELECT * FROM tbl_devida WHERE estado IN ('0', '1')";

              // Se os filtros de data foram fornecidos, adicioná-los à consulta
              if ($data1) {
                  $query .= " AND data = '$data1'";
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
                          echo '<td align="left"><span class="label label-danger">' . $linha['valor'] . ' MT</span>';
                          
                          // Verificar se o usuário é caixa
                          if ($user_role == 'caixa') {
                              echo '<a href="#" data-toggle="modal" data-target="#modalAcessoNegado"><img src="../images/icons8-request_money.png" width="30" height="30" title="Processar pagamento"></a></span>';
                          } else {
                              echo '<a href="control_devida.php?id=' . $invoice . '&id_divida=' . $linha[0] . '"><img src="../images/icons8-request_money.png" width="30" height="30" title="Processar pagamento"></a></span>';
                          }
                          
                          echo '</td>';
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

<!-- Modal Acesso Negado -->
<div class="modal fade" id="modalAcessoNegado" tabindex="-1" role="dialog" aria-labelledby="modalAcessoNegadoLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #f39c12; color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalAcessoNegadoLabel">
          <i class="fa fa-exclamation-triangle"></i> Acesso Negado
        </h4>
      </div>
      <div class="modal-body" style="text-align: center; padding: 30px;">
        <i class="fa fa-lock" style="font-size: 60px; color: #f39c12; margin-bottom: 20px;"></i>
        <h4 style="color: #333;">Só admin pode liquidar por enquanto a dívida</h4>
        <p style="color: #666; margin-top: 15px;">Entre em contato com o administrador do sistema para processar este pagamento.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">OK, Entendi</button>
      </div>
    </div>
  </div>
</div>

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













