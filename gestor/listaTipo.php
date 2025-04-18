<?php

include_once'../dbconnect.php';



session_start();


include_once'header.php';

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      Lista de Tipos

      <small></small>

    </h1>
    </ol>

  </section>

  <section class="content container-fluid">

   <div class="box box-warning">

    <div class="box-header with-border">    
    </div>
    <div class="box-body">
    <br>
      <div style="overflow-x:auto;" > 
        <table id="producttable" class="table table-striped">
          <thead>
            <tr>
              <th>Nome</th>   
              <th>Editar</th> 
              <th>Delete</th>       
            </tr>    
          </thead> 
          <tbody>
          

            <?php
            $select=$pdo->prepare("select * from tbl_tipo  order by id desc");
            $select->execute();

            while($row=$select->fetch(PDO::FETCH_OBJ)  ){

              echo'
              <tr>
              <td>'.$row->tipo.'</td>
              <td>
              <a href="edittipo.php?id='.$row->id.'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>   
              </td>
              <td>
              <button id='.$row->id.' class="btn btn-danger btndelete" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="Delete Tipo"></span></button>  
              </td>
              </tr>
              ';        
            }          
            ?>        
          </tbody> 
        </table> 
        <a href="addproduct.php" class="btn btn-info" role="button"><span class="glyphicon glyphicon-step-backward" style="color:#ffffff" data-toggle="tooltip" title="Volar Product"></span>Voltar</a>  
        </div>     
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
      console.log(id);
        Swal.fire({
          title: 'Você tem certeza?',
          text: "Deletado o tipo, Voce não podera fazer o recuver!!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sim, Apagar!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
                 //Tudo code here 
                 url:'tipodelete.php',//nome do file php
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
              'Tipo Foi deletedo):',
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