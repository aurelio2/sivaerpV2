<?php 
  include_once'../conexao.php';
  $sql3 = mysqli_query($mysqli,"SELECT * FROM empresa");
  $res3 = mysqli_fetch_array($sql3);
  $nome_db = $res3['nome'];

 ?>
<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Adminstrador  | Darshboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- jQuery 3 -->
  <script src="../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.min.js"></script>
  <script src="../dist/push.min.js"></script>
  <script src="../Chart.js-2.8.0/dist/Chart.min.js"></script>

  <script src="../bower_components/sweetalert/sweetalert2.all.min.js"></script> 

  <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <link rel="shortcut icon" href="../siva.ico">

  
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
  <!-- iCheck 1.0.1 -->
  <script src="../plugins/iCheck/icheck.min.js"></script>
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../plugins/iCheck/all.css">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- date-range-picker -->
  <script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>

<!--Query-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!--Push js-->
  <script src="../jsalert/push.min.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Main Header -->
  <header class="main-header">
    <!-- Logo -->
    <a href="darshboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Si</b>VA</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo $nome_db; ?></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">

            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <p class="fa fa-user-circle-o"></p>
              <span class="hidden-xs">Olá  <b><?php echo $Nome;?></b></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <p>
                  <?php echo $Nome;?>
                  <small>Powerd by SiVA Software</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="changepassword" class="btn btn-default btn-flat">Alterar Profile</a>
                </div>
                <div class="pull-right">
                  <a href="../logout" class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Home</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="darshboard"><img src="../images/icons8-speed1.png"></i> <span>Dashboard</span></a></li>
        </li>
        <!--Servicos-->
        <li class="treeview">
        <a href="#"><i class="fa fa-edit" style="font-size:20px;color:white"></i>
            <span>Registar</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li class="hidden"><a href="addproduct"><i class="fa fa-circle-o-notch" style="font-size:16px;color:white"></i><span>Produto</span></a></li>
            <li><a href="category"><i class="fa fa-circle-o-notch" style="font-size:16px;color:white"></i><span>Categoria</span></a></li>
            <li><a href="expense_categories.php"><i class="fa fa-circle-o-notch" style="font-size:16px;color:white"></i><span>Categoria Despesa</span></a></li>
            <li><a href="expenses.php"><i class="fa fa-circle-o-notch" style="font-size:16px;color:white"></i><span>Despesas</span></a></li>
            <li><a href="registration"><i class="fa fa-user" style="font-size:20px;color:white"></i> <span>Usúario</span></a></li>
            <li><a href="forncedor"><i class="fa fa-users" style="font-size:20px;color:white"></i> <span>Fornecedores</span></a></li>
            <li><a href="mesa"><i class="fa fa-table" style="font-size:20px;color:white"></i> <span>Mesa</span></a></li>
            <li><a href="armazem"><i class="fa fa-table" style="font-size:20px;color:white"></i><span>Armazem</span> </a></li>
          </ul>
          <li class="treeview">
          <a href="#" style="color: white;">
            <i class="fa fa-bar-chart" style="font-size:20px; color: white;"></i>
            <span style="color: white;">Relatórios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right" style="color: white;"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="color: white;">
            <li><a href="diariobar" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Movimentos Diários</span></a></li>
            <li><a href="tablereport" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Movimentos Periódicos</span></a></li>
            <li><a href="transfer_history" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Histórico de Transferência</span></a></li>
            <li><a href="entradas" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Entradas</span></a></li>
            <li><a href="relatorio_despesas.php" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Relatório de Despesas</span></a></li>
            <li><a href="diariovendas" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Relatório de Vendas Diários</span></a></li>
            <li><a href="periodicovendas" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Relatório de Vendas Periódicos</span></a></li>
            <li><a href="relatorio_devedor" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Relatório de Devedores</span></a></li>
            <li><a href="rela_acesso" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Acesso</span></a></li>
            <li><a href="acesso_user.php" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Acesso por Usuário</span></a></li>
            <li><a href="ajust_list.php" style="color: white;"><i class="fa fa-calendar" style="color: white;"></i> <span>Relatório de Quebras</span></a></li>
          </ul>
        </li>

         <li class="treeview">
          <a href="#"><i class="fa fa-info-circle" style="font-size:20px"></i> 
            <span>Actualizar</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="actualiza_stock.php"><i class="fa fa-question-circle-o" style="font-size:20px"></i><span>Actualizar Stock</span></a></li>
            <li><a href="productlist.php"><i class="fa fa-question-circle-o" style="font-size:20px"></i><span>Listagem de Produtos</span></a></li>

          </ul>
        </li>

        <?php 
        // Verificar se o usuário tem permissões de desenvolvedor
        if(isset($_SESSION['txt_email'])) {
            $sql_user_check = mysqli_query($mysqli, "SELECT role FROM tbl_user WHERE useremail = '".$_SESSION['txt_email']."'");
            if($sql_user_check) {
                $user_check = mysqli_fetch_array($sql_user_check);
                if($user_check['role'] == 'dev') {
        ?>
        <li class="treeview">
          <a href="#"><i class="fa fa-cogs" style="font-size:20px;color:#ff6b6b"></i> 
            <span style="color:#ff6b6b">Desenvolvedor</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="dev_panel.php"><i class="fa fa-wrench" style="font-size:16px;color:#ff6b6b"></i><span style="color:#ff6b6b">Painel Dev</span></a></li>
          </ul>
        </li>
        <?php 
                }
            }
        }
        ?>
        
      </ul>

      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>