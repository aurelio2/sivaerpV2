<?php 
include_once '../dbconnect.php';
session_start();
include_once 'header.php';

$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_fornecedor where id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

$ideq=$row['idequipamento'];
$select1=$pdo->prepare("select * from tbl_equipamento where id=$ideq");
$select1->execute();
$equip=$select1->fetch(PDO::FETCH_ASSOC);

$id_db=$row['id'];
$name_db=$row['nome'];
$bi=$row['bi'];
$nuit=$row['nuit'];
$contact=$row['contact'];
$equip=$row['idequipamento'];

if (isset($_POST['btnupdate'])) {

  $nome = $_POST['txtnome'];
  $contact = $_POST['telefone'];
  $bi = $_POST['bi'];
  $nuit = $_POST['nuit'];
  $idequipamento = $_POST['txtselect_option'];



  $update=$pdo->prepare("UPDATE tbl_fornecedor set idequipamento=:idequipamento,nome=:nome, bi=:bi,nuit=:nuit,contact=:contact where id=$id_db");

  $update->bindParam(":idequipamento",$idequipamento);
  $update->bindParam(":nome",$nome);
  $update->bindParam(":bi",$bi);
  $update->bindParam(":nuit",$nuit);

  $update->bindParam(":contact",$contact);

  if($update->execute()){

    echo '<script>
          alert("Dados Alterado com sucesso!");
          window.location.replace("reg_fornecedor.php");
    </script>';

  }

}
$select=$pdo->prepare("select * from tbl_fornecedor where id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

$id_db=$row['id'];
$name_db=$row['nome'];
$bi=$row['bi'];
$nuit=$row['nuit'];
$contact=$row['contact'];
$equi=$row['idequipamento'];

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Editar Equipamento
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
            <h3 class="box-title">Formulario de Actualização de equipamento</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form action="" method="post" name="formproduto" enctype="multipart/form-data">
            <div class="box-body">

             <div class="col-md-12">

              <div class="form-group">
                <label >Nome do fornecedor</label>
                <input type="text" class="form-control" name="txtnome" value="<?php echo $name_db;?>" placeholder="Enter Name" required>
              </div>
              
              <div class="form-group">
                <label>Equipamento escolhido anterior</label>
                <select class="form-control" name="" required>
                  <option value="" disabled selected>Select Equipamento</option>
                  <?php
                  $select = $pdo->prepare("select * from tbl_equipamento where id=$equi");          
                  $select->execute();
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){

                    $ideq=$row['idequipamento'];
                    $select1=$pdo->prepare("select * from tbl_equipamento where id=$ideq");
                    $select1->execute();
                    $equip=$select1->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    ?>    
                    <option <?php if($equip['modelo']==$equip) {?>

                      selected="selected"
                      <?php } ?> disabled>

                      <?php echo $row['modelo'];?></option>

                      <?php   

                    }                  
                    ?>    

                  </select>
                  <input type="hidden" name="idequipa" value="<?php echo $equi; ?>">
                </div>                
                <div class="form-group">
                <label>Equipamento novo</label>
                <select class="form-control" name="txtselect_option" required>
                  <option value="">Select Equipamento novo</option>
                    <?php 
                            $buscar=$pdo->prepare("select * from tbl_equipamento order by modelo");
                            $buscar->execute();

                            $resultado = $buscar->fetchAll();
                            foreach ($resultado as $key => $value) {
                              echo '
                              <option value="'.$value["id"].'">'.$value["modelo"].'</option>
                            ';
                            }

                            ?>
                  </select>
                </div>


                <div class="form-group">
                  <label >B.I</label>
                  <input type="text"  class="form-control" value="<?php echo $bi;?>" name="bi" placeholder="Enter..." required>
                </div>
                
                <div class="form-group">
                  <label >Nuit</label>
                  <input type="text" class="form-control" value="<?php echo $nuit; ?>" name="nuit" placeholder="Enter..." required>
                </div> 

                <div class="form-group">
                  <label >Contacto</label>
                  <input type="text" class="form-control" value="<?php echo $contact; ?>" name="telefone" placeholder="Enter..." required>
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