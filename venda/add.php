<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
include_once 'funcoes_caixa.php';


$codemesa=$_GET['m'];
$id=$_GET['id'];

// Verificar se existe um caixa aberto
$caixa_aberto = existeCaixaAberto($idUser, $mysqli);
if (!$caixa_aberto) {
    echo '<script>
    alert("É necessário abrir o caixa antes de adicionar produtos!");
    window.location.href = "abrir_caixa.php";
    </script>';
    exit;
}

// Obter a data do caixa aberto
$data_caixa = getDataCaixaAberto($idUser, $mysqli);

$select=$pdo->prepare("select * from tbl_invoice where invoice_id =(select MAX(invoice_id) from tbl_invoice where invoice_id=$id)");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];
// Usar a data do caixa aberto em vez da data do pedido
$order_date = $data_caixa;
$total_anterior=$row['total'];
$subtotal_anterior=$row['subtotal'];

function fill_product($pdo){

    $output='';
    
    $select=$pdo->prepare("select * from tbl_product ORDER BY pid asc"); 
    $select->execute();
    $result=$select->fetchAll();
    
    foreach($result as $row){

        $output.='<option value="'.$row["pid"].'">'.$row["codebar"].'-'.$row["pname"].'</option>';    

    }    
    
    return $output;   
    
}


if(isset($_POST['btnsaveorder'])){

    $customer_name=$_POST['txtcustomer'];
    // Usar a data do caixa aberto em vez da data do formulário
    $order_date = getDataCaixaAberto($idUser, $mysqli);
    //$total          =$_POST['txttotal'];
    $subtotal       =$_POST['txt_subtotal'];
    $totalacumulado =$_POST['totalacumulado'];
    $subtotalacumulado =$_POST['totalacumulado2'];
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
    //$arr_cate=$_POST['categaria'];
    //$arr_t_sub=$_POST['Subtotal'];
    $arr_t_iva=$_POST['totaliva'];

    //actualiza a tabela
    $soma=$total+$totalacumulado;
    $somasub=$subtotal+$subtotalacumulado;

    $insert_mesa=$pdo->prepare("UPDATE tbl_invoice SET total=:total,subtotal=:subtotal where invoice_id=$id");
    $insert_mesa->bindParam(":total",$soma);
    $insert_mesa->bindParam(":subtotal",$somasub);
    $insert_mesa->execute();
    
    
      
    
    //2nd  insert query for tbl_invoice_details
    //$invoice_id=$pdo->lastInsertId();
    if($id!=null){
                
        for($i=0 ; $i<count($arr_productid) ; $i++){


        //if($arr_stock[$i]==0){

            //$rem_qty=0;
            //$rem_qty =$arr_stock[$i]; 
            
            $rem_qty = $arr_stock[$i]-$arr_qty[$i];
            
            
            //$rem_qty=$rem_qty;
             $update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");

             $update->execute();

         //}
         


         $insert=$pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,total,t_iva,order_date) values(:invid,:pid,:pname,:qty,:price,:total,:t_iva,:orderdate)");

         $insert->bindParam(':invid',$id);
         $insert->bindParam(':pid', $arr_productid[$i]);
         $insert->bindParam(':pname',$arr_productname[$i]);
         $insert->bindParam(':qty',$arr_qty[$i]);
         $insert->bindParam(':price',$arr_price[$i]);
         $insert->bindParam(':total',$arr_total[$i]);
         $insert->bindParam(':orderdate',$order_date);
         //$insert->bindParam(':t_sub',$arr_t_sub[$i]);
         $insert->bindParam(':t_iva',$arr_t_iva[$i]);


         //$insert->bindParam(":cate",$arr_cate[$i]);
         $insert->execute();


     }        
   //  echo"success fully created order";    
     header('location:mesa.php');     
 }


}



  include_once 'cabecalho_user.php';   

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Adicionar Produtos a Venda MESA: <?php  echo $codemesa; ?>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> SIVAERP</a></li>
            <li class="active">SIVAERP</li>
        </ol>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-2">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-percent"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">IVA (16%)</span>
                        <span class="info-box-number" id="header-iva">0.00 MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Subtotal</span>
                        <span class="info-box-number" id="header-subtotal">0.00 MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number" id="header-total">0.00 MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-gray">
                    <span class="info-box-icon"><i class="fa fa-history"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Subtotal anterior</span>
                        <span class="info-box-number"><?php echo $subtotal_anterior?> MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-gray">
                    <span class="info-box-icon"><i class="fa fa-history"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total anterior</span>
                        <span class="info-box-number"><?php echo $total_anterior?> MT</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <!-- Estilos para os cards de produtos -->
        <style>
            /* Estilos para os cards de produtos */
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
        </style>

        <div class="box box-warning">
            <form action="" method="post">
               
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6" hidden>
                            <div class="form-group">
                                <label>Nome do Cliente</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" name="txtcustomer" value="<?php echo $customer_name;?>" required>
                                </div>
                            </div>
                            <div class="form-group" hidden>
                                <label>Data</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" name="orderdate" value="<?php echo $order_date;?>" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" hidden>
                                <label>Mesa</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-table"></i>
                                    </div>
                                    <input type="text" class="form-control" name="txt_mesa" value="<?php echo $codemesa;?>" readonly>
                                    <input type="hidden" class="form-control" name="txtmesa" value="<?php echo $codemesa;?>" readonly>
                                    <input type="hidden" class="form-control" name="totalacumulado" value="<?php echo $total_anterior;?>" readonly>
                                    <input type="hidden" class="form-control" name="totalacumulado2" value="<?php echo $subtotal_anterior;?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                                    <th>Stock</th>
                                                    <th>Preço</th>
                                                    <th>Unidade</th>
                                                    <th>Quantidade</th>
                                                    <th>Subtotal</th>
                                                    <th class="hidden">T.Iva</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Os produtos serão adicionados aqui através dos cards -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6" id="corpo">
                            <div class="form-group " hidden>
                                <label>Iva (17%)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       MT
                                    </div>
                                    <input type="text" class="form-control txtiva" name="txtiva" id="txtiva" required readonly>
                                </div>
                            </div>
                            <div class="form-group" style="background-color: seagreen; padding: 1px;" hidden>
                                <label>Subtotal actual</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="text" class="form-control" name="txt_subtotal" id="txt_subtotal" required readonly>
                                </div>
                            </div>
                            <div class="form-group" style="background-color: seagreen; padding: 1px;" hidden>
                                <label>Total actual</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="text" class="form-control" name="txttotal" id="txttotal" required readonly>
                                </div>
                            </div>
                            <div class="form-group "style="background-color: gray; padding: 1px;" hidden>
                                <label>Subtotal anterior</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="text" class="form-control" name="totalacumulado2" id="totalacumulado2" value="<?php echo $subtotal_anterior?>" readonly>
                                </div>
                            </div>
                            <div class="form-group" style="background-color: gray; padding: 1px;" hidden>
                                <label>Total anterior</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="text" class="form-control" name="totalacumulado" id="totalacumulado" value="<?php echo $total_anterior?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div align="center">
                        <input type="submit" name="btnsaveorder" value="Processar" class="btn btn-info">
                    </div>
                    <hr>
                </form>
            </div>
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
        // Inicializar os valores no cabeçalho quando a página carrega
        calculate(0,0);
        
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
                    html += '<td><input type="number" class="form-control stock" name="stock[]" value="' + productStock + '" readonly style="width:70px;"></td>';
                    html += '<td><input type="text" class="form-control price" name="price[]" value="' + productPrice + '" readonly id="txt_price" style="width:80px;"></td>';
                    html += '<td><select class="form-control unidade" name="unidade[]" style="width:80px;"><option value="un">Unidade</option><option value="kg">Kg</option></select></td>';
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
        
        // Manter o suporte ao botão de adicionar para compatibilidade
        $(document).on('click','.btnadd',function(){
            var html='';
            html+='<tr>'; 
            html+='<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>'; 
            // html+='<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">Select Option</option><?php echo fill_product($pdo); ?> </select></td>';
            html+='<td><input type="number" class="form-control stock" name="stock[]" readonly></td>';
            html+='<td><input type="text" class="form-control price" name="price[]" readonly id="txt_price"></td>';
            html+='<td><input type="number" min="1" class="form-control qty" name="qty[]"></td>';
            html+='<td><input type="text" class="form-control total" name="total[]" readonly></td>';
            html+='<td class="hidden"><input type="number" class="form-control totaliva" name="totaliva[]" readonly></td>';
            $('#producttable').append(html);

            //Initialize Select2 Elements
            $('.productid').select2()

            $(".productid").on('change' , function(e){
                var productid = this.value;
                var tr=$(this).parent().parent();  
                $.ajax({
                    url:"getproduct.php",
                    method:"get",
                    data:{id:productid},
                    success:function(data){
                        tr.find(".pname").val(data["pname"]);
                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1);
                        tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 

                        //iva de cada produto
                        tr.find('.totaliva').val( tr.find(".txtivas").val() * tr.find(".total").val());

                        calculate(0,0);
                    }   
                })   
            })    
        }) // btnadd end here    

        
        $(document).on('click','.btnremove',function(){

            $(this).closest('tr').remove(); 
            calculate(0,0);
            $("#txtpaid").val(0);
         
             calculate(0,0);

     }) // btnremove end here  
        
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

       $("#producttable").delegate(".qty","keyup change" ,function(){

          var quantity = $(this);
          var tr = $(this).parent().parent(); 

        // //quantity.val(0);        
        // tr.find(".total").val(quantity.val() *  tr.find(".price").val());

       
        // //total com iva
        // //Number(tr.find('.Subtotal').val(Number(tr.find(".total").val())+Number(tr.find(".totaliva").val())));
        // tr.find('.totaliva').val( tr.find(".txtivas").val() * tr.find(".total").val());
        
        //iva combrado
        //tr.find('.totaliva').val(tr.find(".txtivas").val()*Number.parseFloat(tr.find(".total").val()));

        //$('#txt_subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));

        //calculate(0,0);
        if((quantity.val()-0)>(tr.find(".stock").val()-0) ){
       
       //swal.fire("WARNING!","SORRY! This much of quantity is not available","warning");
        alert("A quantidade a ser vendida não se encontra disponivel no Stock");
        quantity.val(1);
        
         
        tr.find(".total").val(quantity.val() *  tr.find(".price").val());

        //total com iva
        tr.find('.totaliva').val( tr.find(".txtivas").val() * tr.find(".total").val());
        calculate(0,0);
       }else{


        tr.find(".total").val(quantity.val() *  tr.find(".price").val());

        //total com iva
        tr.find('.totaliva').val( tr.find(".txtivas").val() * tr.find(".total").val());
        calculate(0,0);
       }    
                
        
    }) 

        
        function calculate(dis,paid){
            var subtotal=0;
            var iva=0;
            var discount = dis;     
            var net_total=0;
            var paid_amt=paid;
            var due=0;
            
            // Calcular o subtotal somando todos os valores da coluna total
            $(".total").each(function(){
                subtotal = parseFloat(subtotal) + parseFloat($(this).val() || 0); 
            });
            
            // Calcular o total de IVA somando todos os valores da coluna totaliva
            $(".totaliva").each(function(){
                iva = parseFloat(iva) + parseFloat($(this).val() || 0);
            });
            
            // Calcular o total geral
            net_total = subtotal + iva;
            net_total = net_total - discount;   
            due = net_total - paid_amt;

            // Atualizar os campos do formulário
            $("#txt_subtotal").val(subtotal.toFixed(2)); 
            $("#txttotal").val(net_total.toFixed(2));
            $("#txtdiscount").val(discount);
            $("#txtdue").val(due.toFixed(2));
            $("#txtiva").val(iva.toFixed(2));
            
            // Atualizar os valores no cabeçalho
            $("#header-iva").text(iva.toFixed(2) + " MT");
            $("#header-subtotal").text(subtotal.toFixed(2) + " MT");
            $("#header-total").text(net_total.toFixed(2) + " MT");
     }// function calculate end here 
     

     
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