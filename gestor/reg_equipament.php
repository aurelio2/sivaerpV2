<?php 
include_once '../dbconnect.php';
session_start(); 

if($_SESSION['useremail']=="" OR $_SESSION['role']=="vendas"){
    
    
    header('location:../index.php');
}
include_once 'header.php';

    
if (isset($_POST['btn_stock'])) {


  $modelo=$_POST['txtmodelo'];
  $desc=$_POST['txtdescricao'];

  $insert=$pdo->prepare("insert into tbl_equipamento (modelo,descricao) values(:modelo,:descricao)");

  $insert->bindParam(':modelo',$modelo);
  $insert->bindParam(':descricao',$desc);

  if ($insert->execute()) {
      echo '
        <script>alert("Equipamento salvo com sucesso!");
        window.redirect.replace("reg_equipament.php");
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
      <img src="../images/icons8-add_tab.png"> Formulario de equipamento
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
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddUsuario">Add <i class="fa fa-plus"></i></button> Clica o butão para registar o equipamento
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
          <h3 align="center">Lista de equipamentos </h3>
            <table class="table table-striped" id="tabela_produto">
              <thead>
                <tr>
                  <th>Modelo</th>
                  <th>Descrição</th>
                  <th>Opção</th>

                </tr>
              </thead>
              <tbody>
                <?php 
                $select=$pdo->prepare("select * from tbl_equipamento order by modelo desc");
                $select->execute();
                while ($row=$select->fetch(PDO::FETCH_OBJ)) {
                echo '
                <tr>
                  <td>'.$row->modelo.'</td>
                  <td>'.$row->descricao.'</td>
                  <td><a href="equipamento_edit.php?id='.$row->id.'" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Editar Preço"></span></a>

                  <button id='.$row->id.' class="btn btn-danger btbdelete" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="Delete Order"></span></button>

                  </td>

                </tr> 
                ';
              }
                   ?>
              </tbody>
            </table>
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
                  <h4 class="modal-title">Equipamento</h4>

                </div>
                <div class="modal-body">
                  <div class="box-body">
                    <!--Nome-->
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                          <input type="text" name="txtmodelo" class="form-control input-lg" placeholder="Modelo da Maquina">
                      </div>
                    </div>
                    
                    <!--Preco-->
                    <div class="form-group">
                      <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                        <input type="text" class="form-control input-lg" name="txtdescricao" placeholder="Descrição do modelo" required="">
                      </div>
                    </div>
                                 
                  </div>                         
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Sair <i class="fa fa-danger"></i></button>
                  <button type="submit" class="btn btn-success" name="btn_stock"><i class="fa fa-plus"></i>Salvar</button>
                </div>

              </form>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

      <script type="text/javascript">
        $("#piroduto").change(mostrarValores);

        function mostrarValores(){

          dadosProduto=document.getElementById('piroduto').value.split('_');
          $("#idpro").val(dadosProduto[0]);
          $("#pstock").val(dadosProduto[1]);
        }

      </script>
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
                 url:'delete_equipamento.php',//nome do file php
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
                'Equipamento excluido com sucesso ):',
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