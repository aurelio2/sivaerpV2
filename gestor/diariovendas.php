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
      Relatorio diario de vendas <img src="../images/icons8-tear_off_calendar.png">
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
             
             
             $select1=$pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, (p.saleprice * t.qty) as venda, (p.purchaseprice * t.qty) as compra, (p.saleprice * t.qty) - (p.purchaseprice * t.qty) as lucrototal, SUM((p.saleprice * t.qty) - (p.purchaseprice * t.qty)) as soma
            FROM tbl_invoice_details t
            INNER JOIN tbl_product p ON p.pid = t.product_id
            WHERE order_date =:fromdate ");
             $select1->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
             
             $select1->execute();
             
             $row=$select1->fetch(PDO::FETCH_OBJ);
             
             $net_total_lucros=$row->soma;
             
             //$stotal=$row->stotal;
             
             //$invoice=$row->invoice;                    
             
             ?>
             <!-- Info boxes -->
             <div class="row">
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua">MT</span>

                  <div class="info-box-content">
                    <span class="info-box-text">Total de Lucro</span>
                    <span class="info-box-number"><h2><?php echo number_format($net_total_lucros,2); ?></h2></span>
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
                    <span class="info-box-number"><h2> <a href="../recibos/diario_vendas.php?dia=<?php echo $_POST['date_1']?>" target="_blank">Diario</a> 
          
          
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
                //$select=$pdo->prepare("select * from tbl_invoice  where order_date=:fromdate order by invoice_id DESC");
                $select=$pdo->prepare("SELECT p.pid, t.product_id, p.pname, p.purchaseprice, p.saleprice, t.qty, t.order_date, (p.saleprice * t.qty) as venda, (p.purchaseprice * t.qty) as compra, (p.saleprice * t.qty) - (p.purchaseprice * t.qty) as lucrototal
                FROM tbl_invoice_details t
                INNER JOIN tbl_product p ON p.pid = t.product_id
                WHERE order_date =:fromdate");
                $select->bindParam(':fromdate',$_POST['date_1']);  
             //$select->bindParam(':todate',$_POST['date_2']);  
                
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






