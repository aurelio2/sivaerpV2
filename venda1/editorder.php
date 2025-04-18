<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
include_once 'funcoes_caixa.php';
error_reporting(0);

function fill_product($pdo,$pid){
    $output='';
    $select=$pdo->prepare("select * from tbl_product order by pname asc"); 
    $select->execute();
    $result=$select->fetchAll();
    
    foreach($result as $row){
        $output.='<option value="'.$row["pid"].'"';
        if($pid==$row['pid']){
            $output.='selected';    
        }
        $output.='>'.$row["pid"].'-'.$row["pname"].'</option>'; 
    }    
    return $output;   
}

$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_invoice where invoice_id =$id and user='$idUser'");
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];

// Usar a data do caixa aberto em vez da data do pedido
$data_caixa = getDataCaixaAberto($idUser, $mysqli);
$order_date = $data_caixa;

$total=$row['total'];
$subtotal=$row['subtotal'];

$codemesa=$row['mesa'];

// Verificar se existe um caixa aberto
$caixa_aberto = existeCaixaAberto($idUser, $mysqli);
if (!$caixa_aberto) {
    echo '<script>
    alert("É necessário abrir o caixa antes de editar vendas!");
    window.location.href = "abrir_caixa";
    </script>';
    exit;
}

$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id =$id");
$select->execute();
$row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['btnupdateorder'])) {
    try {
        $txt_customer_name = $_POST['txtcustomer'];
        $txt_order_date = date('Y-m-d',strtotime($_POST['orderdate']));
        $txt_total = $_POST['txttotal'];
        $txt_subtotal = $_POST['txt_subtotal'];
        $mesa_text = $_POST['txt_mesa'];

        $arr_productid = isset($_POST['productid']) ? $_POST['productid'] : array();
        $arr_productname = isset($_POST['productname']) ? $_POST['productname'] : array();
        $arr_stock = isset($_POST['stock']) ? $_POST['stock'] : array();
        $arr_qty = isset($_POST['qty']) ? $_POST['qty'] : array();
        $arr_price = isset($_POST['price']) ? $_POST['price'] : array();
        $arr_total = isset($_POST['total']) ? $_POST['total'] : array();
        $arr_t_iva = isset($_POST['totaliva']) ? $_POST['totaliva'] : array();
        
        // Restore stock for removed products
        foreach($row_invoice_details as $item_invoice_details) {
            $updateproduct = $pdo->prepare("UPDATE tbl_product SET pstock = pstock + :qty WHERE pid = :pid");
            $updateproduct->bindParam(':qty', $item_invoice_details['qty']);
            $updateproduct->bindParam(':pid', $item_invoice_details['product_id']);
            $updateproduct->execute();
        }    
        
        // Delete existing invoice details
        $delete_invoice_details = $pdo->prepare("DELETE FROM tbl_invoice_details WHERE invoice_id = :id");
        $delete_invoice_details->bindParam(':id', $id);
        $delete_invoice_details->execute();    
        
        // Update invoice header
        $update_invoice = $pdo->prepare("UPDATE tbl_invoice SET mesa = :mesa, customer_name = :cust, order_date = :orderdate, total = :total, subtotal = :subtotal WHERE invoice_id = :id");
        $update_invoice->bindParam(':mesa', $mesa_text);
        $update_invoice->bindParam(':cust', $txt_customer_name);
        $update_invoice->bindParam(':orderdate', $txt_order_date);
        $update_invoice->bindParam(':total', $txt_total);
        $update_invoice->bindParam(':subtotal', $txt_subtotal);
        $update_invoice->bindParam(':id', $id);
        $update_invoice->execute();

        // Insert new invoice details
        if (!empty($arr_productid)) {
            for($i = 0; $i < count($arr_productid); $i++) {
                // Update stock for each product
                $selectpdt = $pdo->prepare("SELECT pstock FROM tbl_product WHERE pid = :pid");
                $selectpdt->bindParam(':pid', $arr_productid[$i]);
                $selectpdt->execute();
                $rowpdt = $selectpdt->fetch(PDO::FETCH_OBJ);
                
                if ($rowpdt) {
                    $new_stock = $rowpdt->pstock - $arr_qty[$i];
                    if ($new_stock >= 0) {
                        $update = $pdo->prepare("UPDATE tbl_product SET pstock = :stock WHERE pid = :pid");
                        $update->bindParam(':stock', $new_stock);
                        $update->bindParam(':pid', $arr_productid[$i]);
                        $update->execute();

                        // Insert invoice detail
                        $insert = $pdo->prepare("INSERT INTO tbl_invoice_details (invoice_id, product_id, product_name, qty, price, total, t_iva, order_date) VALUES (:invid, :pid, :pname, :qty, :price, :total, :t_iva, :orderdate)");
                        $insert->bindParam(':invid', $id);
                        $insert->bindParam(':pid', $arr_productid[$i]);
                        $insert->bindParam(':pname', $arr_productname[$i]);
                        $insert->bindParam(':qty', $arr_qty[$i]);
                        $insert->bindParam(':price', $arr_price[$i]);
                        $insert->bindParam(':total', $arr_total[$i]);
                        $insert->bindParam(':t_iva', $arr_t_iva[$i]);
                        $insert->bindParam(':orderdate', $txt_order_date);
                        $insert->execute();
                    }
                }
            }
        }
        
        echo "<script>alert('Atualizado com sucesso o pedido');</script>";
        // Redirect after successful update
        echo "<script>window.location.href='editorder?id=" . $id . "';</script>";
        exit;
        
    } catch (Exception $e) {
        // Log error and show user-friendly message
        error_log($e->getMessage());
        echo "<script>alert('Erro ao atualizar o pedido. Por favor, tente novamente.');</script>";
        echo "<script>window.location.href='editorder?id=" . $id . "';</script>";
        exit;
    }
}


if (isset($_POST['btncancelar'])) {
    // Update mesa status
    $stmt = mysqli_query($mysqli,"UPDATE tbl_mesa SET status='0' WHERE cod_mesa = '$codemesa'");
    
    if (!$stmt) {
        echo "<script>alert('Erro ao cancelar a venda!');</script>";
    }
    
    // Redirect using JavaScript to ensure it works
    echo "<script>window.location.href='mesa';</script>";
    exit;
}

  include_once'cabecalho_user.php';   

// Incluir os estilos para os cards de produtos
include_once 'add_styles.php';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Actualizar venda MESA: <?php  echo $codemesa; ?>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> SIVAERP</a></li>
            <li class="active">SIVAERP</li>
        </ol>
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

        <div class="box box-default">
            <br>
             <form action="" method="post">
            <p><b>Note:</b><span style="color:red;">Para cancelar a conta, primeiro clique no bota <span style="color:black;">X</span> na lista da conta para apagar os produtos, depois clique no botão cancelar!</span> 
                <button type="submit" name="btncancelar" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove">CANCELAR</span></button> </p>
            <input type="hidden" name="txtmesa" value="<?php echo $codemesa; ?>">  
           

        </form>
            <form action="" method="post" name="">
                <input type="hidden" name="txt_mesa" value="<?php echo $codemesa; ?>">

                <div class="box-header with-border">
                    <h3 class="box-title">Actualizar Venda</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                
                                <input type="hidden" class="form-control" name="txtcustomer" value="<?php echo $customer_name;?>" required>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group date">
                                
                                <input type="hidden" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo $order_date;?>" >
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                </div> <!-- this is for customer and date -->

                <div class="box-body">
                    <!-- Layout de duas colunas -->
                    <div class="row">
                    
                        
                        <div class="col-md-12">
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
                                                    <th>Quantidade</th>
                                                    <th>Subtotal</th>
                                                    <th class="hidden">T.Iva</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                            foreach($row_invoice_details as $item_invoice_details){
                                                $select=$pdo->prepare("select * from tbl_product where pid ='{$item_invoice_details['product_id']}'");
                                                $select->execute();
                                                $row_product=$select->fetch(PDO::FETCH_ASSOC); 
                                                $cat = $row_product['pcategory'];
                                            ?>
                                                <tr>
                                                    <td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>
                                                    <td>
                                                        <?php echo $row_product['pname']; ?>
                                                        <input type="hidden" class="form-control pname" name="productname[]" value="<?php echo $row_product['pname']; ?>" readonly>
                                                        <input type="hidden" class="productid" name="productid[]" value="<?php echo $item_invoice_details['product_id']; ?>">
                                                        <input type="hidden" class="form-control txtivas" name="iva[]" readonly id="txt_txtivas" value="<?php echo $row_product['iva']; ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control stock" name="stock[]" value="<?php echo $row_product['pstock']; ?>" readonly></td>
                                                    <td><input type="text" class="form-control price" name="price[]" value="<?php echo $row_product['saleprice']; ?>" readonly></td>
                                                    <td><input type="number" min="1" max="<?php echo $row_product['pstock']; ?>" class="form-control qty" name="qty[]" value="<?php echo $item_invoice_details['qty']; ?>"></td>
                                                    <td><input type="text" class="form-control total" name="total[]" value="<?php echo $row_product['saleprice']*$item_invoice_details['qty']; ?>" readonly></td>
                                                    <td class="hidden"><input type="text" class="form-control totaliva" name="totaliva[]" value="<?php echo $item_invoice_details['t_iva']; ?>" readonly></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- this for table -->

        <div class="box-body">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                     <div class="form-group hidden">
                        <label>Iva (16%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                   MT
                                </div>
                        <input type="text" class="form-control txtiva" name="txtiva" id="txtiva" required readonly>
                            </div>
                        </div>
                        <div class="form-group" hidden>
                        <label>Subtotal </label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                MT
                            </div>
                            <input type="number" class="form-control" name="txt_subtotal" required readonly id="txt_subtotal" value="<?php echo $subtotal; ?>">
                        </div>
                    </div>
                <div class="form-group" hidden>
                    <label>Total</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            MT
                        </div>

                        <input type="text" class="form-control" value="<?php echo $total?>" name="txttotal" id="txttotal" required readonly>
                    </div>
                </div>


            </div>



        </div><!-- tax dis. etc -->

        <hr>

        <div align="center">

            <input type="submit" name="btnupdateorder" value="ACTUALIZAR" class="btn btn-warning">
			<a href="add?id=<?php echo $id;?>&m=<?php echo $codemesa;?>"><button type="button" name="add" value="" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"> ADICIONAR</span></button></a>
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
                    html += '<td><input type="text" class="form-control stock" name="stock[]" value="' + productStock + '" readonly></td>';
                    html += '<td><input type="text" class="form-control price" name="price[]" value="' + productPrice + '" readonly id="txt_price"></td>';
                    html += '<td><input type="number" min="1" max="' + productStock + '" class="form-control qty" name="qty[]" value="1"></td>';
                    html += '<td><input type="text" class="form-control total" name="total[]" value="' + productPrice + '" readonly></td>';
                    html += '<td class="hidden"><input type="text" class="form-control totaliva" name="totaliva[]" value="' + (productPrice * productIva / 100) + '" readonly></td>';
                    
                    $('#producttable tbody').append(html);
                    console.log('Produto adicionado à tabela');
                }
                
                // Recalcular totais
                calculate(0,0);
            } catch(e) {
                console.error('Erro ao adicionar produto:', e);
                alert('Erro ao adicionar produto: ' + e.message);
            }
        });
        
        // Manter o suporte ao select2 para compatibilidade
        $('.productidedit').select2();
        
        $(".productidedit").on('change', function(e){
            var productid = this.value;
            var tr=$(this).parent().parent();  
            $.ajax({
                url:"getproduct.php",
                method:"get",
                data:{id:productid},
                success:function(data){
                    console.log(data); 
                    tr.find(".pname").val(data["pname"]);
                    tr.find(".stock").val(data["pstock"]);
                    tr.find(".price").val(data["saleprice"]);
                    tr.find(".txtivas").val(data["iva"]);
                    tr.find(".qty").val(1);
                    tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val()); 
                    
                    //iva de cada produto
                    tr.find('.totaliva').val(parseFloat(tr.find(".txtivas").val())*parseFloat(tr.find(".total").val()));
                    
                    calculate(0,0); 
                }
            });
        });


     // Inicializar os valores no cabeçalho quando a página carrega
     $(document).ready(function() {
        calculate(0,0);
     });
     
     $(document).on('click','.btnremove',function(){
        var tr = $(this).closest('tr');
        var product_id = tr.find('.productid').val();
        var qty = tr.find('.qty').val();

        // Remove the row from UI
        tr.remove();
        
        // Recalculate totals
        calculate(0,0);
        
        // Reset paid and due
        $("#txtpaid").val("");
        $("#txtdue").val("");

        // Save changes by triggering form submit
        $("#btnupdateorder").click();
     }) // btnremove end here  


    /* $("#producttable").delegate(".qty","keyup change" ,function(){
       
        var quantity = $(this);
        var tr = $(this).parent().parent(); 
        
        if((quantity.val()-0)>(tr.find(".stock").val()-0) ){
        
        quantity.val(1);
        
         tr.find(".total").val(quantity.val() *  tr.find(".price").val());
        calculate(0,0);
       }else{
           
           tr.find(".total").val(quantity.val() *  tr.find(".price").val());
           calculate(0,0);
       }    
        
        
        
    })   */
    $("#producttable").delegate(".qty","keyup change" ,function(){

          var quantity = $(this);
          var tr = $(this).parent().parent(); 

        //quantity.val(0);
        
        // tr.find(".total").val(quantity.val() *  tr.find(".price").val());
        // //iva de cada produto
        //  //tr.find('.totaliva').val(parseFloat(tr.find(".txtivas").val())*parseFloat(tr.find(".total").val()));
        // tr.find('.totaliva').val( tr.find(".txtivas").val() * tr.find(".total").val());

        //  //total de iva + produto
        // //tr.find('.Subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));

        // calculate(0,0);
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

        var totalIva=$('.totaliva').val();
            
            $(".total").each(function(){

                subtotal = subtotal+($(this).val()*1); 
            })  

            $(".totaliva").each(function(){
                iva = iva+($(this).val()*1);
            })

        net_total=subtotal+iva;
        //net_total=subtotal;  //50+1000 =1050
        net_total=net_total-discount;   
        due=net_total-paid_amt; 
        var t=totalIva+totalIva;    

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

include_once'footer.php';

?>