<?php 
  // Sistema de licença
  function verificarLicenca() {
    $arquivo_licenca = 'licenca.txt'; // Arquivo onde a licença está armazenada
    
    // Verificar se o arquivo de licença existe
    if (!file_exists($arquivo_licenca)) {
      return false;
    }
    
    // Ler a licença do arquivo
    $licenca = trim(file_get_contents($arquivo_licenca));
    
    // Verificar se a licença não está vazia
    if (empty($licenca)) {
      return false;
    }
    
    // Decodificar a licença (formato: data_expiracao|chave_hash)
    $partes = explode('|', $licenca);
    
    if (count($partes) != 2) {
      return false;
    }
    
    $data_expiracao = $partes[0];
    $hash = $partes[1];
    
    // Verificar se a data de expiração é válida
    $hoje = date('Y-m-d');
    if ($hoje > $data_expiracao) {
      return false; // Licença expirada
    }
    
    // Verificar se o hash é válido (chave secreta = 'maphezu_sistema')
    $chave_secreta = 'maphezu_sistema';
    $hash_esperado = md5($data_expiracao . $chave_secreta);
    
    return ($hash === $hash_esperado);
  }
  
  // Verificar a licença antes de carregar o sistema
  if (!verificarLicenca()) {
    // Redirecionar para a página de licença expirada
    header('Location: licenca_expirada.php');
    exit();
  }
  
  // Se chegou aqui, a licença é válida - continua para a tela de login normalmente
  
  include_once 'conexao.php';
  include_once 'config_imagem.php';

  $sql1 = mysqli_query($mysqli,"SELECT * FROM empresa");
  $res1 = mysqli_fetch_array($sql1);
  $nome_db = $res1['nome'];
  $nuit_db = $res1['nuit'];
  $contacto_db = $res1['contacto'];

 ?>
 
 <!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="bower_components/sweetalert/sweetalert2.all.min.js"></script>
<script src="dist/push.min.js"></script>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIVA| Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="shortcut icon" href="siva.ico">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <?php 
        $estado = 1;
        
        //se for 1 activo caso 2 inativo
    
    if($estado==1){
 
 ?>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <!--<h1 align="center" style="color: greenyellow;">SiVA ERP</h1>-->
    <!--<h1 align="center" ><?php echo $nome_db; ?></h1>-->
    <p align="center"><img src="<?php echo $nome_imagem_logo; ?>" width="150px"></p>
    <p align="center" style="font-size: 15px;">Welcome to SiVA</p>
 

    <form action="login.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Usuario" name="txt_email" required autocomplete="off">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Senha" name="txt_senha" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      
      <div class="row">
        <div class="col-xs-8">    
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="btn_login">Acessar</button>
        </div>
        <!-- /.col -->
      </div>
      <br>
       <p align="center"><img src="<?php echo $nome_imagem_logo; ?>" width="200px"></p>
      <p align="center">Powerd by <b>SIVA SOFTWARE</b></p>
    </form>
    <!-- /.social-auth-links -->
    
  </div>
  <!-- /.login-box-body -->
  
</div>
<!-- /.login-box -->
<?php }else{?>
 <h1>OUT OF CLOUD!!</h1>
<?php }?>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
