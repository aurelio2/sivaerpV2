
<?php
include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';

include_once'header.php';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
        <input type="date" class="form-control pull-right" id="datepicker1" name="date_1"  data-date-format="AAAA-MM-DD" >
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
                  
                
    $select=$pdo->prepare("select sum(total) as total,count(id) as invoice from tbl_saidas  where data=:fromdate ");
      $select->bindParam(':fromdate',$_POST['date_1']);  
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
             <span class="info-box-icon bg-aqua"><img src="../images/icons8-barbershop_filled.png"></span>

            <div class="info-box-content">
              <span class="info-box-text">Total cortes</span>
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
        <!-- /.col -->
      </div>
      <!-- /.row -->                                                                  
       <br>

        <table id="salesreporttable" class="table table-striped">
        <thead>
        <tr>
        <th>Codigo</th>
         <th>User</th>
         <th>View</th>
            <th>Total</th>  
            <th>Data</th> 
            <th>Hora</th>
                
        </tr>    
            
        </thead>              
        <tbody>
        
            <?php
      $select=$pdo->prepare("select * from tbl_saidas  where data=:fromdate");
      $select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
            
  while($row=$select->fetch(PDO::FETCH_OBJ)  ){
    
    echo'
    <tr>
    <td>00'.$row->id.'</td>
    <td>'.$row->user.'</td>
     <td align="center"><a href="detalhe.php?id='.$row->id.'"><span class="glyphicon glyphicon-plus"></span></a></td> 
  <td><span class="label label-success">'.number_format($row->total,2 )." MT".'</span></td>
    <td>'.$row->data.'</td>
     <td>'.$row->hora.'</td>
   
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






