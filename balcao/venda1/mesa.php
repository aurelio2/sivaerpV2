<?php 
    session_start();
    include_once '../../dbconnect.php';
    error_reporting(0);
    if ($_SESSION['useremail']=="" OR $_SESSION['role']=="Admin") {
      
        header('location:../index.php');
    }
    include_once '../cabecalho_user.php';
 ?>

  <!-- Content Wrapper. Contains page content -->
  <meta http-equiv="refresh" content="20">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Cadeiras
        
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Nivel</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      

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
          echo '<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          
            <div class="inner">
              <h4>'.$row->cod_mesa.' Livre</h4>
            </div>
            <div class="icon">
              <!--<img src="../images/icons8-table_filled.png">-->
                
         
            <a href="venda1/createorder.php?id='.$row->id.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-chair.png"> Abrir</i></a>
          </div>
        </div>';

      }else if($row->status==1){
        
         //$rows->invoice_id;
        echo '<div class="col-lg-3 col-xs-4">
          <!-- small box -->
         
            <div class="inner">
              <h4 style="color:red;">'.$row->cod_mesa.' Ocupado</h4>
            <div class="icon">
              <!--<img src="../images/icons8-table.png">-->
              <a href="pagar.php?id='.$id.'&op=det&max='.$max.'" class="small-box-footer"><img src="../images/icons8-request_money.png"></a>
                 
            </div>
            <a href="editorder.php?id='.$max.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-chair.png"> Detalhes</i></a>
          </div>
        </div>';
      }
      
        ?>

      <?php } ?>
    

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
  <?php 
    include_once '../venda1/footer.php'
   ?>