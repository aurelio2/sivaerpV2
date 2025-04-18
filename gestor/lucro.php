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

    <h1 class="bg-gray" align="center">

      Lucros

      <small></small>

    </h1>
    </ol>

  </section>

  <section class="content container-fluid">

   <div class="box box-info">

    <div class="box-header with-border">  
    </div>
    <div class="box-body">
    <br>
      <div style="overflow-x:auto;" > 
        <table id="producttable" class="table table-striped">
          <thead>
            <tr>
              <th>Nome</th> 
              <th>Preco_compra</th>   
              <th>Preco_venda</th>
              <th>QTD vendida</th>  
              <th>Lucro</th> 
            </tr>    
          </thead> 
          <tbody>
          

            <?php
            $select=$pdo->prepare("SELECT p.pname,p.purchaseprice,p.saleprice,i.qty,SUM(p.saleprice*i.qty) as vendaT,SUM(p.purchaseprice*i.qty) as CompraT FROM
        tbl_invoice_details i JOIN tbl_product p ON p.pid = i.product_id      
        GROUP BY p.pid");
            $select->execute();

    
            while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                $total_compra=$row->CompraT;
       			$total_venda=$row->vendaT;
       			$total_saidas=$row->saidas;

              echo'
              <tr>
              <td>'.$row->pname.'</td>
              <td>'.number_format($row->purchaseprice,2).'</td>
              <td>'.number_format($row->saleprice,2).'</td>
              <td>'.$row->qty.'</td>
              <td class="total">'.number_format($row->vendaT-$row->CompraT,2).' MT</td>
              </tr>
              ';        
            }          
            ?>        
          </tbody> 
        </table> 
        <a href="darshboard.php" class="btn btn-info" role="button"><span class="glyphicon glyphicon-step-backward" style="color:#ffffff" data-toggle="tooltip" title="Voltar para Darshboar"></span>Voltar</a>  
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