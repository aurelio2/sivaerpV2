<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once 'header.php';




if(isset($_POST['btnaddproduct'])){
  $numero=$_POST['txtnumero'];
  $desc=$_POST['txtdescription'];
  $estado=$_POST['txtstaus'];


  if(!isset($errorr)){
    $insert=$pdo->prepare("insert into tbl_mesa(cod_mesa,descricao,status) values(:mesa,:mdescription,:estado)"); 
    $insert->bindParam(':mesa',$numero); 
    $insert->bindParam(':mdescription',$desc);
    $insert->bindParam(':estado',$estado);
    if($insert->execute()){

      echo'<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Obrigado por salvar",
                        showConfirmButton: false,
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
                        title: "Erro ao salvar a mesa",
                        showConfirmButton: false,
                        timer: 2000
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
            Registro de assentos
            <small></small>
          </h1>
        </section>
        <!-- Main content -->
        <section class="content container-fluid">
      <!--------------------------

        | Your Page Content Here |

        -------------------------->

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"> <a href="lisa_mesa.php" class="btn btn-primary" role="button">Listas de Mesas</a></h3>
          </div>

          <!-- /.box-header -->

          <!-- form start -->
          <form action="" method="post"  name="formproduct" enctype="multipart/form-data" >
            <div class="box-body">
              <div class="col-md-6">
                <div class="form-group">
                  <label >Numero do assento</label>
                  <input type="text" class="form-control" name="txtnumero" placeholder="Numero da Mesa ex:03" required autocomplete="off">
                </div>       
              </div> 
              <div class="col-md-6">
                <div class="form-group">
                  <label >Descricão</label>
                  <textarea class="form-control" name="txtdescription" placeholder="Descricao da Mesa... Ex: Pedido o numero do pedido"  rows="4"></textarea>
                </div>
               
                <div class="form-group">
                  <input type="hidden"  class="form-control" name="txtstaus" value="0">
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