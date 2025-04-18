<?php 
    session_start();
    include_once '../dbconnect.php';
    error_reporting(0);
    if ($_SESSION['useremail']=="" OR $_SESSION['role']=="admin") {
      
        header('location:../index.php');
    }
    include_once 'cabecalho_user.php';
 ?>

  <!-- Content Wrapper. Contains page content -->
  <meta http-equiv="refresh" content="15">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
    </section>

    <!-- Main content -->
    <section class="content container">
      <?php 

      include('../conexao.php');
      $data=date('Y-m-d');
      $hora=date('H:i:s');
      
        if(isset($_POST['vende'])){
        $insere=$mysqli->prepare("INSERT INTO diario  (diario.idproduto, diario.stock_inicial) 
            SELECT tbl_product.pid, tbl_product.pstock FROM tbl_product");
            if(!$insere->execute()){
              echo "";
            }else{
            $update=mysqli_query($mysqli,"UPDATE diario SET data='$data' WHERE data = ''");
            echo("<script>window.location.href='index.php?link=mesa.php';</script>");
          }
        }
       ?>
        
         <!-- /.sidebar-menu -->
      <form action="" method="post">
        <div class="">
        <!--<input type="hidden" name="mesa" value="<?php echo $_GET['id']?>">-->
        <p style="color:red;">Porfvor click o botão abrir venda para comecar...</p>
        <button class="btn btn-primary" name="vende" type="submit">ABRIR VENDA</button>
        </div>
      <hr>
    </form>
     
    </section>

   
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
  <?php 
    include_once 'footer.php'
   ?>