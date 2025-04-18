<?php

include_once '../dbconnect.php'; 
session_start();  

include_once 'cabecalho_user.php';


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Imprimir Recibo
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
      <div class="panel-heading">Recibo da venda</div>
      <div class="panel-body">  
        <center>
        <br>
        <h5>Clique no icon para poder imprimir ou baixar o recibo da venda</h5>
        <?php
        $select=$pdo->prepare("SELECT * FROM tbl_saidas ORDER by id DESC LIMIT 1");
                
        $select->execute();
        $result=$select->fetch();
        ?>
          <!--<a href="../pdf/recibo.php?id='.$result['id'].'" target="_blank"><img src="../images/print.png" width="60" height="60" title="Imprimir rebido da venda 0'.$result['id'].'"></a>-->
          <a href="my.bluetoothprint.scheme://http://qani.co.mz/venda/imprime.php?id=<?php echo $result['id'];?>" target="_self" > <img src="../images/print.png" width="60" height="60" title="Imprimir rebido"></a>
        
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