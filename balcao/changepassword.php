<?php
  include_once '../dbconnect.php'; 
  
  session_start();
  
  include_once 'cabecalho_user.php';
   if ($_SESSION['useremail']=="") {
      
        header('location:../index.php');
  }

  if(isset($_POST['btn_update'])){
      $oldpassword = $_POST['txtoldpass'];
      $newpassword = $_POST['txtnewpass'];
      $confirmapassword = $_POST['txtconfirmapass'];
      //pegar sessao do usuario com o seu email
      $email=$_SESSION['userid'];
      $username_db=$_POST['txt_name'];
      $user =$_POST['txt_usuario'];
      $select=$pdo->prepare("select * from tbl_user where userid='$email'");

      $select->execute();
      $row=$select->fetch(PDO::FETCH_ASSOC);

      //$useremail_db = $row['useremail'];

      $password_db = $row['password'];
     //we compare userinput and database values
      if ($oldpassword==$password_db) {
        if ($newpassword==$confirmapassword) {   

            $update=$pdo->prepare("update tbl_user set password=:pass,username=:username,useremail=:usuario where userid=:email ");
            $update->bindparam(':pass',$confirmapassword);
            $update->bindparam(':email',$email);
            $update->bindparam(':username',$username_db);
            $update->bindparam(':usuario',$user);


            if ($update->execute()) { 
              echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Senha actualizado com sucesso",
                        showConfirmButton: false,
                        timer: 1500
                      });
                    });

                    window.rad2deg(number)
              </script>';

              
            }else{

               echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "center",
                        icon: "warning",
                        title: "Senha nè´™o foi actualizado",
                        showConfirmButton: false,
                        timer: 1500
                      });
                    });
              </script>';
            }

        }else{
             echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "center",
                        icon: "warning",
                        title: "Nova senha e confirma senha nè´™o iguais",
                        showConfirmButton: true,
                        timer: 1500
                      });
                    });
              </script>';
        }

      }else{

         echo '<script type="text/javascript">
        jQuery(function validation(){
           Swal.fire({
            position: "top-end",
            icon: "warning",
            title: "Porfavor preencha todos campos para actuliazar sua senha!",
            showConfirmButton: true,
            timer: 1500
          });
        });
      </script>';
      }
  }

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Alterar Profile
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Formulario de Profile</h3>
            </div> 
            <!-- form start -->
            <form role="form" action="" method="post">
              <div class="box-body">
                <div class="col-md-4">
                <div class="form-group">
                  <label>Nome</label>
                  <input type="text" class="form-control" name="txt_name" placeholder="Nome do Completo" required="" value="<?php echo $_SESSION['username'];?>">
                </div>
                 <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="txt_usuario" placeholder="nome do usuario" required="" value="<?php  echo $_SESSION['useremail'];?>" >
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="exampleInputPassword1">Antiga Senha</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Porfavor insira antiga senha" name="txtoldpass" autocomplete="off" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Nova Senha</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Porfavor insira nova senha" name="txtnewpass" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Confirma a senha</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Porfavor confirme a nova senha" name="txtconfirmapass" autocomplete="off">
                </div>
              </div>
            </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="btn_update">Actualizar</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php 
    include_once 'footer.php'
   ?>