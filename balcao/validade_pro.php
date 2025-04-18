<?php

include_once'../dbconnect.php';



session_start();


include_once'cabecalho_user.php';

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      Productos que expiram em 30 dias

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
              <th>Id</th>
               <th>Forma</th>  
              <th>Nome</th> 
              <th>Lote</th>  
              <th>Data</th> 
            </tr>    
          </thead> 
          <tbody>
          

            <?php
            $select=$pdo->prepare("SELECT pid,pname,pcategory,lote,datavalidade FROM tbl_product WHERE datavalidade between NOW() and DATE_ADD(NOW(), INTERVAL 30 DAY) ORDER BY datavalidade");
            $select->execute();

            while($row=$select->fetch(PDO::FETCH_OBJ)  ){
             
              echo'
              <tr>
              <td>'.$row->pid.'</td>
              <td>'.$row->pcategory.'</td>
              <td>'.$row->pname.'</td>
              <td>'.$row->lote.'</td>
              <td>'.$row->datavalidade.'</td>
              </tr>
              ';        
            }          
            ?>        
          </tbody> 
        </table> 
        <a href="mesa.php" class="btn btn-info" role="button"><span class="glyphicon glyphicon-step-backward" style="color:#ffffff" data-toggle="tooltip" title="Voltar para Darshboar"></span>Voltar</a>  
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