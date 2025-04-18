<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';


include_once'header.php';

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      Lista de Produtos Expirados

      <small></small>

    </h1>
    </ol>

  </section>

  <section class="content container-fluid">

   <div class="box box-warning">

    <div class="box-header with-border">    
    </div>
    <div class="box-body">
    <!--<a href="../recibos/lista_produto.php" class="btn btn-info" role="button"><span class="glyphicon glyphicon-print" style="color:#ffffff" data-toggle="tooltip" title="Imprimir Produto" align="right"></span>Imprimir</a>-->
    <br>
    <br>
      <div style="overflow-x:auto;" > 
        <table id="producttable" class="table table-striped">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Produto</th>
              <th>Categoria</th>   
              <th>Pre&ccedil;o de venda</th>   
              <th>Stock</th> 
              <th>Data</th> 
              <th>Editar</th> 
              <th>Delete</th>       
            </tr>    
          </thead> 
          <tbody>
          

            <?php
            $select=$pdo->prepare("select * from tbl_product where datavalidade<=curdate()");
            $select->execute();

            while($row=$select->fetch(PDO::FETCH_OBJ)  ){

              echo'
              <tr>
              <td>'.$row->pid.'</td>
              <td>'.$row->pname.'</td>
              <td>'.$row->pcategory.'</td>
              <td>'.$row->saleprice.'</td>
              ';
               if ($row->pstock<=0) {
                 echo '<td style="color:red;">'.$row->pstock.'</td>';
              }else{
                echo '<td style="color:green;">'.$row->pstock.'</td>';
              }
              echo'
              <td>'.$row->datavalidade.'</td>

              <td>
              <a href="editproduct.php?id='.$row->pid.'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>   
              </td>
              <td>
              <a href="productdelete.php?id='.$row->pid.'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip" title="Apagar"></span> Delete</a>  
              </td>
              </tr>
              ';        
            }          
            ?>        
          </tbody>               
        </table>  
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

<!--<script type="text/javascript">
  $(document).ready(function(){
    $('.btndelete').click(function(){
      var tdh = $(this);
      var id = $(this).attr("id");
        Swal.fire({
          title: 'Você tem certeza?',
          text: "Deletado o produto, Voce não podera fazer o recuver!!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sim, Apagar!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
                 //Tudo code here 
                 url:'productdelete.php',//nome do file php
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
              'Produto Foi deletedo):',
              'success'
              )
          }
        });

      });
  });
</script>  --> 
<script>
        function deleteme(id) {
           if (confirm("Você tem certeza?")) {
              window.location.href='productdelete.php='+id+'';

              return true;
           }else{
              alert("Obrigado produto salva!");
              window.location.href='productlist.php';
           }
         } 

     </script> 
<?php

include_once'footer.php';
?>