<?php 
include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
include_once 'header.php';

    
if (isset($_POST['btn_forncedor'])) {


  $modelo=$_POST['idmodelo'];
  $fornecedor=$_POST['txtfornecedor'];
  $bi=$_POST['txtbi'];
  $nuit=$_POST['txtnuit'];
  $contacto=$_POST['txtcontacto'];


  $insert=$pdo->prepare("insert into tbl_fornecedor (idequipamento,nome,bi,nuit,contact) values(:modelo,:nome,:bi,:nuit,:contacto)");

  $insert->bindParam(':modelo',$modelo);
  $insert->bindParam(':nome',$fornecedor);
  $insert->bindParam(':bi',$bi);
  $insert->bindParam(':nuit',$nuit);
  $insert->bindParam(':contacto',$contacto);

  if ($insert->execute()) {
      echo '
        <script>alert("Fornecedor salvo com sucesso!");
        window.redirect.replace("reg_fornecedor.php");
        </script>
      ';
    }
   
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <img src="../images/icons8-group.png"> Formulario de Fornecedor
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i> Level</a></li>
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
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddUsuario">Add <i class="fa fa-plus"></i></button> Clica o butão para registar o forncedor
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i>
            </button>
          </div>
        </div>

        <div class="box-body">
          <h3 align="center">Lista de Fornecedor e seus equipamentos </h3>
           <div style="overflow-x:auto;" > 
            <table class="table table-striped" id="tabela_produto">
              <thead>
                <tr>
                  <th>Modelo</th>
                  <th>Nome</th>
                  <th>B.I</th>
                  <th>NUIT</th>
                  <th>Contacto</th>
                  <th>Opção</th>

                </tr>
              </thead>
              <tbody>
                <?php 
                $select=$pdo->prepare("select * from tbl_fornecedor order by nome desc");
                $select->execute();
                while ($row=$select->fetch(PDO::FETCH_ASSOC)) {
                    $ideq=$row['idequipamento'];

                    $select1=$pdo->prepare("select * from tbl_equipamento where id=$ideq");
                    $select1->execute();
                    $equip=$select1->fetch(PDO::FETCH_ASSOC);

                  ?>
               
                <tr>
                  <td><?php echo $equip['modelo']; ?></td>
                  <td><?php echo $row['nome']; ?></td>
                  <td><?php echo $row['bi']; ?></td>
                  <td><?php echo $row['nuit']; ?></td>
                  <td><?php echo $row['contact']; ?></td>


                  <td>
                    <a href="equipamento_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Editar Preço"></span></a>

                  <button id="<?php echo $row['id'];  ?>" class="btn btn-danger btbdelete" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="Deletar fornecedor"></span></button>

                  </td>

                </tr> 
              <?php
              }
              ?>
              </tbody>
            </table>
          </div>
          </div>
        </div>
          <!-- /.box-header -->
          <!-- form start -->
          
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

  <!--Modal para adicionar preco ao produto-->
  <div class="modal fade" id="modalAddUsuario">
        <div class="modal-dialog">
          <div class="modal-content">
            <form role="form" method="post" action="">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Fornecedor</h4>

                </div>

                <div class="modal-body">
                  <div class="box-body">
                    <!--Equipamento-->
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-users"></i></span>
                          <select class="form-control input-lg productid" name="idmodelo" id="piroduto">
                            <option value="">Escolha o Modelo</option>
                            <?php 
                            $buscar=$pdo->prepare("select * from tbl_equipamento order by modelo");
                            $buscar->execute();

                            $resultado = $buscar->fetchAll();
                            foreach ($resultado as $key => $value) {
                              echo '
                              <option value="'.$value["id"].'">'.$value["modelo"].'</option>
                            ';
                            }

                            ?>
                          </select>

                      </div>
                    </div>
                    <!--Nome-->
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-users"></i></span>
                          <input type="text" name="txtfornecedor" class="form-control input-lg" placeholder="Nome do fornecedor" required="">
                      </div>
                    </div>
                    
                    <!--BI-->
                    <div class="form-group">
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                        <input type="text" class="form-control input-lg" name="txtbi" placeholder="Numero de B.I" required="">
                      </div>
                    </div>

                    <!--NUIT-->
                    <div class="form-group">
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                        <input type="text" class="form-control input-lg" name="txtnuit" placeholder="Numero de NUIT" required="">
                      </div>
                    </div>

                    <!--Contacto-->
                    <div class="form-group">
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                        <input type="number" class="form-control input-lg" name="txtcontacto" placeholder="Contacto" min="9" step="1" required="">
                      </div>
                    </div>
                                 
                  </div>                         
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Sair <i class="fa fa-danger"></i></button>
                  <button type="submit" class="btn btn-success" name="btn_forncedor"><i class="fa fa-plus"></i>Salvar fornecedor</button>
                </div>

              </form>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


      <script type="text/javascript">  
      $(document).ready( function () {
      $('#tabela_produto').DataTable();
    } );

  </script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('.btbdelete').click(function(){
       
        var tdh = $(this);
        var id = $(this).attr("id");
        //alert(id);

        Swal.fire({
            title: 'Você tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Apagar!'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                 //Tudo code here 
                 url:'delete_fornecedor.php',//nome do file php
                 type:'post',
                 data:{
                    pidd:id
                 },
                 success:function(data){
                    tdh.parents('tr').hide();

                 }
                });
              Swal.fire(
                'Excluido!',
                'Fornecedor excluido com sucesso ):',
                'success'
              )
            }
          });

      });
    });
  </script> 

  
      <?php 
      include_once 'footer.php';
      ?>