<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
include_once 'cabecalho_user.php';

?>

<?php 
if (isset($_POST['saveAdjustment'])) {
    $produto_id = $_POST['productNameId'];
    $stock_ant = $_POST['txt_stock_ant'];
    $stock_quebra = $_POST['txt_stock_qubra'];
    $user_id = $idUser;
    $data = date('Y-m-d');

    // Validação básica
    if (empty($produto_id) || empty($stock_ant) || empty($stock_quebra)) {
        echo '<script>alert("Todos os campos são obrigatórios!");</script>';
    } else if ($stock_quebra > $stock_ant) {
        echo '<script>alert("A quantidade de quebra não pode ser maior que o estoque atual.");</script>';
    } else {
        $stmt = $pdo->prepare("INSERT INTO tbl_quebra_stock (id_produto, stock_anterior, stock_quebra, id_user, data) 
                               VALUES (:produto_id, :stock_ant, :stock_quebra, :user_id, :data)");

        $stmt->execute([
            ':produto_id' => $produto_id,
            ':stock_ant' => $stock_ant,
            ':stock_quebra' => $stock_quebra,
            ':user_id' => $user_id,
            ':data' => $data,
        ]);

        // Atualizar o estoque
        $totalQuantidade = $stock_ant - $stock_quebra;
        $update = $pdo->prepare("UPDATE tbl_product SET pstock = :totalQuantidade WHERE pid = :produto_id");
        $update->execute([':totalQuantidade' => $totalQuantidade, ':produto_id' => $produto_id]);

        echo '<script>
            alert("Obrigado, Quebra registrada com sucesso!");
            window.location.href = "ajust_list.php";
        </script>';
    }
}


?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Lista de Produtos
      <small>Registar Quebras</small>
    </h1>
    </ol>
  </section>

  <section class="content container-fluid">
   <div class="box box-warning">
    <div class="box-header with-border">    
    </div>
    <div class="box-body">
      <div style="overflow-x:auto;" > 
        <table id="producttable" class="table table-striped">
          <thead>
            <tr>
              <th>Cod</th>            
              <th>Produto</th>
              <th>Categoria</th>   
              <th>Pre&ccedil;o de compra</th>   
              <th>Pre&ccedil;o de venda</th>   
              <th>Stock</th> 
              <th>Ajuste</th> 
            </tr>    
          </thead> 
          <tbody>
            <?php
            $select=$pdo->prepare("select * from tbl_product order by pid desc");
            $select->execute();

            while($row=$select->fetch(PDO::FETCH_OBJ)  ){

              echo'
              <tr>
              <td>'.$row->pid.'</td>
              
              <td>'.$row->pname.'</td>
             
              <td>'.$row->pcategory.'</td>
              <td>'.$row->purchaseprice.'</td>
              <td>'.$row->saleprice.'</td>
              ';
               if ($row->pstock<=0) {
                 echo '<td style="color:red;">'.$row->pstock.'</td>';
              }else{
                echo '<td style="color:green;">'.$row->pstock.'</td>';
              }
              echo'
            
              <td>
                <button class="btn btn-warning adjust-stock" data-id="'.$row->pid.'" 
                        data-name="'.$row->pname.'" data-stock="'.$row->pstock.'">
                    Ajustar
                </button>
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

  <section class="content container-fluid">

<div class="box box-warning">
 <div class="box-header with-border">  
    <h3>Lista de Quebras do dia: <?php echo date('d/m/Y');?></h3>  
 </div>
 <div class="box-body">
   <div style="overflow-x:auto;" > 
     <table id="producttable1" class="table table-striped">
       <thead>
         <tr>
           <th>Cod</th>            
           <th>Produto</th>
           <th>Categoria</th>   
           <th>Stock Anterior</th>   
           <th>Stock Quebra</th>   
           <th>Total</th>   
           <th>User</th> 
           <th>Data</th> 
         </tr>    
       </thead> 
       <tbody>
         <?php
         $data_hoje = date('Y-m-d');
         $select=$pdo->prepare("select * from tbl_quebra_stock where data='$data_hoje'");
         $select->execute();

         while($row=$select->fetch(PDO::FETCH_OBJ)  ){
            $prod_id = $row->id_produto;
            $user_id = $row->id_user;

            $cons = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE pid = '$prod_id'");
            $res = mysqli_fetch_array($cons);
            $product_name = $res['pname'];
            $product_categoria = $res['pcategory'];

             //users
             $cons1 = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE userid = '$user_id'");
             $res1 = mysqli_fetch_array($cons1);
             $full_name = $res1['username'];
 

           echo'
           <tr>
           <td>'.$prod_id.'</td>
           <td>'.$product_name.'</td>
           <td>'.$product_categoria.'</td>
           <td>'.$row->stock_anterior.'</td>
           <td>'.$row->stock_quebra.'</td>
           <td>'.number_format($row->stock_anterior-$row->stock_quebra).'</td>
           <td>'.$full_name.'</td>
           <td>'.$row->data.'</td>
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

<!-- Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" role="dialog" aria-labelledby="adjustStockModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adjustStockModalLabel">Ajustar Estoque</h5>
       
      </div>
      <div class="modal-body">
        <form id="adjustStockForm" method="post">
        <div class="form-group">
            <label for="productNameId">Codigo do Produto:</label>
            <input type="text" id="productNameId" class="form-control" name="productNameId"  readonly>
          </div>
          <div class="form-group">
            <label for="productName">Produto:</label>
            <input type="text" id="productName" class="form-control"  readonly>
          </div>
          <div class="form-group">
            <label for="productStock">Stock Atual:</label>
            <input type="text" id="productStock" class="form-control" name="txt_stock_ant" readonly>
          </div>
          <div class="form-group">
            <label for="breakQuantity">Quantidade de Produto em Quebra:</label>
            <input type="number" id="breakQuantity" class="form-control" name="txt_stock_qubra" placeholder="Digite a quantidade">
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary" id="saveAdjustment" name="saveAdjustment">Salvar Ajuste</button>
      </div>
    </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Clique no botão de ajuste
    document.querySelectorAll(".adjust-stock").forEach(button => {
      button.addEventListener("click", function () {
        const productId = this.getAttribute("data-id");
        const productName = this.getAttribute("data-name");
        const productStock = parseInt(this.getAttribute("data-stock"), 10);

         // Verifica se o estoque é 0
        if (productStock <= 0) {
            alert("Não é possível registrar quebra para um produto com estoque zero.");
            return;
        }

        // Preenche os campos do modal
        document.getElementById("productName").value = productName;
        document.getElementById("productStock").value = productStock;
        document.getElementById("productNameId").value = productId;

        // Abre o modal
        $("#adjustStockModal").modal("show");
      });
    });

    // document.getElementById("saveAdjustment").addEventListener("click", () => {
    //   const productName = document.getElementById("productName").value;
    //   const breakQuantity = document.getElementById("breakQuantity").value;

    //   if (!breakQuantity) {
    //     alert("Por favor, insira a quantidade.");
    //     return;
    //   }

    //   // Envie os dados ao servidor (via AJAX, por exemplo)
    //   console.log("Ajuste salvo para:", productName, "Quantidade:", breakQuantity);

    //   // Fechar o modal
    //   $("#adjustStockModal").modal("hide");
    // });
    document.getElementById("saveAdjustment").addEventListener("click", () => {
    const stockAnt = parseInt(document.getElementById("productStock").value, 10);
    const breakQuantity = parseInt(document.getElementById("breakQuantity").value, 10);

    if (!breakQuantity || breakQuantity <= 0) {
        alert("Por favor, insira uma quantidade válida.");
        return;
    }

    if (breakQuantity > stockAnt) {
        alert("A quantidade de quebra não pode ser maior que o estoque atual.");
        return;
    }

    // Submeter o formulário
    document.getElementById("adjustStockForm").submit();
    });

    document.getElementById("saveAdjustment").addEventListener("click", () => {
    console.log("Form data:", new FormData(document.getElementById("adjustStockForm")));
});

  });
</script>

<script>
  $(document).ready( function () {
    $('#producttable').DataTable({
      "order":[[0,"desc"]]    
    });
  } );  
</script>

<script>
  $(document).ready( function () {
    $('#producttable1').DataTable({
      "order":[[0,"desc"]]    
    });
  } );  
</script>
<script>
  $(document).ready( function () {
    $('[data-toggle="tooltip"]').tooltip();
  } );  
</script>
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