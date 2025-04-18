<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';

include_once'header.php';


$idme=$_GET['id'];

$select=$pdo->prepare("select * from tbl_mesa where id=$idme");

$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);
$code=$row['cod_mesa'];
$desc=$row['descricao'];
$estado=$row['status'];

if(isset($_POST['btnaddproduct'])){

  $code_txt = $_POST['txtnumero'];
  $desc_txt = $_POST['txtdescription'];
  $estado_txt = $_POST['txtestado'];

  $update=$pdo->prepare("update tbl_mesa set cod_mesa=:mesa ,descricao=:mdescricao, status=:mstatus where id = $idme");

  $update->bindParam(":mesa",$code_txt);
  $update->bindParam(":mdescricao",$desc_txt);
  $update->bindParam(":mstatus",$estado_txt);

  if ($update->execute()) {
      
      echo '<script>alert("Mesa Actualizado com sucesso");
      window.location.replace("lisa_mesa.php")
      </script>';
       
  }else{
       echo '<script>alert("Error")</script>';
  }

}


?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      Registro de Mesa

      <small></small>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>

      <li class="active">Here</li>

    </ol>

  </section>



  <!-- Main content -->

  <section class="content container-fluid">

    <div class="box box-info">

      <div class="box-header with-border">

      </div>

      <!-- /.box-header -->

      <!-- form start -->

      <form action="" method="post"  name="formproduct" enctype="multipart/form-data" >

        <div class="box-body">

          <div class="col-md-6">


            <div class="form-group">

              <label >Numero da Mesa</label>

              <input type="text" class="form-control" name="txtnumero" value="<?php echo $code;  ?>" required>

            </div>       

          </div> 

          <div class="col-md-6">

            <div class="form-group">

              <label >Descricão</label>

              <textarea class="form-control" name="txtdescription" placeholder="Descricao da Mesa..."  rows="4"><?php echo $desc;?></textarea>

            </div>

            <div class="form-group">

              <input type="hidden" class="form-control" name="txtestado" value="<?php echo $estado;  ?>">

            </div>


          </div>      


        </div>

        <div class="box-footer" align="center">

         <button type="submit" class="btn btn-warning" name="btnaddproduct">Actualizar</button>
       </div>



     </form>

   </div>



 </section>

 <!-- /.content -->

</div>

<!-- /.content-wrapper -->



<?php



include_once'footer.php';



?>