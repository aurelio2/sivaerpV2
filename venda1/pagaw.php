<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once'cabecalho_user.php';   


 $ref = $_GET['id'];


if(isset($_POST['btnupdateorder'])){

    $txt_total=$_POST['txttotal'];
    $mesa_text=$_POST['txtmesa'];
    $txt_mesas=$_POST['txt_mesa'];
    $txt_customer_name=$_POST['txtcustomer'];
    $txt_order_date=date('Y-m-d',strtotime($_POST['orderdate']));


    //disc,pago,troco,forma de pagamento
    $disconto_text=$_POST['txtdiscount'];
    $pagar_text=$_POST['txtpaid'];
    $troco_text=$_POST['txtdue'];
    //$forma_text=$_POST['rb'];
    //$venda=$_POST['idvenda'];

    $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$txt_mesas");
    $insert_mesa->bindParam(":estado",$mesa_text);
    $insert_mesa->execute();
 
    
    

   }   
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Imprimir Recibo
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
      <div class="box box-success">
            
      
      <div class="panel panel-default">
      <div class="panel-heading">Recibo da venda <?php echo $ref; ?></div>
      <div class="panel-body">  
        <center>
        <br>
        <h5>Clique na impressora para imprimir recibo</h5>
        <?php
       
        echo "<br>";
        echo '<a href="../recibo1/pdf.php?id='.$ref.'" target="_blank"><img src="../images/print.png" width="60" height="60" title="Imprimir rebido da venda '.$ref.'"></a>';
        //echo '<a href="../pdf/recibo.php?id='.$result['invoice_id'].'" target="_blank"><img src="../images/print.png" width="60" height="60" title="Imprimir rebido da venda 0'.$result['invoice_id'].'"></a>';
        
        ?>
          <!--<a href="../pdf/recibo.php?id='.$result['id'].'" target="_blank"><img src="../images/print.png" width="60" height="60" title="Imprimir rebido da venda 0'.$result['id'].'"></a>-->
          
        
        </center>
        <br>
        </div>
        <a href="mesa.php"><img src="../images/icons8-circled_left.png" width="40" height="40" title="Voltar para pagina de venda"> </a>

    </div>
    




</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


</script>


<?php

include_once'footer.php';

?>