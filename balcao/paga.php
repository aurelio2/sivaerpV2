
<?php
include_once'../dbconnect.php';
error_reporting(0);
session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="admin"){


  header('location:../index.php');
}

include_once'headeruser.php';

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Consultar Pedidos ainda a pagar
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
              
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="box-body">                  
              <div class="row">            
                                        
             </div>  

             <br>

             <?php


             $select=$pdo->prepare("select sum(total) as total,count(invoice_id) as invoice from tbl_invoice  where paid=''");
             //$select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  

             $select->execute();

             $row=$select->fetch(PDO::FETCH_OBJ);

             $net_total=$row->total;

  //$stotal=$row->stotal;

             $invoice=$row->invoice;                    

             ?>
             <!-- Info boxes -->
             <div class="row">
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-files-o"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Total Pedidos</span>
                    <span class="info-box-number"><h2><?php echo number_format($invoice); ?></h2></span>
					<a href="">Bar </a> | <a href="">Cozinha </a>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->                                                                  
            <br>

            <table id="salesreporttable" class="table table-striped">
              <thead>
                <tr>
                  <th>Mesa</th>
                  <th>Total</th>  
               
                  <th>Pagar</th>
                </tr>    

              </thead>              
              <tbody>

                <?php
                $select=$pdo->prepare("select * from tbl_invoice where paid=''");
                //$select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  

                $select->execute();

                while($row=$select->fetch(PDO::FETCH_OBJ)  ){

                  echo'
                  <tr>
                  <td>'.$row->mesa.'</td>
                  <td><span class="label label-success">'.$row->total ." MT".'</span></td>
                  
                  <td><a href="pagar.php?id='.$row->invoice_id.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-banknote.png">Pagar</i></a></td>


                  ';

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

  include_once'footer.php';

  ?>






