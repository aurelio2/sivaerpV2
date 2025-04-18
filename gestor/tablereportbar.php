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

      Relátorio Periodico 

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

                  <span class="info-box-text">Total</span>

                  <span class="info-box-number"><h2><?php echo number_format($net_total,2); ?></h2></span>

                </div>

                <!-- /.info-box-content -->

              </div>

              <!-- /.info-box -->

            </div>
       <!-- <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-blue"> <i class="fa fa-pie-chart" style="font-size:42px"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Relatorio</span>
                    <span class="info-box-number"><h2> <a href="../recibos/periodico_bar.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>" target="_blank">Bar</a> | <a href="../recibos/periodico_cozinha.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>" target="_blank">Cozinha</a></h2></span>
          
          
                  </div>
               
                </div>
               
              </div>-->

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

                    <th>usuario</th>


                    <th>Total</th>   

                    <th>Data</th> 
                    <th>Conteudo</th>




                  </tr>    



                </thead> 







                <tbody>



                  <?php

                  $select=$pdo->prepare("select * from tbl_invoice  where order_date between :fromdate AND :todate");

                  $select->bindParam(':fromdate',$_POST['date_1']);  

                  $select->bindParam(':todate',$_POST['date_2']);  



                  $select->execute();



                  while($row=$select->fetch(PDO::FETCH_OBJ)  ){



                    echo'

                    <tr>

                    <td>'.$row->invoice_id.'</td>

                    <td>'.$row->customer_name.'</td>


                    <td><span class="label label-success">'."MT " .$row->total.'</span></td>




                    <td>'.$row->order_date.'</td>
                    <td><a href="detalhe.php?id='.$row->invoice_id.'"><span class="glyphicon glyphicon-plus"></span></a></td>


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













