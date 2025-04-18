<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';


include_once'header.php';

if(isset($_POST['btnsave'])){
    //$nome = $_POST['txtnome'];
    $marca = $_POST['txtmarca'];
    $produto = $_POST['txtproduto'];

    if(empty($marca)){
       $error='<script type="text/javascript">
       jQuery(function validation(){
        swal({
          title: "Feild is Empty!",
          text: "Please Fill Feild!!",
          icon: "error",
          button: "Ok",
          });
        });
        </script>';   
        echo $error;  

      }

      if(!isset($error)){
        $insert=$pdo->prepare("insert into tbl_fornecedor(marca,produto) values(:marca,:produto)");
        //$insert->bindParam(':nome',$nome);
        $insert->bindParam(':marca',$marca);
        $insert->bindParam(':produto',$produto);
        if($insert->execute()){
        echo '<script type="text/javascript">
            jQuery(function validation(){
             Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Forncedor salvo com sucesso",
                showConfirmButton: true,
                timer: 2000
                });
                });
                </script>';
            }else{
               echo '<script type="text/javascript">
               jQuery(function validation(){
                 Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Erro ao salvar o forncedor",
                    showConfirmButton: false,
                    timer: 2000
                    });
                    });
                    </script>';
                }    

            }        

}// btnadd end here

if(isset($_POST['btnupdate'])){
    //$nome = $_POST['txtnome'];
    $marca = $_POST['txtmarca'];
    $produto = $_POST['txtproduto'];
    $id = $_POST['txtid'];
 if(empty($marca)){
    $errorupdate='<script type="text/javascript">
    jQuery(function validation(){
     Swal.fire({
        position: "top-end",
        icon: "error",
        title: "Porfavor preencha os campos",
        showConfirmButton: false,
        timer: 2000
        });
        });
        </script>';    
    echo $errorupdate; 
    } 
    if(!isset($errorupdate)){ 

    $update=$pdo->prepare("update tbl_fornecedor set marca=:marca,produto=:produto where id=".$id);
    //$update->bindParam(':nome',$nome);
    $update->bindParam(':marca',$marca);
    $update->bindParam(':produto',$produto);
    if($update->execute()){
         echo '<script type="text/javascript">
         jQuery(function validation(){
             Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Actualizado com sucesso",
                showConfirmButton: false,
                timer: 2000
                });
                });
                </script>';   

            }else{

              echo '<script type="text/javascript">
              jQuery(function validation(){
                 Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Erro ao actualizar",
                    showConfirmButton: false,
                    timer: 2000
                    });
                    });
                    </script>';
                }    
            }  

} // btn update code end here

if(isset($_POST['btndelete'])){
   $delete=$pdo->prepare("delete from tbl_fornecedor where id=".$_POST['btndelete']); 
   if($delete->execute()){
      echo '<script type="text/javascript">
      jQuery(function validation(){
         Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Deletado com sucesso",
            showConfirmButton: false,
            timer: 2000
            });
            });
            </script>'; 
        }else{

         echo '<script type="text/javascript">
         jQuery(function validation(){
             Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Error ao deletar o forncedor",
                showConfirmButton: false,
                timer: 2000
                });
                });
                </script>';


            } 



        }


        ?>





        <!-- Content Wrapper. Contains page content -->

        <div class="content-wrapper">

            <!-- Content Header (Page header) -->

            <section class="content-header">

                <h1>

                    Fornecedor

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
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Formulario de Forncedor</h3>
            </div>
            <div class="box-body">
                <form role="form"  method="post">
                    <?php
                    if(isset($_POST['btnedit'])){
                        $select=$pdo->prepare("select * from tbl_fornecedor where id=".$_POST['btnedit']); 
                        $select->execute();
                        if($select){
                            $row =$select->fetch(PDO::FETCH_OBJ);    
                            echo' <div class="col-md-4">
                            <div class="form-group">
                            <input type="hidden" class="form-control" value="'.$row->id.'" name="txtid">
                            <label >Produto</label>
                            <input type="text" class="form-control" value="'.$row->marca.'" name="txtmarca"  placeholder="Marca">   
                            </div>
                            <div class="form-group">
                            <label >Produto</label>
                            <input type="text" class="form-control" value="'.$row->produto.'" name="txtproduto"  placeholder="produto">   
                            </div>
                            <button type="submit" class="btn btn-info" name="btnupdate">Actualizar</button>

                            </div>'; 

                      }        

                    }else{


                        echo' <div class="col-md-4">
                        <label >Marca</label>
                        <div class="form-group">
                        <input type="text" class="form-control" name="txtmarca" placeholder="marca de produto" >
                        </div>
                        <div class="form-group">
                        <label >Produto</label>
                        <input type="text" class="form-control" name="txtproduto" placeholder="Produto" >
                        </div>
                        <button type="submit" class="btn btn-warning" name="btnsave">Salvar</button>
                        </div>';    
                    }          

                    ?>
                    <div class="col-md-8">
                        <table id="tablecategory" class="table table-striped">
                            <thead>
                                <tr>
                                   <th>#</th>
                                   <th>Marca</th>
                                   <th>Produto</th>
                                   <th>Editar</th>
                                   <th>Apagar</th>
                               </tr>
                           </thead>
                           <tbody>
                            <?php

                            $select=$pdo->prepare("select * from tbl_fornecedor order by id desc");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            echo' <tr>
                                <td>'.$row->id.'</td>
                                <td>'.$row->marca.'</td>
                                <td>'.$row->produto.'</td>
                                <td>
                                <button type="submit" value='.$row->id.' class="btn btn-success" name="btnedit">Edit</button>
                                </td>
                                <td>
                                <button type="submit" value="'.$row->id.'" class="btn btn-danger" name="btndelete">Delete</button>
                                </td>

                                </tr>';    
                            }            
                        ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        </div>
    </div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>

  $(document).ready( function () {

    $('#tablecategory').DataTable();

} );  





</script>







<?php



include_once'footer.php';



?>