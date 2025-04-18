<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';

include_once'header.php';    



$id=$_GET['id'];



$select=$pdo->prepare("select * from tipo where id=$id");

$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);

$id_db=$row['id'];



$tipo_db=$row['tipo'];



if(isset($_POST['btnupdate'])){



  $tipo_txt = $_POST['txttipo'];


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



              $update=$pdo->prepare("update tbl_tipo set tipo=:tipo where id = $id");

              $update->bindParam(':tipo',$tipo_txt);

            //$update->bindParam(':pimage',$f_newfile);


              if($update->execute()){



                echo'<script type="text/javascript">

                jQuery(function validation(){


                  swal({

                    title: "Tipo actualizado com sucesso!",

                    text: "Tipo actualizado",

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

                      text: "Update tipo Fail",

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

                $update=$pdo->prepare("update tbl_tipo set tipo=:tipo where id = $id");

                $update->bindParam(':tipo',$tipo_txt);



                if($update->execute()){

                  $error= '<script type="text/javascript">
                  alert("Tipo Actualizado com sucesso!");
                  window.location="listaTipo.php";
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



              $select=$pdo->prepare("select * from tbl_tipo where id=$id");

              $select->execute();

              $row=$select->fetch(PDO::FETCH_ASSOC);



              $id_db=$row['id'];



              $tipo_db=$row['tipo'];



              ?>

              <!-- Content Wrapper. Contains page content -->

              <div class="content-wrapper">

                <!-- Content Header (Page header) -->

                <section class="content-header">

                  <h1>

                    Edit Tipo

                    <small></small>

                  </h1>

                  <ol class="breadcrumb">

                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>

                    <li class="active">Here</li>

                  </ol>

                </section>

        <section class="content container-fluid">

        <div class="box box-warning">

          <div class="box-header with-border">

            <h3 class="box-title"><a href="listaTipo.php" class="btn btn-primary" role="button">Voltar</a></h3>

          </div>
          <form action="" method="post"  name="formproduct" enctype="multipart/form-data" >
            <div class="box-body">
              <div class="col-md-6">
                <div class="form-group">
                  <label >Tipo</label>

                  <input type="text" class="form-control" name="txttipo" value="<?php echo $tipo_db;?>" placeholder="Tipo" required>

                </div>

              </div> 

            </div>

            <div class="box-footer">

             <button type="submit" class="btn btn-warning" name="btnupdate">Actualizar</button>
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