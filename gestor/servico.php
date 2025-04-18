<?php

    include_once '../dbconnect.php'; 
    session_start();  
    include_once 'header.php';

    if (isset($_POST['btn_reg'])) {

        $corte = $_POST['txt_corte'];
        $preco = $_POST['txt_preco'];
        //$desc = $_POST['txtdescricao'];

      $insert=$pdo->prepare("insert into tbl_servico(corte,pre_unt) values(:corte,:preco)");

      $insert->bindParam(':corte',$corte);
      //$insert->bindParam(':desc',$desc);
      $insert->bindParam(':preco',$preco);

      if ($insert->execute()) {
        echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Corte registado com sucesso!",
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
                        title: "Verfique a sentex de sql!",
                        showConfirmButton: true,
                        timer: 2000
                      });
                    });
              </script>';
      }
     

    }
    //delete
    if (isset($_POST['btn_delete'])) {
      
      $delete=$pdo->prepare("delete from tbl_servico where cod_servico=".$_POST['btn_delete']);

      if ($delete->execute()) {
          echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Serviço deletedo com sucesso!",
                        showConfirmButton: true,
                        timer: 3000
                      });
                    });
              </script>';
      }else{
        echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Erro ao deletar Serviço!",
                        showConfirmButton: true,
                        timer: 3000
                      });
                    });
              </script>';

      }
      
    }
    //upadate 
      
      if(isset($_POST['btn_update'])){

        $pro = $_POST['txt_tipo'];
        $qtd = $_POST['txt_qtd'];
        $desc = $_POST['txtdescricao'];
        $id =$_POST['txtid'];
        
        $update=$pdo->prepare("update tbl_servico set corte=:corte,descricao=:descricao,pre_unt=:preco where cod_servico=".$id);

          $update->bindParam(':corte',$pro);
          $update->bindParam(':descricao',$desc);
          $update->bindParam(':preco',$qtd);

          if ($update->execute()) {
              
              echo '<script type="text/javascript">
                    jQuery(function validation(){
                       Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Corte actualizado com sucesso!",
                        showConfirmButton: true,
                        timer: 3000
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
      Registo de Cortes ou Produtos
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
                        
              <div class="box-body">
              <form role="form" action="" method="post">
                <?php 
                  if (isset($_POST['btn_edit'])) {

                    $select= $pdo->prepare("select * from tbl_servico where cod_servico=".$_POST['btn_edit']);
      
                    $select->execute();

                    if ($select) {
                    
                    $row=$select->fetch(PDO::FETCH_OBJ); 
                        echo '<div class="col-md-4">
                          <div class="form-group">
                            <label>Produto</label>
                            <input type="hidden" class="form-control" name="txtid" value="'.$row->cod_servico.'">
                            <input type="text" class="form-control" name="txt_tipo" value="'.$row->corte.'" name="txt_categoria">
                          </div>
                          <!--
                            <div class="form-group">
                            <label>Descrição</label>
                            <textarea class="form-control" name="txtdescricao" rows="5">'.$row->descricao.'</textarea>
                            </div>-->

                             <div class="form-group">
                            <label>Preço</label>
                            <input type="text" class="form-control" name="txt_qtd" value="'.$row->pre_unt.'">
                            </div>
                        
                          <button type="submit" class="btn btn-success" name="btn_update">Actualizar</button>
                        </div>';
                    }
                    
                  }else{
                    echo '<div class="col-md-4">
                            <div class="form-group">
                            <label>Corte | Produto</label>
                            <input type="text" class="form-control" name="txt_corte" placeholder="Nome do corte" maxlength="15" autocomplete="off">
                            </div>
                            <!--
                            <div class="form-group">
                            <label>Descrição</label>
                            <textarea class="form-control" name="txtdescricao" placeholder="Discrição do corte" rows="5"></textarea>
                            </div>-->

                             <div class="form-group">
                            <label>Preço</label>
                            <input type="text" class="form-control" name="txt_preco" placeholder="Preço do corte" autocomplete="off">
                            </div>
                        
                          <button type="submit" class="btn btn-warning" name="btn_reg">Registar</button>
                        </div>';
                  }
                 ?>
              

              <div class="col-md-8">
                <table class="table table-striped" id="tabela_categoria">
                  <thead>
                    <tr>
                      <tr>
                      <th>Tipo de corte</th>                      
                      <!--<th>Descrição</th>-->
                      <th>Preço unit</th>
                      <th>Editar</th>
                      <th>Delete</th>
                    </tr>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $select=$pdo->prepare("select * from tbl_servico order by cod_servico desc");
                      $select-> execute();
                      while ($row=$select->fetch(PDO::FETCH_OBJ)) {
                        echo '

                            <tr>
                              <td>'.$row->corte.'</td>
                              <!--<td>'.$row->descricao.'</td>-->
                              <td>'.number_format($row->pre_unt,2).'</td>
                              <td><button type="submit" value="'.$row->cod_servico.'" class="btn btn-success" name="btn_edit">Editar</button></td>
                              <td><button type="submit" value="'.$row->cod_servico.'" class="btn btn-danger" name="btn_delete">Delete</button></td>
                            </tr>
                        ';
                      }
                     ?>
                  </tbody>
                 
                </table>
              </div>
            </form>

 
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">  
      $(document).ready( function () {
      $('#tabela_categoria').DataTable();
    } );
  </script>
<?php
    include_once'footer.php';
?>