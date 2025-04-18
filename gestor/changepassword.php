<?php
session_start();
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once 'header.php';

if (!isset($_SESSION['txt_email']) || empty($_SESSION['txt_email'])) {
    die("Erro: Nenhum email encontrado na sessão.");
}

$usuario_cookie = mysqli_real_escape_string($mysqli, $_SESSION['txt_email']);
$sql = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE useremail = '$usuario_cookie'");

if ($sql && mysqli_num_rows($sql) > 0) {
    $res = mysqli_fetch_array($sql);
    $nome_db1 = $res['username'];
} else {
    die("Erro: Nenhum usuário encontrado com o email fornecido.");
}

if (isset($_POST['btn_update'])) {
    $oldpassword = $_POST['txtoldpass'];
    $newpassword = $_POST['txtnewpass'];
    $confirmapassword = $_POST['txtconfirmapass'];
    $username_db = $_POST['txt_name'];
    $user = $_POST['txt_usuario'];
    $email = $usuario_cookie;

    // Buscar senha antiga
    $sql = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE useremail = '$email'");
    if ($sql && mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        $password_db = $row['password'];
    } else {
        die("Erro: Usuário não encontrado.");
    }

    // Validar senhas
    if ($oldpassword == $password_db) {
        if ($newpassword == $confirmapassword) {
            $update = $mysqli->prepare("UPDATE tbl_user SET password = ?, username = ?, useremail = ? WHERE useremail = ?");
            $update->bind_param("ssss", $confirmapassword, $username_db, $user, $email);

            if ($update->execute()) {
                //echo "<script>alert('Senha atualizada com sucesso!');</script>";
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
            } else {
                //echo "<script>alert('Erro ao atualizar a senha.');</script>";
                echo '<script type="text/javascript">
                  jQuery(function validation(){
                    Swal.fire({
                      position: "center",
                      icon: "warning",
                      title: "Senha n���o foi actualizado",
                      showConfirmButton: false,
                      timer: 1500
                    });
                  });
               </script>';
            }
        } else {
            //echo "<script>alert('Nova senha e confirmação não coincidem.');</script>";
            echo '<script type="text/javascript">
            jQuery(function validation(){
               Swal.fire({
                position: "center",
                icon: "warning",
                title: "Nova senha e confirmação não coincidem",
                showConfirmButton: true,
                timer: 1500
              });
            });
      </script>';
        }
    } else {
        echo "<script>alert('Senha antiga incorreta.');</script>";
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
                  <input type="text" class="form-control" name="txt_name" placeholder="Nome do Completo" required="" value="<?php echo $nome_db1;?>" readonly>
                </div>
                 <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="txt_usuario" placeholder="nome do usuario" required="" value="<?php  echo $usuario_cookie;?>" readonly>
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