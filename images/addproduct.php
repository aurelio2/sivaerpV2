<?php

include_once'../dbconnect.php';



session_start();





if($_SESSION['useremail']=="" OR $_SESSION['role']=="Barb-men"){

    

    

    header('location:../index.php');

}







include_once'header.php';



if(isset($_POST['btnaddproduct'])){

    

$productname = $_POST['txtpname'];

    

$category= $_POST['txtselect_option'];  // $_POST[''];  

    

$purchaseprice =  $_POST['txtpprice']; 

    

$saleprice =  $_POST['txtsaleprice']; 

    

$stock= $_POST['txtstock']; 



$description=$_POST['txtdescription'];

    
if(!isset($errorr)){

     

$insert=$pdo->prepare("insert into tbl_product(pname,pcategory,purchaseprice,saleprice,pstock,pdescription) values(:pname,:pcategory,:purchaseprice,:saleprice,:pstock,:pdescription)"); 

     

     $insert->bindParam(':pname',$productname); 

     $insert->bindParam(':pcategory',$category);

     $insert->bindParam(':purchaseprice',$purchaseprice);

     $insert->bindParam(':saleprice',$saleprice);

     $insert->bindParam(':pstock',$stock);

     $insert->bindParam(':pdescription',$description);

     //$insert->bindParam(':pimage',$productimage);

     

     

if($insert->execute()){

    

    echo'<script type="text/javascript">

jQuery(function validation(){





swal({

  title: "Add product Successfull!",

  text: "Product Added",

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

  text: "Add product Fail",

  icon: "error",

  button: "Ok",

});





});



</script>';  

    

}     

     

    

 } 

           

           

    

}    

          







?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

          Registro de produto

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

        

         <div class="box box-info">

            <div class="box-header with-border">

                <h3 class="box-title"> <a href="productlist.php" class="btn btn-primary" role="button">Listas de produto</a></h3>

            </div>

            <!-- /.box-header -->

            <!-- form start -->



 <form action="" method="post"  name="formproduct" enctype="multipart/form-data" >





            <div class="box-body">

            

           

                

            <div class="col-md-6">

                

              <div class="form-group">

                  <label >Nome do Produto</label>

                  <input type="text" class="form-control" name="txtpname" placeholder="Nome do produto" required>

                </div>

                                

                                

                <div class="form-group">

                  <label>Categoria</label>

                  <select class="form-control" name="txtselect_option" required>

                    <option value="" disabled selected>Escolha a categoria</option>

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

                  <input type="number"  class="form-control" name="txtpprice" placeholder="Preço de compra do Produto..." >

                </div>

                

                   <div class="form-group">

                  <label >Preço de venda</label>

                  <input type="number" min="1" step="1" class="form-control" name="txtsaleprice" placeholder="Preço de venda do produto..." required>

                </div>  

                

                

                

            </div> 

                

                 

                   

                 <div class="col-md-6">

                     

                     

                      <div class="form-group">

                  <label >Stock</label>

                  <input type="number"  class="form-control" name="txtstock" placeholder="Stock do produto..." required>

                </div> 

                    

                    

              <div class="form-group">

                  <label >Descricão</label>

                  <textarea class="form-control" name="txtdescription" placeholder="Descricao do produto..."  rows="4"></textarea>

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

  <!-- /.content-wrapper -->



  <?php



include_once'footer.php';



?>