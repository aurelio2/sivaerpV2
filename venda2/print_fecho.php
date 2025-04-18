<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once 'cabecalho_user.php';
$ref = $_GET['id'];

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Imprimir Recibo do Fecho
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
      <div class="box box-success">
            
      
      <div class="panel panel-default">
      <div class="panel-heading">Recibo do fecho</div>
      <div class="panel-body">  
        <center>
        <br>
        <h5>Clique no icon para poder imprimir ou baixar o recibo do fecho</h5>
        
          <a href="../recibos/abertura_fecho.php?id=<?php echo $ref; ?>" target="_blank"> <img src="../images/print.png" width="60" height="60" title="Imprimir fecho"></a>
        
        </center>
        <br>
        </div>
        <a href="venda.php"><img src="../images/icons8-circled_left.png" width="40" height="40" title="Voltar para pagina de venda"> </a>

    </div>
    




</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


</script>


<?php

include_once'footer.php';

?>