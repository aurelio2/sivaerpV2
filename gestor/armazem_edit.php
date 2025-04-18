<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once 'header.php';    



$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_armazem where pid=$id");
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);
$id_db=$row['pid'];
$productname_db=$row['pname'];
$category_db=$row['pcategory'];
$purchaseprice_db=$row['purchaseprice'];
$saleprice_db=$row['saleprice'];
$stock_db=$row['pstock'];


//$cli_saida=

if(isset($_POST['btnupdate'])){
  $productname_txt = $_POST['txtpname'];
$category_txt = $_POST['txtselect_option'];  // $_POST['']; 
$purchaseprice_txt =  $_POST['txtpprice']; 
$saleprice_txt =  $_POST['txtsaleprice']; 
$stock_txt= $_POST['txtstock']; 

$fornecedor_txt=$_POST['txtselect_fornecedo'];




if(!empty($f_name)){
  $f_tmp = $_FILES['myfile']['tmp_name'];
  $f_size =  $_FILES['myfile']['size'];
  $f_extension = explode('.',$f_name);
  $f_extension= strtolower(end($f_extension));
  $f_newfile =  uniqid().'.'. $f_extension;   
  $store = "productimages/".$f_newfile;
  if($f_extension=='jpg' || $f_extension=='jpeg' ||  $f_extension=='png' || $f_extension=='gif'){
    if($f_size>=1000000 ){
      $error= '<script type="text/javascript">
      jQuery(function validation(){
        swal({
          title: "Error!",
          text: "Max file should be 1MB!",
          icon: "warning",
          button: "Ok",
          });
          });
          </script>';
          echo $error;      
        }else{
         if(move_uploaded_file($f_tmp,$store)){
           $f_newfile;
           if(!isset($error)){
            $update=$pdo->prepare("update tbl_product set pname=:pname , pcategory=:pcategory , purchaseprice=:pprice , saleprice=:saleprice, pstock=:pstock , pdescription=:pdescription,lote=:lote,datavalidade=:datavalidade,tipo=:tipo where pid = $id");

            $update->bindParam(':pname',$productname_txt);

            $update->bindParam(':pcategory',$category_txt);

            $update->bindParam(':pprice',$purchaseprice_txt);

            $update->bindParam(':saleprice',$saleprice_txt);

            $update->bindParam(':pstock',$stock_txt);

            $update->bindParam(':pdescription',$description_txt);

            $update->bindParam(':lote',$lote_txt);
            //$update->bindParam(':datavalidade',$data);
            //$update->bindParam(':tipo',$tipo_txt);

     //$update->bindParam(':pimage',$f_newfile);


            if($update->execute()){



              echo'<script type="text/javascript">
              jQuery(function validation(){
                swal({
                  title: "Update product Successfull!",
                  text: "Product Updated",
                  icon: "success",
                  button: "Ok",
                  });
                  });
                  </script>';
                }else{
                 echo'<script type="text/javascript">
                 jQuery(function validation(){
                  swal({
                    title: "ERROR!",
                    text: "Update product Fail",
                    icon: "error",
                    button: "Ok",
                    });
                    });
                    </script>';  
                  }     
                } 
              } 
            }   
          }else
          {
            $error= '<script type="text/javascript">
            jQuery(function validation(){
              swal({
                title: "Warning!",
                text: "only jpg ,jpeg, png and gif can be upload!",
                icon: "error",
                button: "Ok",
                });
              });
            </script>';
              echo $error;      
              }   
            }else{
            $update=$pdo->prepare("update tbl_armazem set pname=:pname , pcategory=:pcategory,purchaseprice=:pprice,saleprice=:saleprice, pstock=:pstock,fornecedor=:fornecedor where pid = $id");

              $update->bindParam(':pname',$productname_txt);
              $update->bindParam(':pcategory',$category_txt);
              $update->bindParam(':pprice',$purchaseprice_txt);
              $update->bindParam(':saleprice',$saleprice_txt);
              $update->bindParam(':pstock',$stock_txt);
              
              $update->bindParam(':fornecedor',$fornecedor_txt);
              //$update->bindParam(':nome_comerc',$comercial_txt);
              
             

              if($update->execute()){

                $error= '<script type="text/javascript">
                alert("Produto Actualizado com sucesso!");
                window.location="list_armazem.php";
                </script>';

    //header("location:productlist.php"); 
                echo $error;   
              }else{
               $error= '<script type="text/javascript">
               jQuery(function validation(){
                swal({
                  title: "Error!",
                  text: "update Fail",
                  icon: "error",
                  button: "Ok",
                  });
                });
                </script>';
                echo $error;   
                }

              }   

            }
            $select=$pdo->prepare("select * from tbl_armazem where pid=$id");
            $select->execute();
            $row=$select->fetch(PDO::FETCH_ASSOC);
            $id_db=$row['pid'];
            $productname_db=$row['pname'];
            $category_db=$row['pcategory'];
            $purchaseprice_db=$row['purchaseprice'];
            $saleprice_db=$row['saleprice'];
            $stock_db=$row['pstock'];
            
            $fornecedor_db=$row['fornecedor'];


            ?>

            <!-- Content Wrapper. Contains page content -->

            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <h1>
                  Editar Produto 
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

              <div class="col-md-12">
                 <div class="form-group">
                  <label >Fornecedor</label>
                  <select class="form-control" name="txtselect_fornecedo" required>
                    <option value="" disabled selected>Escolha o fornecedor</option>
                    <?php
                    $select = $pdo->prepare("select * from tbl_fornecedor order by id desc");  
                    $select->execute();
                    while($row=$select->fetch(PDO::FETCH_ASSOC)){
                      extract($row);
                      ?>    
                      <option <?php if($row['marca']==$fornecedor_db) {?>
                       selected="selected"

                       <?php } ?> >

                       <?php echo $row['marca'];?></option>
                       <?php   
                     }                  
                     ?>    
                   </select>
                </div>
                
                <div class="form-group">
                  <label >Produto</label>
                  <input type="text" class="form-control" name="txtpname" value="<?php echo $productname_db;?>" placeholder="Enter Name" required>
                </div>
                <div class="form-group">
                  <label>Categoria</label>
                  <select class="form-control" name="txtselect_option" required>
                    <option value="" disabled selected>Seleciona Categoria</option>
                    <?php
                    $select = $pdo->prepare("select * from tbl_category order by catid desc"); 
                    $select->execute();
                    while($row=$select->fetch(PDO::FETCH_ASSOC)){
                      extract($row);
                      ?>    
                      <option <?php if($row['category']==$category_db) {?>
                       selected="selected"
                       <?php } ?> >
                       <?php echo $row['category'];?></option>
                       <?php   
                     }                  
                     ?>    
                   </select>
                 </div>                

                 <div class="form-group">
                  <label >Preco de compra</label>
                  <input type="text"  class="form-control" value="<?php echo $purchaseprice_db;?>" name="txtpprice" placeholder="Enter..." >

                </div>
                <div class="form-group">
                  <label >Preco de venda</label>
                  <input type="text" min="1" max="1" class="form-control" value="<?php echo $saleprice_db; ?>" name="txtsaleprice" placeholder="Enter..." required>
                </div>  
                <div class="form-group">
                  <label >Stock</label>
                  <input type="number" class="form-control" value="<?php echo $stock_db;?>"  name="txtstock" placeholder="Enter..." required readonly>
                </div> 
              </div> 

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