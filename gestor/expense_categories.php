<?php

      include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';


include_once'header.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS expense_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if(isset($_POST['btnsave'])){
    $name = $_POST['txtname'];
    if(empty($name)){
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
        $insert=$pdo->prepare("insert into expense_categories(name) values(:name)");
        $insert->bindParam(':name',$name); 
        if($insert->execute()){

        echo '<script type="text/javascript">
            jQuery(function validation(){
             Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Categoria salvo com sucesso",
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
                    title: "Erro ao salvar a categoria",
                    showConfirmButton: false,
                    timer: 2000
                    });
                    });
                    </script>';
                }    

            }        

}// btnadd end here

if(isset($_POST['btnupdate'])){
 $name = $_POST['txtname'];
 $id = $_POST['txtid'];
 if(empty($name)){
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

    $update=$pdo->prepare("update expense_categories set name=:name where id=".$id);
    $update->bindParam(':name',$name);
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
   $delete=$pdo->prepare("delete from expense_categories where id=".$_POST['btndelete']); 
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
                title: "Error ao deletar a categoria",
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
                    Categoria de Despesa
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

            <div class="box-header with-border">

                <h3 class="box-title">Formulario de Categoria</h3>

            </div>

            <div class="box-body">

                <form role="form" action="" method="post">

                    <?php

                    if(isset($_POST['btnedit'])){

                        $select=$pdo->prepare("select * from expense_categories where id=".$_POST['btnedit']); 

                        $select->execute();

                        if($select){

                            $row =$select->fetch(PDO::FETCH_OBJ);    

                            echo' <div class="col-md-4">
                            <div class="form-group">
                            <label >Nome</label>
                            <input type="hidden" class="form-control" value="'.$row->id.'" name="txtid"  placeholder="Digite o nome" >
                            <input type="text" class="form-control" value="'.$row->name.'" name="txtname"  placeholder="Digite o nome" >
                            </div>
                            <button type="submit" class="btn btn-info" name="btnupdate">Actualizar</button>
                            </div>';
                        }        

                    }else{

                        echo' <div class="col-md-4">
                        <div class="form-group">
                        <label >Nome</label>
                        <input type="text" class="form-control" name="txtname" placeholder="Digite o nome" >
                        </div>
                        <button type="submit" class="btn btn-warning" name="btnsave">Salvar</button>
                        </div>';    

                        }          

                    ?>

                    <div class="col-md-8">
                        <table id="tableexpensecategories" class="table table-striped">
                            <thead>
                                <tr>
                                   <th>#</th>
                                   <th>Nome</th>
                                   <th>Editar</th>
                                   <th>Apagar</th>
                               </tr>
                           </thead>
                           <tbody>
                            <?php

                            $select=$pdo->prepare("select * from expense_categories order by id desc");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                                echo' <tr>
                                <td>'.$row->id.'</td>
                                <td>'.$row->name.'</td>
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

    $('#tableexpensecategories').DataTable();

} );  

</script>






<?php



include_once'footer.php';



?>
