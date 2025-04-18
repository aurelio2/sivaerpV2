<?php 
include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
    include_once 'header.php';

    $pid=$_GET['id'];
    $select=$pdo->prepare("select * from fornecedor where id=$pid");
    $select->execute();
    $row=$select->fetch(PDO::FETCH_ASSOC);

    $id_db=$row['id'];

    //echo "---------------------".$id_db;
    $modelo_db=$row['modelo'];
    $descricao_db=$row['descricao'];
    
    
    if (isset($_POST['btnupdate'])) {

        $modelo = $_POST['txt_modelo'];
        $desc = $_POST['txt_descricao'];

       $update=$pdo->prepare("UPDATE tbl_equipamento set modelo=:modelo,descricao=:descricao where id=$id_db");

       $update->bindParam(":modelo",$modelo);
       $update->bindParam(":descricao",$desc);

       if($update->execute()){

          echo '<script>
            alert("Actualizado com sucesso!");

            window.location.replace("reg_equipament.php");
          </script>';
          
       }


          
       
    }
    
 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar produto
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
         <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Formulario de Actualização de produto</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="post" name="formproduto" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" class="form-control" name="txt_modelo" value="<?php echo $modelo_db; ?>">
                    </div>

                    <div class="form-group">
                    <label>Descricao</label>
                    <input type="text" class="form-control" name="txt_descricao" value="<?php echo $descricao_db; ?>" >
                    </div>
                </div>             
                  
                 
            </div>
            <div class="box-footer">    
                <button type="submit" class="btn btn-success" name="btnupdate">Actualizar</button>
              </div>
              
          </form>

          </div>
          
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php 
    include_once 'footer.php'
   ?>