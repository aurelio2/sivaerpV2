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

      Lista de Produtos do Armazem

      <small></small>

    </h1>
    </ol>

  </section>

  <section class="content container-fluid">

   <div class="box box-warning">

    <div class="box-header with-border">    
    </div>
    <!-- <div class="box-body">
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
              <th>Editar</th> 
              <th>Delete</th>       
            </tr>    
          </thead> 
          <tbody>
          

            <?php
            $select=$pdo->prepare("select * from tbl_armazem order by pid desc");
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
      </div> -->
      <div class="box-body">
    <div style="overflow-x:auto;">
        <table id="producttable" class="table table-striped">
            <thead>
                <tr>
                    <th>Cod</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço de compra</th>
                    <th>Preço de venda</th>
                    <th>Stock</th>
                    <th>Opçoês</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select = $pdo->prepare("SELECT * FROM tbl_armazem ORDER BY pid DESC");
                $select->execute();

                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                    echo '
                    <tr>
                        <td>' . $row->pid . '</td>
                        <td>' . $row->pname . '</td>
                        <td>' . $row->pcategory . '</td>
                        <td>' . $row->purchaseprice . '</td>
                        <td>' . $row->saleprice . '</td>
                        <td>' . ($row->pstock <= 0 ? '<span style="color:red;">' . $row->pstock . '</span>' : '<span style="color:green;">' . $row->pstock . '</span>') . '</td>
                        <td>
                            <button class="btn btn-primary select-product" data-id="' . $row->pid . '" data-name="' . $row->pname . '" data-stock="' . $row->pstock . '">Transferir</button>
                             <a href="armazem_edit.php?id='.$row->pid.'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product">Edit</span></a>
                             <a href="pro_armazem_delete.php?id='.$row->pid.'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip" title="Apagar"></span> Delete</a>  
                        </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="quantityModal" style="display:none; position:fixed; top:30%; left:50%; transform:translate(-50%, -50%); z-index:1000; background:white; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.5);">
    <h4 id="modalProductName"></h4>
    <p>Quantidade disponível: <span id="modalProductStock"></span></p>
    <form id="transferForm" method="POST" action="transfer_products.php">
        <input type="hidden" name="product_id" id="modalProductId">
        <label for="quantity">Quantidade para transferir:</label>
        <input type="number" name="quantity" id="modalQuantity" min="1" required>
        <button type="submit" class="btn btn-success">Transferir</button>
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
    </form>
</div>

   

    </div>
  </section>
</div>

<script>
    document.querySelectorAll('.select-product').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productStock = this.getAttribute('data-stock');

            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('modalProductStock').innerText = productStock;
            document.getElementById('quantityModal').style.display = 'block';
        });
    });

    function closeModal() {
        document.getElementById('quantityModal').style.display = 'none';
    }
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


    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const selectedProductsList = document.getElementById('selected-products');
    const selectedProductsData = document.getElementById('selected-products-data');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productCategory = this.dataset.category;
            const purchasePrice = this.dataset.purchaseprice;
            const salePrice = this.dataset.saleprice;
            const stock = this.dataset.stock;

            if (this.checked) {
                // Add to selected products list
                const listItem = document.createElement('li');
                listItem.textContent = `${productName} - ${productCategory} - Compra: ${purchasePrice}, Venda: ${salePrice}, Estoque: ${stock}`;
                listItem.dataset.id = productId;
                selectedProductsList.appendChild(listItem);
            } else {
                // Remove from selected products list
                const items = selectedProductsList.querySelectorAll('li');
                items.forEach(item => {
                    if (item.dataset.id === productId) {
                        selectedProductsList.removeChild(item);
                    }
                });
            }

            // Update hidden input value
            const selectedProducts = [];
            selectedProductsList.querySelectorAll('li').forEach(item => {
                selectedProducts.push(item.dataset.id);
            });
            selectedProductsData.value = JSON.stringify(selectedProducts);
        });
    });
});


     </script> 
<?php

include_once'footer.php';
?>