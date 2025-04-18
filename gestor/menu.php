<?php 
include_once '../dbconnect.php';
session_start(); 

if($_SESSION['useremail']=="" OR $_SESSION['role']=="Barb-men"){
    
    
    header('location:../index.php');
}
include_once 'header.php';



?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h4 align="center">
       Bem vindo ão Painel do Bar e Outros Serviços
    </h4>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i> Level</a></li>
      <li class="active">Here</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid" >
      
      <?php 
          $select = $pdo->prepare("select count(pname) as pro from tbl_product");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $total_produto=$row->pro;
          ?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h5>Produtos</h5>
            </div>
            <div class="icon">
              <?php echo $total_produto; ?>
            </div>
            <a href="addproduct.php" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-beer_bottle.png"> Produto</i></a>
            <a href="productlist.php" class="small-box-footer">Lista de Produto</a>
            <a href="actualiza_stock.php" class="small-box-footer">Actualizar Stock</a>
          </div>
        </div>
        
          <?php 
          $select = $pdo->prepare("select count(category) as cate from tbl_category");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $total_category=$row->cate;
          ?>
       
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-blue">
            <div class="inner">
              <h5>Categoria</h5>
            </div>
            <div class="icon">
              <?php echo $total_category;?> 
            </div>

            <a href="category.php" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-aliexpress.png"> Categoria</i></a>
            <a href="category.php" class="small-box-footer">Lista de categoria</a>
          </div>
        </div>

         <?php 
          $select = $pdo->prepare("select count(cod_mesa) as mesa from tbl_mesa");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $total_mesa=$row->mesa;
          ?>
        <!--Mesa-->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-blue">
            <div class="inner">
              <h5>Cadeiras</h5>
            </div>
            <div class="icon">
               <?php echo $total_mesa;?> 
            </div>

            <a href="mesa.php" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-chair.png"> Cadeiras</i></a>
            <a href="lisa_mesa.php" class="small-box-footer">Lista de Cadeiras</a>
          </div>
        </div>
        
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

  <!--Modal para adicionar preco ao produto-->
  
      <?php 
      include_once 'footer.php';
      ?>