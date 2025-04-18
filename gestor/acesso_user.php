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
      Relátorio de Acesso 
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
              <h3 class="box-title">De : <?php echo $_POST['date_1']?> -- Ate : <?php echo $_POST['date_2']?> </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-4">            
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-users"></i>
                    </div>
                  
                    <select
                    class="custom-select2 form-control"
                    data-style="btn-outline-dark"
                    data-size="5"
                    name="txt_usuario"
                    >
                    <option value="" >Escolha o usuario</option>
                    <?php
                    $cons=mysqli_query($mysqli,"SELECT * FROM tbl_user where role='Caixa'");
                    while($lista=mysqli_fetch_array($cons)){
                      echo '<option value="'.$lista['userid'].'">'.$lista['username'].'</option>';
                    }
                    ?>
                  </select>
               
                  </div>           
                </div>
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
              <div class="col-md-2">
               <div align="left">
                <input type="submit" name="btndatefilter" value="Buscar" class="btn btn-success">
              </div>
            </div>              

          </div>  
          <br>

          <?php

          $select=$pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice from tbl_invoice  where order_date between :fromdate AND :todate and user=:user");
          $select->bindParam(':fromdate',$_POST['date_1']);  
          $select->bindParam(':todate',$_POST['date_2']);  
          $select->bindParam(':user',$_POST['txt_usuario']);  
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $net_total=$row->total;
          $invoice=$row->invoice;                    

          ?>

           <?php

          $select1=$pdo->prepare("SELECT sum(subtotal) as subtotal from tbl_invoice  where order_date between :fromdate AND :todate and user=:user");
          $select1->bindParam(':fromdate',$_POST['date_1']);  
          $select1->bindParam(':todate',$_POST['date_2']);  
          $select1->bindParam(':user',$_POST['txt_usuario']);
          $select1->execute();
          $row=$select1->fetch(PDO::FETCH_OBJ);
          $net_subtotal=$row->subtotal;
          //$invoice=$row->invoice;                    

          ?>


          <?php
            $data1 = $_POST['date_1'];
            $data2 = $_POST['date_2'];
            $usuario = $_POST['txt_usuario'];
           $sql_caixa = mysqli_query($mysqli, "SELECT *
            FROM tbl_caixa
            WHERE estado = '2'
            AND data ='$data1' AND data_final='$data2' and id_user='$usuario' and closed_by='$usuario'");
           $res = mysqli_fetch_array($sql_caixa);
                              
           $data_abertura = $res['data'];
           $data_fecho = $res['data_final'];
           $valor_inicio = $res['valor_inicial'];
           $valor_final = $res['valor_final'];
          ?>
           
          <!-- Info boxes -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-files-o"></i></span>
                <div class="info-box-content">

                  <span class="info-box-text">Itens vendidos</span>

                  <span class="info-box-number"><h2><?php echo number_format($invoice); ?></h2></span>

                </div>

                <!-- /.info-box-content -->

              </div>

              <!-- /.info-box -->

            </div>

            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green">MT</i></span>
                <div class="info-box-content">
                  <span class="info-box-text">SubTotal</span>
                  <span class="info-box-number"><h2><?php echo number_format($net_subtotal,2); ?></h2></span>
                </div>
                <!-- /.info-box-content -->
              </div>

              <!-- /.info-box -->
            </div>
             <!--<div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-blue">MT</i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Total + Imposto</span>
                  <span class="info-box-number"><h2><?php echo number_format($net_total,2); ?></h2></span>
                </div>
               
              </div>
            </div>-->
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-blue"> <i class="fa fa-pie-chart" style="font-size:42px"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Relatorio de Fecho</span>
                  <span class="info-box-number"><h2> <!--<a href="../recibos/fecho_rela.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>&user=<?php echo $_POST['txt_usuario']; ?>" target="_blank">FECHO</a> </h2></span>-->
                  <a href="../recibo1/fecho.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>&user=<?php echo $_POST['txt_usuario']; ?>" target="_blank">FECHO</a> </h2></span>

                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

             <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-blue"> <i class="fa fa-pie-chart" style="font-size:42px"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">DATA INICIO: <?php echo $data_fecho;?></span>
                  <span class="info-box-text">VALOR INICIO: <?php echo $valor_inicio;?></span>
                  <span class="info-box-text">DATA FECHO: <?php echo $data_fecho;?></span>
                  <span class="info-box-text">VALOR FECHO: <?php echo $valor_final;?></span>
                  


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

             <!--<div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-green"> <i class="fa fa-bar-chart" style="font-size:45px"> <a href="../recibos/fecho_dia.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>" target="_blank"><img src="../images/pdf.ico" width="30px" title="Fecho do Dia:  <?php echo $cash_total+$cash2_total+$mpesa_total+$emola_total+$pos_total; ?>"></a></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">CASH: <b><?php echo $cash_total+$cash2_total; ?></b> MT</span>
                    <span class="info-box-text">M-PESA: <b><?php echo $mpesa_total; ?></b> MT</span>
                    <span class="info-box-text">E-MOLA: <b><?php echo $emola_total; ?></b> MT</span>
                    <span class="info-box-text">POS: <b><?php echo $pos_total; ?></b> MT</span>
                  </div>
                </div>               
              </div> -->
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
                  <th>Subtotal</th>   
                  <th>Total</th>   
                  <th>Data</th> 
                </tr>    
              </thead> 

              <tbody>



                <?php

                $select=$pdo->prepare("SELECT * from tbl_invoice  where order_date between :fromdate AND :todate and user=:user");

                $select->bindParam(':fromdate',$_POST['date_1']);  

                $select->bindParam(':todate',$_POST['date_2']);  
                $select->bindParam(':user',$_POST['txt_usuario']);  

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
                  <td>'.$row->invoice_id.'</td>
                  
                  <td>'.$row->customer_name.'</td>
                   <td>'.$row->mesa .'</td>
                  <td>'.$nome_cliente_db.'</td>
                  <td><span class="label label-success">'."MT " .$row->subtotal.'</span></td>
                  <td><span class="label label-success">'."MT " .$row->total.'</span></td>
                  <td>'.$row->order_date.'</td>

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













