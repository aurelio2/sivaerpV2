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

  <title>SiVA| Operador </title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">  
  <!-- jQuery 3 -->
  <link rel="shortcut icon" href="../siva.ico">

  <script src="../bower_components/jquery/dist/jquery.min.js"></script>
  <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../dist/js/adminlte.min.js"></script>
  <script src="../bower_components/sweetalert/sweetalert.js"></script>
  <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../Chart.js-2.8.0/dist/Chart.min.js"></script>
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">

<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- DataTables -->
<link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">   
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="../plugins/iCheck/all.css">  
<script src="../plugins/iCheck/icheck.min.js"></script>                         
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>  
<link rel="shortcut icon" href="../siva.ico">     
</head>
<!-- <body class="hold-transition skin-blue sidebar-mini"> -->
<body class="hold-transition skin-blue sidebar-mini-expand-feature sidebar-collapse">
  <div class="wrapper">
    <header class="main-header">
      <a href="mesa.php" class="logo">
       <span class="logo-mini"><b>SI</b>VA</span>
        <span class="logo-lg"><b><?php echo $nome_db; ?></span>
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <p class="fa fa-user-circle-o"></p>

                <span class="hidden-xs"><?php echo $Nome;?></span>

              </a>

              <ul class="dropdown-menu">
                <li class="user-header">
                  <p>
                    <?php echo $Nome;?>
                  </p>

                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="changepassword.php" class="btn btn-default btn-flat">Alterar Senha</a>
                  </div>
                  <div class="pull-right">
                    <a href="../logout.php" class="btn btn-default btn-flat">Sair</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>

        </div>

      </nav>

    </header>

    <!-- Left side column. contains the logo and sidebar -->

    <aside class="main-sidebar">



      <!-- sidebar: style can be found in sidebar.less -->

      <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">
          <li><a href="abrir_caixa.php"><img src="../images/icons8-cash_register_45.png"></i> <span>Caixa</span></a></li> 

          <li><a href="mesa.php"><img src="../images/restaurant.png" width="40px"></i> <span>Mesa</span></a></li> 
          <li><a href="ajust_list.php"><img src="../images/warning.png" width="40px" title="Registar Quebras"></i> <span>Ajuste</span></a></li> 
          <!-- <li><a href="balcao.php?id=55"><img src="../images/icons8-add.png" width="30px"></i> <span>Balcão</span></a></li> 
            -->
            <li>
            <form action="balcao.php" method="POST" style="display: inline;">
                <input type="hidden" name="txtnome" value="55"> 
                <input type="hidden" name="id" value="55">
                <button type="submit" style="background: none; border: none; cursor: pointer;">
                    <img src="../images/icons8-add.png" width="30px">
                    <span style="color: white;">Balcão</span>
                </button>
            </form>
        </li>

          <li class="treeview">

          <a href="#"><i class="fa fa-bar-chart" style="font-size:18px"></i> 
            <span>Relatorio</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
          <li><a href="diario.php"><i class="fa fa-calendar"></i> <span>Diario</span></a>
          <li><a href="acesso_rela.php"><i class="fa fa-user"></i> <span>Acesso</span></a>
        </li>
      </ul>

    </li>
    
      </ul>

        <!-- /.sidebar-menu -->

      </section>

      <!-- /.sidebar -->

    </aside>

