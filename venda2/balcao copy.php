<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(0);
error_reporting(0);




$ref = $_GET['id'];

include_once 'cabecalho_user.php';

if(isset($_POST['btnaddorder'])){
$nome_cliente = $_POST['txtnome'];
$invoice = '00000';


$stmt = "INSERT INTO client_order_detalhes VALUES (NULL,'$nome_cliente','$ref','$invoice')";

if (mysqli_query($mysqli, $stmt)) {
    echo '<meta http-equiv="refresh" content="0;  url=createorder.php?id='.$ref.'">';
} else {
    echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
}


} 
  

  
 


?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Iniciar venda 

        <small>Codigo da venda: <?php  echo $ref;?></small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>

        <li class="active">Here</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content container-fluid">

         <div class="box box-info">

         <form action="" method="post" >

            <div class="box-body">
            <div class="col-md-12">
         
              <div class="form-group" >
                <label>Nome do cliente</label>
                <input type="text" name="txtnome" class="form-control" placeholder="Nome do cliente" autocomplete="off" value="<?php echo $ref;?>" readonly>
              </div>
                 
       

             </div>

             <div class="box-footer">
           

             <button type="submit" class="btn btn-info" name="btnaddorder">Continuar</button>            

              </div>

              </form>
          </div>

    </section>

    <!-- /.content -->

  </div>
  <div class="modal fade" id="modalAddUsuario">
    <?php 
        if (isset($_POST['tipoBtn'])) {
          $tipoPro = $_POST['txtTipo'];

          $insert=$pdo->prepare("insert into tbl_tipo(tipo)values(:tipo)"); 

          $insert->bindParam(':tipo',$tipoPro); 

          if ($insert->execute()) {
            echo '<script >  
              alert("Salvo com sucesso!");
               window.location="addproduct.php";
          </script>';
             
          }else{
             echo '<script >  
              alert("Não Registado!");
            </script>';
          }
        }
     ?>
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" method="post" action="">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tipo de Farmaco</h4>

                  </div>
                  <div class="modal-body">
                    <div class="box-body">
                      <!--Preco-->
                      <div class="form-group">
                        <label>Quantidade</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                          <input type="text" class="form-control input-lg" name="txtTipo" placeholder="Tipo de Farmaco" required="">
                        </div>
                      </div>
                      <!--Estado-->              
                    </div>                         
                  </div>
                  <div class="modal-footer">
                    <button type="reset" class="btn btn-default pull-left" data-dismiss="modal">Sair <i class="fa fa-danger"></i></button>
                    <button type="submit" class="btn btn-warning" name="tipoBtn"><i class="fa fa-plus" ></i>Salvar</button>
                  </div>

                </form>

              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.content-wrapper -->
          <script type="text/javascript">  
            $(document).ready( function () {
              $('#tabela_categoria').DataTable();
            } );
          </script>

           <script type="text/javascript">
            $("#piroduto").change(mostrarValores);

            function mostrarValores(){

              dadosProduto=document.getElementById('piroduto').value.split('_');
              //$("#idpro").val(dadosProduto[0]);
              $("#iva").val(dadosProduto[1]);
              //$("#preco").val(dadosProduto[2]);

            }
          </script>
          

  <!-- /.content-wrapper -->



  <?php



include_once'footer.php';



?>