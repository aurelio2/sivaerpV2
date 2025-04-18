<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(0);




include_once'header.php';

if(isset($_POST['btnaddproduct'])){
$productname = $_POST['txtpname'];
$category= $_POST['txtselect_option'];  // $_POST[''];  
$purchaseprice =  $_POST['txtpprice']; 
$saleprice =  $_POST['txtsaleprice'];
$stock= $_POST['txtstock']; 
$description=$_POST['txtdescription'];
//$lote=$_POST['txtLote'];
//$data=$_POST['txtDatavalidade'];
//$tipo=$_POST['txtselect_data'];
//$dosa=$_POST['txtdosagem'];
$fornecedor=$_POST['txtselect_fornecedo'];
//$comercial=$_POST['txtcomercial'];
$iva=$_POST['txtiva'];
$codebar=$_POST['txtcodebar'];


$select= "SELECT * FROM tbl_product WHERE pname='{$productname}'";
$result = mysqli_query($mysqli,$select);


if($result->num_rows!=0){
  echo'<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Existe um Produto igual registado!!..",
                        showConfirmButton: true,
                        timer: 2000
                      });
                    });
              </script>';  
}else{



if(!isset($errorr)){

$insert=$pdo->prepare("insert into tbl_product(pname,pcategory,purchaseprice,saleprice,pstock,pdescription,fornecedor,iva,codebar) values  (:pname,:pcategory,:purchaseprice,:saleprice,:pstock,:pdescription,:fornecedor,:iva,:code)"); 
     
     $insert->bindParam(':pname',$productname); 
     $insert->bindParam(':pcategory',$category);
     $insert->bindParam(':purchaseprice',$purchaseprice);
     $insert->bindParam(':saleprice',$saleprice);
     $insert->bindParam(':pstock',$stock);
     $insert->bindParam(':pdescription',$description);
     //$insert->bindParam(':lote',$lote);
     //$insert->bindParam(':datavalidade',$data);
     //$insert->bindParam(':tipo',$tipo);
     //$insert->bindParam(':dosagem',$dosa);
     $insert->bindParam(':fornecedor',$fornecedor);
     //$insert->bindParam(':nome_comerc',$comercial);
     $insert->bindParam(':iva',$iva);
     $insert->bindParam(':code',$codebar);


     


     //$insert->bindParam(':pimage',$productimage);

     

     

if($insert->execute()){

echo'<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Produto salvo com sucesso",
                        showConfirmButton: true,
                        timer: 2000
                      });
                    });
              </script>';

}else{
   echo'<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Erro ao salvar ",
                        showConfirmButton: true,
                        timer: 2000
                      });
                    });
              </script>';  
      }     

    } 
  

  }  
}  


?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

          Registo de Produtos

        <small></small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>

        <li class="active">Here</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content container-fluid">
         <div class="box box-info">

            <div class="box-header with-border">

                <h3 class="box-title"> <a href="productlist.php" class="btn btn-warning" role="button">Listas Produtos</a></h3>

            </div>

         <form action="" method="post"  name="formproduct" enctype="multipart/form-data" >

            <div class="box-body">
            <div class="col-md-6">
               <div class="form-group">
                <label>Codigo(Opcional)</label>
                <input type="text" name="txtcodebar" class="form-control" placeholder="Codigo do produto" autocomplete="off">
              </div>
              <div class="form-group">
                  <label>Fornecedor</label>
                  <select class="form-control" name="txtselect_fornecedo" >
                    <option value="" disabled selected>Escolha o Fornecedor</option>
                  <?php
                  $select = $pdo->prepare("select * from tbl_fornecedor order by id desc");      
                  $select->execute();
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){
                  extract($row);
                  ?>    
                  <option><?php echo $row['marca'];?></option>
                  <?php   
                    }                  
                   ?>    
                  </select>
                </div>
              <div class="form-group" hidden>
                <label>Nome Comercial</label>
                <input type="text" name="txtcomercial" class="form-control" placeholder="Nome comercial do produto">
              </div>
              <div class="form-group">
                  <label >Produto</label>
                  <input type="text" class="form-control" name="txtpname" placeholder=" Produto" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label>Categoria</label>
                  <select class="form-control" name="txtselect_option" required>
                    <option value="" disabled selected>Escolha a Categoria</option>
                  <?php
                  $select = $pdo->prepare("select * from tbl_category order by catid desc");      
                  $select->execute();
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){
                  extract($row);
                  ?>    
                  <option><?php echo $row['category'];?></option>
                  <?php   
                    }                  
                   ?>    
                  </select>
                </div>                

                 <div class="form-group">

                  <label >Preço de compra</label>

                  <input type="text" min="0" class="form-control" name="txtpprice" placeholder="Preço de compra do Produto..." >

                </div>

                <div class="form-group">
                  <label >Preço unit</label>
                  <input type="text" min="1" step="1" class="form-control" name="txtsaleprice" placeholder="Preço unit do produto..." required>
                </div>  
                <!--<div class="form-group" hidden>
                  <label >Dosagem</label>
                  <input type="text" class="form-control" name="txtdosagem" placeholder="Dosagem..." >
                </div>-->
            </div> 

                 <div class="col-md-6">
                  <div class="form-group">
                  <label >Stock</label>
                  <input type="number"  class="form-control" name="txtstock" placeholder="Stock do produto..." required>
                </div> 
                <!--<div class="form-group" hidden>
                  <label >Numero de Lote</label>
                  <input type="text"  class="form-control" name="txtLote" placeholder="Numero de Lote..." >
                </div>-->
                <!--<div class="form-group">
                  <label >Data de Validade</label>
                  <input type="date"  class="form-control" name="txtDatavalidade"  data-date-format="AAAA-MM-DD">
                </div>-->
                 <div class="form-group">
                  <label >Descricão</label>
                  <textarea class="form-control" name="txtdescription" placeholder="Descricao do produto..."  rows="4"></textarea>
                </div>

                <div class="form-group">
                  <label>Iva</label>
                  <select class="form-control productid" name="produto" id="piroduto">
                    <option value="">Escolha o Imposto</option>
                    <?php 
                    $buscar=$pdo->prepare("select * from tbl_iva ORDER by iva");
                    $buscar->execute();

                    $resultado = $buscar->fetchAll();
                    foreach ($resultado as $key => $value) {
                      echo '
                       <option value="'.$value["id"].'_'.$value['valor'].'">'.$value["iva"].'</option>';
                              
                    }

                    ?>
                 </select>
                </div>
                <div class="form-group">
                  <label>Valor do iva</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                      <input type="number" class="form-control input-lg" name="txtiva" placeholder="16%" id="iva" readonly="">
                        </div>
                  </div>  
                </div>      
       

             </div>

             <div class="box-footer">
           

             <button type="submit" class="btn btn-info" name="btnaddproduct">Add +</button>            

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