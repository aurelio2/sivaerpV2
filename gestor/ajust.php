<?php

      include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';

include_once'header.php';    

$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_entradas where idproduto=$id");
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);
$id_db=$row['idproduto'];
$pro_nome=$row['produto'];
$pro_stock=$row['quantidade'];

  $select1=$pdo->prepare("select * from tbl_product where pid=$id");
  $select1->execute();
  $row1=$select1->fetch(PDO::FETCH_ASSOC);
  $stoque=$row1['pstock'];


//$cli_saida=
if(isset($_POST['btnupdate'])){
  $ajustado=$_POST['qtdAjust'];
  $data=date('Y-m-d',strtotime($_POST['date_ajuste']));

  $update=$pdo->prepare("UPDATE tbl_entradas set ajust=:ajustado,data_ajust=:data where idproduto=".$id);

    $update->bindParam(':ajustado',$ajustado);
    $update->bindParam(':data',$data);

    $ajustar=($ajustado)+$stoque;
    $update1=$pdo->prepare("UPDATE tbl_product set pstock='$ajustar' where pid='".$id_db."'");

    $update1->execute();
  if ($update->execute()) {
    echo '
    <script>alert("Obrigado, Stock Ajustado com sucesso!");
      window.location="actualiza_stock.php";
    </script>
    ';
  }else{
    echo '<script>alert("Error".$e->getmessage());</script>';
  } 

}

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Ajustar Entrada
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
      <li class="active">Here</li>
    </ol>
  </section>

  <!-- Main content -->

  <section class="content container-fluid">
    <div class="box box-warning">
      <form action="" method="post"  name="formproduct" enctype="multipart/form-data" >
       <div class="box-body">
        <div class="form-group">
          <label >Artigo/Produto</label>
          <input type="text" class="form-control" name="txtpname" value="<?php echo $pro_nome; ?>" readonly>

        </div>
        <div class="form-group">
          <label>Codigo</label>
          <input type="text" class="form-control" name="txtpcide" value="<?php echo $id_db; ?>" readonly>
        </div>
        <div class="form-group">
          <label >Entrada</label>
          <input type="text"  class="form-control" value="<?php echo $pro_stock; ?>" name="txtentrada" readonly>
        </div>
        <div class="form-group">
          <label >Total</label>
          <input type="text"  class="form-control" value="<?php echo $stoque; ?>" name="txtentrada" readonly>
        </div>
        <div class="form-group">
          <label >Ajuste +/-</label>
          <input type="number" class="form-control" value="" name="qtdAjust" placeholder="Quantidade a justar..." required>
        </div>
        <div class="form-group">
          <input type="hidden" class="form-control pull-right" id="datepicker" name="date_ajuste" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
          <input type="hidden" class="form-control pull-right" id="datepicker" name="txt_teste" value="entrada">
        </div>  
        <b>NB:</b><p style="color: red;">Para ajustar o artigo usa o sinal (-) para decrementar o stock, e usa (+) para incrementrar o artigo.</p>
      </div> 
      <div class="box-footer">
       <button type="submit" class="btn btn-warning" name="btnupdate">Actualizar</button>          
     </div>

   </form>

 </div>

</section>
</div>

<script type="text/javascript">
  $("#piroduto").change(mostrarValores);

  function mostrarValores(){

    dadosProduto=document.getElementById('piroduto').value.split('_');
              //$("#idpro").val(dadosProduto[0]);
              $("#iva").val(dadosProduto[1]);
              //$("#preco").val(dadosProduto[2]);

            }
          </script>

          <?php



          include_once'footer.php';



        ?>