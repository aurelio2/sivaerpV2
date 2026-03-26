<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
include_once 'funcoes_caixa.php';
error_reporting(1);

// Obter a data do caixa aberto em vez da data atual
$data = getDataCaixaAberto($idUser, $mysqli);

//if($verifica>0){
$mid=$_GET['id'];
$select=$pdo->prepare("select * from tbl_mesa where cod_mesa=$mid");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);


$codemesa=$row['cod_mesa'];

$sql = mysqli_query($mysqli, "SELECT * FROM client_order_detalhes WHERE id =
          (select MAX(id) from client_order_detalhes where id_order=$mid )");

$res = mysqli_fetch_array($sql);
$id_client_order = $res['id'];
$nome_cliente_db = $res['nome'];

function fill_product($pdo){

    $output='';
    
    $select=$pdo->prepare("select * from tbl_product where pstock !=0"); 
    $select->execute();
    $result=$select->fetchAll();
    
    foreach($result as $row){

        //$output.='<option value="'.$row["pid"].'" >'.$row["codebar"].'</option>';
        $output.='<option value="'.$row["pid"].'">'.$row["codebar"].'-'.$row["pname"].'</option>';    

    }    
    
    return $output;   
    
}

function get_product_cards($pdo, $mysqli) {
    $output = '';
    
    // Consulta para buscar todos os produtos com estoque positivo
    $query = "SELECT * FROM tbl_product WHERE pstock > 0 ORDER BY pname";
    $result = mysqli_query($mysqli, $query);
    
    if (!$result) {
        return $output . '<div class="alert alert-danger">Erro na consulta: ' . mysqli_error($mysqli) . '</div>';
    }
    
    if (mysqli_num_rows($result) > 0) {
        $output .= '<div class="row product-cards-container">';
        
        while ($product = mysqli_fetch_assoc($result)) {
            // Obter valores dos campos
            $pid = isset($product['pid']) ? $product['pid'] : '';
            $pname = isset($product['pname']) ? $product['pname'] : 'Produto';
            $price = isset($product['saleprice']) ? $product['saleprice'] : 0;
            $stock = isset($product['pstock']) ? $product['pstock'] : 0;
            $category = isset($product['pcategory']) ? $product['pcategory'] : 'Sem Categoria';
            $iva = isset($product['iva']) ? $product['iva'] : '16';
            
            // Criar card do produto
            $output .= '<div class="col-md-2 col-sm-2 col-xs-6">'
                     . '<div class="product-card" '
                     . 'data-id="' . $pid . '" '
                     . 'data-name="' . htmlspecialchars($pname, ENT_QUOTES) . '" '
                     . 'data-price="' . $price . '" '
                     . 'data-stock="' . $stock . '" '
                     . 'data-category="' . htmlspecialchars($category, ENT_QUOTES) . '" '
                     . 'data-iva="' . $iva . '">'
                     . '<div class="product-name">' . $pname . '</div>'
                     . '<div class="product-price">' . number_format($price, 2) . ' MT</div>'
                     . '<div class="product-stock">Stock: ' . $stock . '</div>'
                     . '<button type="button" class="btn btn-success btn-sm btn-add-product">Adicionar</button>'
                     . '</div>'
                     . '</div>';
        }
        
        $output .= '</div>';
    } else {
        $output .= '<div class="alert alert-warning">Não foram encontrados produtos com estoque disponível!</div>';
    }
    
    return $output;
}


if(isset($_POST['btnsaveorder'])){

    $customer_name=$_POST['txtcustomer'];
    // Usar a data do caixa aberto em vez da data do formulário
    $order_date = $data;
    $total=$_POST['txttotal'];
    $sub_total=$_POST['txt_subtotal'];

    //$paid=$_POST['txtpaid'];
    //$due=$_POST['txtdue'];
    //$payment_type=$_POST['rb'];

    //mesa
    $mesa=$_POST['txt_mesa'];
    $mesa1=$_POST['txtmesa'];
    //$iva=$_POST['txtiva'];

    ////////////////////////////////
    
    $arr_productid=$_POST['productid'];
    $arr_productname=$_POST['productname'];
    $arr_stock=$_POST['stock'];
    $arr_qty=$_POST['qty'];
    $arr_price=$_POST['price'];
    $arr_total=$_POST['total'];
    $arr_cate=$_POST['categaria'];
    //$arr_t_sub=$_POST['Subtotal'];
    $arr_t_iva=$_POST['totaliva'];

    if($mid==55){
        $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$mid");
    $insert_mesa->bindParam(":estado",$mesa1);
    $insert_mesa->execute();
    
    $insert=$pdo->prepare("insert into tbl_invoice(mesa,customer_name,order_date,subtotal,total,user)values(:mesa,:cust,:orderdate,:subtotal,:total,:user)");
    $insert->bindParam(':cust',$customer_name);
    $insert->bindParam(':orderdate',$order_date);
    $insert->bindParam(':total',$total);
    $insert->bindParam(':mesa',$mid);
    $insert->bindParam(':subtotal',$sub_total);
     $insert->bindParam(':user',$idUser);
    //$insert->bindParam(':iva',$iva);
    $insert->execute();
    }else{
    $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$mid");
    $insert_mesa->bindParam(":estado",$mesa1);
    $insert_mesa->execute();
    
    $insert=$pdo->prepare("insert into tbl_invoice(mesa,customer_name,order_date,subtotal,total,user)values(:mesa,:cust,:orderdate,:subtotal,:total,:user)");
    $insert->bindParam(':cust',$customer_name);
    $insert->bindParam(':orderdate',$order_date);
    $insert->bindParam(':total',$total);
    $insert->bindParam(':mesa',$mesa);
    $insert->bindParam(':subtotal',$sub_total);
     $insert->bindParam(':user',$idUser);
    //$insert->bindParam(':iva',$iva);
    $insert->execute();


    }
    
    //2nd  insert query for tbl_invoice_details
    $invoice_id=$pdo->lastInsertId();
    if($invoice_id!=null){
                
        for($i=0 ; $i<count($arr_productid) ; $i++){


        //if($arr_stock[$i]==0){

            //$rem_qty=0;
            //$rem_qty =$arr_stock[$i]; 
            
            $rem_qty = $arr_stock[$i]-$arr_qty[$i];
            
            
            //$rem_qty=$rem_qty;
             $update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");

             $update->execute();

         //}

    //update detalhes da conta onde fica o nome e id invoice
     $stmt = mysqli_query($mysqli,"UPDATE client_order_detalhes SET 
                                invoice_id='$invoice_id' 
                                 where id = '$id_client_order'");         
         


         $insert=$pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,total,t_iva,order_date) values(:invid,:pid,:pname,:qty,:price,:total,:t_iva,:orderdate)");

         $insert->bindParam(':invid',$invoice_id);
         $insert->bindParam(':pid', $arr_productid[$i]);
         $insert->bindParam(':pname',$arr_productname[$i]);
         $insert->bindParam(':qty',$arr_qty[$i]);
         $insert->bindParam(':price',$arr_price[$i]);
         $insert->bindParam(':total',$arr_total[$i]);
         $insert->bindParam(':orderdate',$order_date);
         //$insert->bindParam(':t_sub',$arr_t_sub[$i]);
         // Verificar se o valor de IVA é válido, se não for, definir como 0
         $iva_value = isset($arr_t_iva[$i]) && is_numeric($arr_t_iva[$i]) ? $arr_t_iva[$i] : 0;
         $insert->bindParam(':t_iva', $iva_value);
        


         //$insert->bindParam(":cate",$arr_cate[$i]);
         $insert->execute();


     }        
   //  echo"success fully created order";    
     //Balcao o seu codigo é 55
   if($mid==55){
        //echo "Hello";
   // <a href="pagar.php?id='.$id.'&op=det&max='.$max.'" class="small-box-footer"><img src="../images/icons8-request_money.png"> '.$rows->total.' MT</a>
    header('location:pagar.php?id=55&op=det&max=0'.$invoice_id.'');
   }else{
        header('location:mesa.php');     
   }
     
 }

}

  include_once 'cabecalho_user.php';   

?>
<!-- Estilos para os cards de produtos -->
<style>
    .product-category {
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .product-category h4 {
        background-color: #f4f4f4;
        padding: 8px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        color: #333;
    }
    .product-card {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
        margin-bottom: 15px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        cursor: pointer;
        height: 120px;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        border-color: #3498db;
    }
    .product-name {
        font-weight: bold;
        margin-bottom: 5px;
        height: 19px;
        overflow: hidden;
        font-size: 14px;
    }
    .product-price {
        color: #e74c3c;
        font-size: 16px;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .product-stock {
        color: #7f8c8d;
        font-size: 12px;
        margin-bottom: 10px;
    }
    .btn-add-product {
        width: 100%;
        background-color: #27ae60;
        border-color: #27ae60;
    }
    .btn-add-product:hover {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }
    .product-cards-container {
        margin-bottom: 10px;
    }
    /* Destacar produtos na tabela */
    #producttable tbody tr:hover {
        background-color: #f5f5f5;
    }
    /* Estilo para os painéis */
    .panel-primary > .panel-heading {
        background-color: #3498db;
        color: white;
    }
    .panel-success > .panel-heading {
        background-color: #27ae60;
        color: white;
    }
    .panel-body {
        padding: 10px;
    }
    .panel {
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        margin-bottom: 20px;
    }
    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .col-xs-6 {
            width: 50%;
        }
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Caixa <img src="../images/icons8-shopping_cart.png"> <?php echo $codemesa; ?> 
            <small></small>
            Nome do cliente: <?php echo $mid; ?>
        </h1>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-4">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-percent"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">IVA (16%)</span>
                        <span class="info-box-number" id="header-iva">0.00 MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Subtotal</span>
                        <span class="info-box-number" id="header-subtotal">0.00 MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number" id="header-total">0.00 MT</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
            <form action="" method="post" name="">

                <!-- <div class="box-header with-border">
                    <small>Clique nos cards de produtos para adicionar ao pedido</small>
                </div> -->
                <!-- /.box-header -->
                <!-- form start -->
                <input type="hidden" name="txt_mesa" value="<?php echo $codemesa; ?>">
                <div class="box-body">

                    <!--MIZ-->
                    <div class="col-md-6">
                        <div class="form-group">                       
                            <div class="input-group">
                                <input type="hidden" class="form-control" name="txtcustomer" value="<?php echo $Nome;?>" required>
                                <input type="hidden" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd" >
                                <input type="hidden" class="form-control pull-right" id="datepicker" name="txtmesa" value="1">
                            </div>
                        </div>
                    </div>
                </div> <!-- this is for customer and date -->
                <div class="box-body">
                    <!-- Layout de duas colunas -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Área de produtos (lado esquerdo) -->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Produtos Disponíveis</h3>
                                </div>
                                <div class="panel-body" style="max-height: 600px; overflow-y: auto;">
                                    <!-- Campo de pesquisa para produtos -->
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="product-search" placeholder="Pesquisar produto por nome ou categoria...">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="product-selection">
                                        <?php
                                        // Consulta para buscar produtos com estoque
                                        $product_query = "SELECT * FROM tbl_product WHERE pstock > 0 ORDER BY pname";
                                        $product_result = mysqli_query($mysqli, $product_query);
                                        
                                        if (mysqli_num_rows($product_result) > 0) {
                                            // Armazenar todos os produtos em um array
                                            $all_products = array();
                                            while ($product = mysqli_fetch_assoc($product_result)) {
                                                $all_products[] = $product;
                                            }
                                            
                                            // Total de produtos e produtos por página
                                            $total_products = count($all_products);
                                            $products_per_page = 6;
                                            $total_pages = ceil($total_products / $products_per_page);
                                            
                                            echo '<div class="row product-cards-container">';
                                            
                                            // Exibir apenas os primeiros 6 produtos inicialmente
                                            $display_count = min($products_per_page, $total_products);
                                            for ($i = 0; $i < $display_count; $i++) {
                                                $product = $all_products[$i];
                                                echo '<div class="col-md-4 col-sm-4 col-xs-4 product-item" data-page="1">';
                                                echo '<div class="product-card" ';
                                                echo 'data-id="' . $product['pid'] . '" ';
                                                echo 'data-name="' . htmlspecialchars($product['pname'], ENT_QUOTES) . '" ';
                                                echo 'data-price="' . $product['saleprice'] . '" ';
                                                echo 'data-stock="' . $product['pstock'] . '" ';
                                                echo 'data-category="' . htmlspecialchars(isset($product['pcategory']) ? $product['pcategory'] : 'Sem Categoria', ENT_QUOTES) . '" ';
                                                echo 'data-iva="' . (isset($product['iva']) ? $product['iva'] : '16') . '">';
                                                echo '<div class="product-name">' . $product['pname'] . '</div>';
                                                echo '<div class="product-price">' . number_format($product['saleprice'], 2) . ' MT</div>';
                                                echo '<div class="product-stock">Stock: ' . $product['pstock'] . '</div>';
                                                echo '<button type="button" class="btn btn-success btn-sm btn-add-product">Add</button>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                            
                                            // Adicionar os produtos restantes com display:none
                                            for ($i = $products_per_page; $i < $total_products; $i++) {
                                                $product = $all_products[$i];
                                                $page = ceil(($i + 1) / $products_per_page);
                                                echo '<div class="col-md-4 col-sm-4 col-xs-4 product-item" data-page="' . $page . '" style="display:none;">';
                                                echo '<div class="product-card" ';
                                                echo 'data-id="' . $product['pid'] . '" ';
                                                echo 'data-name="' . htmlspecialchars($product['pname'], ENT_QUOTES) . '" ';
                                                echo 'data-price="' . $product['saleprice'] . '" ';
                                                echo 'data-stock="' . $product['pstock'] . '" ';
                                                echo 'data-category="' . htmlspecialchars(isset($product['pcategory']) ? $product['pcategory'] : 'Sem Categoria', ENT_QUOTES) . '" ';
                                                echo 'data-iva="' . (isset($product['iva']) ? $product['iva'] : '16') . '">';
                                                echo '<div class="product-name">' . $product['pname'] . '</div>';
                                                echo '<div class="product-price">' . number_format($product['saleprice'], 2) . ' MT</div>';
                                                echo '<div class="product-stock">Stock: ' . $product['pstock'] . '</div>';
                                                echo '<button type="button" class="btn btn-success btn-sm btn-add-product">Add</button>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                            
                                            echo '</div>';
                                            
                                            // Adicionar controles de paginação se houver mais de uma página
                                            if ($total_pages > 1) {
                                                echo '<div class="product-pagination text-center" style="margin-top: 15px;">';
                                                echo '<button type="button" class="btn btn-default btn-prev" disabled><i class="fa fa-chevron-left"></i> Anterior</button>';
                                                echo '<span class="pagination-info" style="margin: 0 15px;">Página <span class="current-page">1</span> de ' . $total_pages . '</span>';
                                                echo '<button type="button" class="btn btn-default btn-next">Próximo <i class="fa fa-chevron-right"></i></button>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="alert alert-warning">Não foram encontrados produtos com estoque disponível.</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Tabela de itens selecionados (lado direito) -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Itens Selecionados</h3>
                                </div>
                                <div class="panel-body">
                                    <div style="overflow-x:auto;" > 
                                        <table class="table table-bordered table-striped" id="producttable">
                                            <thead>
                                                <tr>
                                                    <th width="5px">Ação</th>
                                                    <th>Produto</th>
                                                    <!-- <th>Categoria</th> -->
                                                    <!-- <th>Iva</th> -->
                                                    <th>Stock</th>
                                                    <th>Preço</th>
                                                    <th>Unidade</th>
                                                    <th>Quantidade</th>
                                                    <th>Subtotal</th>
                                                    <th class="hidden">T.Iva</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Itens adicionados aparecerão aqui -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- this for table -->

                <div class="box-body">
                    <div class="col-md-6">
                        <!-- Espaço para informações adicionais no lado esquerdo se necessário -->
                    </div>
                    <div class="col-md-6" id="corpo" hidden>
                        <div class="form-group">
                            <label>Iva (16%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                   MT
                                </div>
                                <input type="text" class="form-control txtiva" name="txtiva" id="txtiva" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subtotal </label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>
                                <input type="text" class="form-control" name="txt_subtotal" required readonly id="txt_subtotal">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>
                                <input type="number" class="form-control" name="txttotal" id="txttotal" required readonly>
                            </div>
                        </div>
                   
                 
                </div>
            </div><!-- tax dis. etc -->
            <hr>
            <div align="center">
                <input type="submit" name="btnsaveorder" value="Processar" class="btn btn-info">
            </div>
            <hr>
        </form>
    </div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>


    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });


    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    })
    
    
    
    $(document).ready(function(){
        // Variáveis para controle de paginação
        var currentPage = 1;
        var totalPages = $(".product-pagination").length > 0 ? parseInt($(".pagination-info").text().split(" de ")[1]) : 1;
        
        // Função para mudar de página
        function changePage(page) {
            // Esconder todos os produtos
            $(".product-item").hide();
            
            // Mostrar apenas os produtos da página atual
            $(".product-item[data-page='" + page + "']").show();
            
            // Atualizar o número da página atual
            $(".current-page").text(page);
            
            // Atualizar estado dos botões de navegação
            $(".btn-prev").prop("disabled", page === 1);
            $(".btn-next").prop("disabled", page === totalPages);
            
            // Atualizar a página atual
            currentPage = page;
        }
        
        // Eventos para os botões de navegação
        $(".btn-prev").click(function() {
            if (currentPage > 1) {
                changePage(currentPage - 1);
            }
        });
        
        $(".btn-next").click(function() {
            if (currentPage < totalPages) {
                changePage(currentPage + 1);
            }
        });
        
        // Função de pesquisa de produtos
        $("#product-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            
            if (value === "") {
                // Se a pesquisa estiver vazia, voltar para a visualização normal paginada
                changePage(1);
                return;
            }
            
            // Mostrar todos os produtos que correspondem à pesquisa, independente da página
            $(".product-item").each(function() {
                var productCard = $(this).find(".product-card");
                var productName = productCard.data("name").toLowerCase();
                var productCategory = productCard.data("category") ? productCard.data("category").toLowerCase() : "";
                var matchesSearch = productName.indexOf(value) > -1 || productCategory.indexOf(value) > -1;
                
                $(this).toggle(matchesSearch);
            });
            
            // Desabilitar os botões de navegação durante a pesquisa
            $(".btn-prev, .btn-next").prop("disabled", true);
        });
        
        // Função para adicionar produto à tabela quando clicar no card
        $(document).on('click', '.btn-add-product', function(){
            try {
                var productCard = $(this).closest('.product-card');
                var productId = productCard.data('id');
                var productName = productCard.data('name');
                var productPrice = productCard.data('price');
                var productStock = productCard.data('stock');
                var productCategory = productCard.data('category');
                var productIva = productCard.data('iva');
                
                console.log('Clicou no produto:', productName, 'ID:', productId, 'Preço:', productPrice);
                
                if (!productId || !productName) {
                    alert('Dados do produto incompletos. Por favor, tente novamente.');
                    return;
                }
                
                // Verificar se a tabela tem tbody, caso não tenha, adicionar
                if ($('#producttable tbody').length === 0) {
                    $('#producttable').append('<tbody></tbody>');
                }
                
                // Verificar se o produto já está na tabela
                var exists = false;
                $('#producttable tbody tr').each(function() {
                    var existingId = $(this).find('.productid').val();
                    if (existingId == productId) {
                        // Incrementar quantidade
                        var qtyInput = $(this).find('.qty');
                        var currentQty = parseInt(qtyInput.val());
                        if (currentQty < productStock) {
                            qtyInput.val(currentQty + 1);
                            qtyInput.trigger('change');
                        } else {
                            alert("A quantidade máxima disponível em estoque foi atingida!");
                        }
                        exists = true;
                        return false;
                    }
                });
                
                if (!exists) {
                    // Adicionar nova linha na tabela
                    var html = '';
                    html += '<tr>';
                    html += '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>';
                    html += '<td>' + productName + '<input type="hidden" class="form-control pname" name="productname[]" value="' + productName + '" readonly><input type="hidden" class="productid" name="productid[]" value="' + productId + '"></td>';
                    // html += '<td><input type="text" class="form-control forma" name="forma[]" value="' + productCategory + '" readonly></td>';
                    // html += '<td><input type="text" class="form-control txtivas" name="iva[]" value="' + productIva + '" readonly id="txt_txtivas"></td>';
                    html += '<td><input type="number" class="form-control stock" name="stock[]" value="' + productStock + '" readonly style="width:70px;"></td>';
                    html += '<td><input type="text" class="form-control price" name="price[]" value="' + productPrice + '" readonly id="txt_price" style="width:80px;"></td>';
                    html += '<td><select class="form-control unidade" name="unidade[]" style="width:100px;"><option value="un">Unidade</option><option value="kg">Kg</option></select></td>';
                    html += '<td><input type="number" min="0.01" step="0.01" max="' + productStock + '" class="form-control qty" name="qty[]" value="1"></td>';
                    html += '<td><input type="text" class="form-control total" name="total[]" value="' + productPrice + '" readonly></td>';
                    html += '<td class="hidden"><input type="number" class="form-control totaliva" name="totaliva[]" value="' + (productPrice * productIva / 100) + '" readonly></td>';
                    
                    $('#producttable tbody').append(html);
                    console.log('Produto adicionado à tabela');
                    calculate(0,0);
                }
            } catch(e) {
                console.error('Erro ao adicionar produto:', e);
                alert('Erro ao adicionar produto: ' + e.message);
            }
        });
        
        // Inicializar os valores no cabeçalho quando a página carrega
        $(document).ready(function() {
            calculate(0,0);
        });
        
        // Remover produto da tabela
        $(document).on('click','.btnremove',function(){
            $(this).closest('tr').remove(); 
            calculate(0,0);
        });
        
        // Evento para mudar o tipo de input quando a unidade for alterada
        $(document).on('change', '.unidade', function(){
            var tr = $(this).closest('tr');
            var qtyInput = tr.find('.qty');
            var unidade = $(this).val();
            
            if(unidade === 'kg') {
                // Permitir valores decimais para kg
                qtyInput.attr('step', '0.01');
                qtyInput.attr('min', '0.01');
            } else {
                // Apenas números inteiros para unidades
                qtyInput.attr('step', '1');
                qtyInput.attr('min', '1');
                // Arredondar o valor se for decimal
                var currentVal = parseFloat(qtyInput.val());
                if(currentVal % 1 !== 0) {
                    qtyInput.val(Math.round(currentVal));
                }
            }
            
            // Recalcular o total
            qtyInput.trigger('change');
        });
        
        // Atualizar valores quando a quantidade mudar
        $(document).on('change keyup', '.qty', function(){
            var quantity = $(this);
            var tr = $(this).parent().parent(); 
            
            if((quantity.val()-0) > (tr.find(".stock").val()-0)){
                alert("A quantidade a ser vendida não se encontra disponível no Stock");
                quantity.val(1);
            }
            
            tr.find(".total").val(quantity.val() * tr.find(".price").val());
            tr.find('.totaliva').val(tr.find(".txtivas").val() * tr.find(".total").val() / 100);
            calculate(0,0);
        });

    // Fim do document.ready


// Atualizar valores quando a quantidade mudar no delegate
$(document).ready(function() {
    $("#producttable").delegate(".qty", "keyup change", function() {
        var quantity = $(this);
        var tr = $(this).parent().parent(); 

        if((quantity.val()-0) > (tr.find(".stock").val()-0)) {
            alert("A quantidade a ser vendida não se encontra disponível no Stock");
            quantity.val(1);
        }
        
        tr.find(".total").val(quantity.val() * tr.find(".price").val());
        tr.find('.totaliva').val(tr.find(".txtivas").val() * tr.find(".total").val() / 100);
        calculate(0,0);
    });
});

        
        function calculate(dis,paid){
            var subtotal=0;     
            var iva=0;     
            var discount = dis;        
            var net_total=0;     
            var paid_amt=paid;     
            var due=0;     
            
            $(".total").each(function(){
                subtotal = subtotal+($(this).val()*1);     
            })
            
            $(".totaliva").each(function(){
                iva = iva+($(this).val()*1);     
            })
            
            net_total=subtotal+iva;
            net_total=net_total-discount;      
            due=net_total-paid_amt;      
            
            // Atualizar os campos do formulário
            $("#txt_subtotal").val(subtotal.toFixed(2)); 
            $("#txtdiscount").val(discount);
            $("#txttotal").val(net_total.toFixed(2));
            $("#txtdue").val(due.toFixed(2));
            $("#txtiva").val(iva.toFixed(2));
            
            // Atualizar os valores no cabeçalho
            $("#header-iva").text(iva.toFixed(2) + " MT");
            $("#header-subtotal").text(subtotal.toFixed(2) + " MT");
            $("#header-total").text(net_total.toFixed(2) + " MT");
        }
     

     
     $("#txtdiscount").keyup(function(){
        var discount = $(this).val();
        calculate(discount,0);


    }) 

     $("#txtpaid").keyup(function(){
        var paid = $(this).val();  
        var discount = $("#txtdiscount").val();
        calculate(discount,paid);

    })        



 });
    
    
</script>


<?php

// Verificar se existe um caixa aberto antes de permitir criar pedidos
$caixa_aberto = existeCaixaAberto($idUser, $mysqli);
if (!$caixa_aberto) {
    echo '<script>
    alert("É necessário abrir o caixa antes de realizar vendas!");
    window.location.href = "abrir_caixa.php";
    </script>';
    exit;
}

include_once 'footer.php';
/*}else{
    $insere=$mysqli->prepare("INSERT INTO tbl_saidas  (tbl_saidas.idproduto, tbl_saidas.stock_inicial) 
                        SELECT tbl_product.pid, tbl_product.pstock FROM tbl_product");
    if(!$insere->execute()){
        echo "<script>alert('Erros')</script>";
    }else{
    $update=mysqli_query($mysqli,"UPDATE tbl_saidas SET data='$data' WHERE data = '0000-00-00'");
    echo '<meta http-equiv="refresh" content="0;url=mesa.php">';
    }   
}*/

?>