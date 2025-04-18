<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once 'header.php';


?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      Mesas

      <small></small>

    </h1>
  </section>



  <!-- Main content -->

  <section class="content container-fluid">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">Lista de Mesas</h3>
          </div>
          <!-- /.box-header -->

          <!-- form start -->

          <div class="box-body">
           <div style="overflow-x:auto;" > 
             <table id="producttable" class="table table-striped">
              <thead>
                <tr>
                  <th>Numero</th>   
                  <th>Descrição</th> 
                  <th>Editar</th> 
                  <th>Delete</th>       
                </tr>    
              </thead> 
              <tbody>
                <?php
                $select=$pdo->prepare("select * from tbl_mesa ");
                $select->execute();
                while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                  echo'
                  <tr>
                  <td>'.$row->cod_mesa.'</td>
                  <td>'.$row->descricao.'</td>
                  <td>
                  <a href="editar_mesa.php?id='.$row->id.'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Editar Caixa"></span></a>   
                  </td>
                  <td>
                  <button id='.$row->id.' class="btn btn-danger btndelete" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="Delete Caixa"></span></button>  
                  </td>
                  </tr>
                  ';
                }          
                ?>               
              </tbody>               
            </table>  </div>     
          </div>
        </div>
      </section>
    </div>
    <script>
      $(document).ready( function () {
        $('#producttable').DataTable({
          "order":[[0,"desc"]]    
        });
      } );  
    </script>
    <script>
      $(document).ready( function () {
        $('[data-toggle="tooltip"]').tooltip();
      } );  
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
      $('.btndelete').click(function(){
       
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
                 url:'del_mesa.php',//nome do file php
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
                'Caixa excluido com sucesso ):',
                'success'
              )
            }
          });

      });
    });
  </script>    
    <?php
    include_once'footer.php';
    ?>