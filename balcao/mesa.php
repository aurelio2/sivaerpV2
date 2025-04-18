<?php 
    session_start();
    include_once '../dbconnect.php';
    error_reporting(0);
    if ($_SESSION['useremail']=="" OR $_SESSION['role']=="") {
      
        header('location:../index.php');
    }
    include_once 'cabecalho_user.php';
 ?>

  <!-- Content Wrapper. Contains page content -->
  <meta http-equiv="refresh" content="15">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <?php
        $select = $pdo->prepare("select sum(total) as total,count(invoice_id) as invoice from tbl_invoice  where order_date=CURRENT_DATE");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total_order=$row->total;
        //$net_total=$row->t;
        ?>
      <p> <b>Operador:</b> <?php echo $_SESSION['username']; ?><b style="color:green;"> Online</b></p>
      <h1>
       
        <b>Total: <?php echo number_format($total_order,2); ?> MT</b>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Nivel</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container">
        

        <?php 

         $select=$pdo->prepare("select * from tbl_mesa order by status=1 desc ");
         $select-> execute();

         while ($row=$select->fetch(PDO::FETCH_OBJ)) {
          $id=$row->cod_mesa;
         $select1=$pdo->prepare("select * from tbl_invoice where invoice_id =(select MAX(invoice_id) from tbl_invoice where mesa=$id) ");
         $select1-> execute();
         $rows=$select1->fetch(PDO::FETCH_OBJ);
         $max=$rows->invoice_id;
       ?>
      <?php        
      if ($row->status==0) {
          echo '
          <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="box box-solid box-success">
            <div class="inner">
              <h1>'.$row->cod_mesa.'</h1>
            </div>
            <div class="icon">
              <!--<img src="../images/icons8-table_filled.png">-->
            <a href="createorder.php?id='.$row->id.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-shopping_cart.png">Vender</i></a>
          </div>
          <br>
          </div>
        </div>';

      }else if($row->status==1){
        
         //$rows->invoice_id;
        echo '<div class="col-lg-3 col-xs-6">
          <!-- small box -->
         <div class="box box-solid box-danger">
            <div class="inner">
              <h4 style="color:red;">'.$row->cod_mesa.' Pedido</h4>
            <div class="icon">
              <br>                            
              <a class="small-box-footer">A pagar: '.$rows->total.' MT</a>
                 <p></p>
            </div>
            <a href="editorder.php?id='.$max.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-shopping_cart.png"> Detalhes</i></a>
          </div>
          </div>
        </div>';
      }
      
        ?>

      <?php } ?>
      
       <?php 
         $select = $pdo->prepare("SELECT COUNT(pid) as p FROM tbl_product WHERE datavalidade<=curdate()");
         $select->execute();
         $row=$select->fetch(PDO::FETCH_OBJ);

         $stock_exipirados=$row->p;
      ?>
      <div class="col-lg-6 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 style="text-align: center;"><?php echo $stock_exipirados;?></h3>

              <p style="text-align:center;">Expirados</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="prodExispirados.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!--<h3>Producto que vao expirar em 30 dias....</h3>-->
         <?php 
          $select = $pdo->prepare("SELECT count(pname) as pro FROM tbl_product WHERE datavalidade between NOW() and DATE_ADD(NOW(), INTERVAL 30 DAY) ORDER BY datavalidade");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $total_nu=$row->pro;
          ?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
             <h3 style="text-align:center;"><?php echo $total_nu; ?></h3>
              <h6 style="text-align:center;"><b>Productos que vão expirar em 30 dias</b></h6>
            </div>
            <div class="icon">
               <i class="ion-android-warning"></i>
            </div>
            <a href="validade_pro.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
         
    </section>

   
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
  <?php 
    include_once 'footer.php'
   ?>