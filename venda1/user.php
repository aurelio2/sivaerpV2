<?php 
   include_once '../dbconnect.php';

    session_start();
    include_once 'cabecalho_user.php';
    if ($_SESSION['useremail']=="" OR $_SESSION['role']=="admin" ) {
      
        header('location:../index.php');
    } 
 ?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Painel de Cortes
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">

    </section>
    <!-- /.content -->
  </div>
  <?php 
    include_once 'footer.php'
   ?>
  <!-- /.content-wrapper -->
  