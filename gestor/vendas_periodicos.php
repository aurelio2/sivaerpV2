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
      Relátorio de vendas Periodico 
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
              <h3 class="box-title">De : <?php echo $_POST['date_1']?> -- Ate : <?php echo $_POST['date_2']?></h3>
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
                    <input type="date" class="form-control pull-right"  name="date_1"  data-date-format="AAAA-MM-DD" >
                  </div>
                </div>   
                <div class="col-md-5">
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
          <br>      
          <?php

          $select=$pdo->prepare("select sum(total) as total, count(invoice_id) as invoice from tbl_invoice  where order_date between :fromdate AND :todate");
          $select->bindParam(':fromdate',$_POST['date_1']);  
          $select->bindParam(':todate',$_POST['date_2']);  
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $net_total=$row->total;
          $invoice=$row->invoice;                    

          ?>

           <?php

          $select1=$pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, (p.saleprice * t.qty) as venda, (p.purchaseprice * t.qty) as compra, (p.saleprice * t.qty) - (p.purchaseprice * t.qty) as lucrototal, SUM((p.saleprice * t.qty) - (p.purchaseprice * t.qty)) as soma
          FROM tbl_invoice_details t
          INNER JOIN tbl_product p ON p.pid = t.product_id
          where order_date between :fromdate AND :todate ");
          $select1->bindParam(':fromdate',$_POST['date_1']);  
          $select1->bindParam(':todate',$_POST['date_2']);  
          $select1->execute();
          $row=$select1->fetch(PDO::FETCH_OBJ);
          $net_lucros=$row->soma;
          //$invoice=$row->invoice;                    

          ?>

          <!-- Info boxes -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua">MT</span>
                <div class="info-box-content">

                  <span class="info-box-text">Total de Lucros</span>

                  <span class="info-box-number"><h2><?php echo number_format($net_lucros,2); ?></h2></span>

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
                  <span class="info-box-number"><h2><?php echo number_format($net_total,2); ?></h2></span>
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
               
              </div>-->
              
              <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-blue"> <i class="fa fa-pie-chart" style="font-size:42px"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Relatorio</span>
                  <span class="info-box-number"><h2> <a href="../recibos/periodico_bar.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>" target="_blank">Periodico</a> </h2></span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
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
                  <th>Data</th>
                  <th>Produto</th>  
                  <th>Preco compra</th>  
                  <th>Preco Venda</th>  
                  <th>Quantidade</th>  
                  <th>Total de venda</th>  
                  <th>Total de Lucro</th> 
                </tr>    
              </thead> 

              <tbody>

                <?php

                //$select=$pdo->prepare("select * from tbl_invoice  where order_date between :fromdate AND :todate");
                $select=$pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, (p.saleprice * t.qty) as venda, (p.purchaseprice * t.qty) as compra, (p.saleprice * t.qty) - (p.purchaseprice * t.qty) as lucrototal
            FROM tbl_invoice_details t
            INNER JOIN tbl_product p ON p.pid = t.product_id
            where order_date between :fromdate AND :todate");

                $select->bindParam(':fromdate',$_POST['date_1']);  

                $select->bindParam(':todate',$_POST['date_2']);  

                $select->execute();

                while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                   echo'
                  <tr>
                  <td>'.$row->order_date.'</td>
                  <td>'.$row->pname.'</td>
                  <td align="left"><span class="label label-success">'.$row->purchaseprice ." MT".'</span></td>
                  <td align="left"><span class="label label-success">'.$row->saleprice ." MT".'</span></td>
                  <td>'.$row->qty.'</td>
                  <td>'.$row->venda.'</td>
                  <td>'.number_format($row->lucrototal,2).'</td>
                  
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













