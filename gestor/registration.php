<?php 
 include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';  
    include_once 'header.php';



    error_reporting(1);
     if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']); // Converte para inteiro para evitar SQL Injection
        $delete = $pdo->prepare("DELETE FROM tbl_user WHERE userid = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($delete->execute()) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Deletado com sucesso!",
                        showConfirmButton: true,
                        timer: 3000
                      });
                    });
                  </script>';
        }
    } 
      
      if (isset($_POST['btn_reg'])) {
      
      $username=$_POST['txt_name'];
      $useremail=$_POST['txt_email'];
      $userpassword=$_POST['txt_senha'];
      $userrole=$_POST['selec_option'];

      //echo $username ."-".$useremail."-".$userpassword."-".$userrole;


      //if email exist
      if(isset($_POST['txt_email'])){
      $select= $pdo->prepare("select useremail from tbl_user where useremail='$useremail'");
      
      $select->execute();

      if($select->rowCount() > 0){
        echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "warning",
                        title: "Já existe um usuario com esse email: Porfavor tenter com outro email!",
                        showConfirmButton: true,
                        timer: 3000
                      });
                    });
              </script>';
      }else{
        $insert=$pdo->prepare("insert into tbl_user(username,useremail,password,role) values(:nome,:email,:senha,:role)");

        $insert->bindParam(':nome',$username);
        $insert->bindParam(':email',$useremail);
        $insert->bindParam(':senha',$userpassword);
        $insert->bindParam(':role',$userrole);

        //$insert->execute();
        if( $insert->execute()) {
          echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Usuario registado com sucesso",
                        showConfirmButton: true,
                        timer: 2000
                      });
                    });
              </script>';
        }else{
          echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Erro ao tentar registar o usuario",
                        showConfirmButton: false,
                        timer: 2000
                      });
                    });
              </script>';
        }

      }
      }//end if txt_email

      // codigo de Editar
      
  }
 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Cadastro de usúario
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Nivel</a></li>
        <li class="active">Here</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
      <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Formulario de Cadastro</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="post">
              
              <div class="box-body">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Nome</label>
                  <input type="text" class="form-control" name="txt_name" placeholder="Nome do Completo" required="" autocomplete="off">
                </div>
                 <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="txt_email" placeholder="nome do usuario" required="" autocomplete="off">
                </div>
                <div class="form-group">
                  <label>Senha</label>
                  <input type="password" class="form-control" name="txt_senha" placeholder="Senha" required="" autocomplete="off">
                </div>
                <!-- select -->
                <div class="form-group">
                  <label>Nivel de Acesso</label>
                  <select class="form-control" name="selec_option" required="">
                    <option value="" disabled selected="">Seleciona o Nivel de Acesso</option>
                    <option value="admin">Administrador</option>
                    <option value="Caixa">Caixa</option>
                    <!--<option>Balconista</option>-->     
                  </select>
                </div>
                <button type="submit" class="btn btn-info" name="btn_reg">Registar</button>
              </div>

              <div class="col-md-8">
                <a href="../recibos/usuario.php" class="btn btn-info" role="button"><span class="glyphicon glyphicon-print" style="color:#ffffff" data-toggle="tooltip" title="Imprimir Produto" align="right"></span>Imprimir</a>
                <br>
                <br>
                <table class="table table-striped" id="tabela_registation">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nome</th>
                      <th>Usuario</th>
                      <th>Senha</th>
                      <th>Nivel de Acesso</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $select=$pdo->prepare("select * from tbl_user order by userid desc");

                    $select-> execute();

                    while ($row=$select->fetch(PDO::FETCH_OBJ)) {
                      echo '
                        <tr>
                        <td>'.$row->userid.'</td>
                        <td>'.$row->username.'</td>
                        <td>'.$row->useremail.'</td>
                        <td>**************</td>
                        <td>'.$row->role.'</td>
                        <td>
                        <a href="registration.php?id='.$row->userid.'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash" title="Delete"></span></a>
                        </td>
                        </tr>

                      ';
                    }
                     ?>
                  </tbody>
                </table>
              </div>
  
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
              </div>
            </form>
          </div>
        

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!--Call this single function-->
  <script type="text/javascript">  
      $(document).ready( function () {
      $('#tabela_registation').DataTable();
    } );
  </script>

  <?php 
    include_once 'footer.php'
   ?>