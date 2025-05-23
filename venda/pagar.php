<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
include_once 'funcoes_caixa.php';

$ref = $_GET['id'];
$idfuncionario = $idUser;
$data = getDataCaixaAberto($idUser, $mysqli);

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
        $output.='>'.$row["pname"].'</option>'; 
    }    
    return $output;   
}



if(($_GET['op']=='det')){

$max=$_GET['max']; //invoid_id
$mid=$_GET['id']; // mesa

$select=$pdo->prepare("select * from tbl_invoice where invoice_id =(select MAX(invoice_id) from tbl_invoice where invoice_id=$max)");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];
$order_date=date('Y-m-d',strtotime($row['order_date']));
$total=$row['total'];
$subtotal=$row['subtotal'];
$codemesa=$row['mesa'];

$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id =$max");
$select->execute();

$row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);

}

if(isset($_POST['btnupdateorder'])){

    $txt_customer_name=$_POST['txtcustomer'];
    $txt_order_date=date('Y-m-d',strtotime($_POST['orderdate']));
    
    $txt_total=$_POST['txttotal'];
   
    $txt_mesas=$_POST['txt_mesa'];
    $disconto_text=$_POST['txtdiscount'];
    $pagar_text=$_POST['txtpaid'];
    $troco_text=$_POST['txtdue'];
    $forma_text=$_POST['rb'];

    ////////////////////////////////
    
    $arr_productid=$_POST['productid'];
    $arr_productname=$_POST['productname'];
    $arr_stock=$_POST['stock'];
    $arr_qty=$_POST['qty'];
    $arr_price=$_POST['price'];
    $arr_total=$_POST['total'];

    
        
    foreach($row_invoice_details as $item_invoice_details){
        $updateproduct=$pdo->prepare("update tbl_product set pstock=pstock+".$item_invoice_details['qty']." where pid='".$item_invoice_details['product_id']."'");
        $updateproduct->execute();
    }      
    
    $update_invoice=$pdo->prepare("update tbl_invoice set discount=:disc,paid=:pago,due=:troco,payment_type=:forma_paga where invoice_id=$max");
    $update_invoice->bindParam(':cust',$txt_customer_name);
    $update_invoice->bindParam(':orderdate',$txt_order_date);
    $update_invoice->bindParam(':total',$txt_total);
    $update_invoice->bindParam(':mesa',$txt_mesas);
    $update_invoice->bindParam(':disc',$disconto_text);
    $update_invoice->bindParam(':pago',$pagar_text);
    $update_invoice->bindParam(':troco',$troco_text);
    $update_invoice->bindParam(':forma_paga',$forma_text);
    
    $update_invoice->execute(); 
    
    
    $invoice_id=$pdo->lastInsertId();
    if($invoice_id!=null){
        for($i=0 ; $i<count($arr_productid) ; $i++){
            $selectpdt=$pdo->prepare("select * from tbl_product where pid='".$arr_productid[$i]."'");
            $selectpdt->execute();

            while($rowpdt=$selectpdt->fetch(PDO::FETCH_OBJ)){
                $db_stock[$i]=$rowpdt->pstock;
              $rem_qty = $db_stock[$i]-$arr_qty[$i];
             if($db_stock[$i]==0){
                $db_stock[$i]=0;
            }else{
               $update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");
               $update->execute();
           }
       }    

       $insert=$pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,order_date) values(:invid,:pid,:pname,:qty,:price,:orderdate)");

       $insert->bindParam(':invid',$id);
       $insert->bindParam(':pid', $arr_productid[$i]);
       $insert->bindParam(':pname',$arr_productname[$i]);
       $insert->bindParam(':qty',$arr_qty[$i]);
       $insert->bindParam(':price',$arr_price[$i]);
       $insert->bindParam(':orderdate',$txt_order_date);
       $insert->execute();
   }        

   header('location:pagaw.php');     

    }    
}


  include_once'cabecalho_user.php';   







?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pagamento
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> SIVAERP</a></li>
            <li class="active">Pagamento</li>
        </ol>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-4">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-percent"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">IVA (16%)</span>
                        <span class="info-box-number" id="header-iva"><?php echo number_format($iva, 2); ?> MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Subtotal</span>
                        <span class="info-box-number" id="header-subtotal"><?php echo number_format($subtotal, 2); ?> MT</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number" id="header-total"><?php echo number_format($total, 2); ?> MT</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <script>
        // Inicializar os valores no cabeçalho quando a página carrega
        $(document).ready(function() {
            // Obter os valores dos campos
            var iva = document.getElementById('txtiva').value || '0.00';
            var subtotal = document.getElementById('txtsubtotal').value || '0.00';
            var total = document.getElementById('txttotal').value || '0.00';
            
            // Atualizar os valores no cabeçalho
            document.getElementById('header-iva').innerHTML = iva + ' MT';
            document.getElementById('header-subtotal').innerHTML = subtotal + ' MT';
            document.getElementById('header-total').innerHTML = total + ' MT';
            
            // Inicializar o cálculo
            calculate(0,0);
            
            // Adicionar um log para debug
            console.log('IVA: ' + iva + ', Subtotal: ' + subtotal + ', Total: ' + total);
        });
        </script>
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
            .payment-methods {
                margin-top: 15px;
            }
            .payment-option {
                margin-bottom: 10px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .payment-option label {
                font-weight: bold;
                color: #333;
            }
            .payment-input {
                margin-top: 5px;
            }
        </style>
        <div class="box box-warning">
            <form method="post" name="">
                <input type="hidden" name="txt_mesa" value="<?php echo $mid; ?>">
                <input type="hidden" name="idvenda" value="<?php echo $max; ?>">
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
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Tabela de itens do pedido -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Itens do Pedido</h3>
                                </div>
                                <div class="panel-body">
                                    <div style="overflow-x:auto;" > 
                                        <table class="table table-bordered table-striped" id="producttable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Produto</th>
                                                    <th>Preço</th>
                                                    <th>Qtd</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($row_invoice_details as $item_invoice_details){
                                                $select=$pdo->prepare("select * from tbl_product where pid ='{$item_invoice_details['product_id']}'");
                                                $select->execute();
                                                $row_product=$select->fetch(PDO::FETCH_ASSOC); 
                                            ?>
                                            <tr>
                                                <td><?php echo $item_invoice_details['product_id']; ?></td>
                                                <td>
                                                    <?php echo $row_product['pname']; ?>
                                                    <input type="hidden" class="form-control pname" name="productname[]" value="<?php echo $row_product['pname']; ?>" readonly>
                                                    <input type="hidden" class="form-control productid" name="productid[]" value="<?php echo $row_product['pid']; ?>">
                                                </td>
                                                <td>
                                                    <?php echo number_format($row_product['saleprice'], 2); ?> MT
                                                    <input type="hidden" class="form-control price" name="price[]" value="<?php echo $row_product['saleprice']; ?>" readonly>
                                                </td>
                                                <td>
                                                    <?php echo $item_invoice_details['qty']; ?>
                                                    <input type="hidden" class="form-control qty" name="qty[]" value="<?php echo $item_invoice_details['qty']; ?>">
                                                    <input type="hidden" class="form-control stock" name="stock[]" value="<?php echo $row_product['pstock']; ?>" readonly>
                                                    <input type="hidden" class="form-control txtivas" name="iva[]" readonly id="txt_txtivas" value="<?php echo $row_product['iva']; ?>">
                                                </td>
                                                <td>
                                                    <?php echo number_format($row_product['saleprice']*$item_invoice_details['qty'], 2); ?> MT
                                                    <input type="hidden" class="form-control total" name="total[]" value="<?php echo $row_product['saleprice']*$item_invoice_details['qty']; ?>" readonly>
                                                    <input type="hidden" class="form-control totaliva" name="totaliva[]" value="<?php echo $item_invoice_details['t_iva']; ?>" readonly>
                                                </td>
                                            </tr>   
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div><!-- this for table -->

        <div class="box-body">

            <div class="col-md-6">
                 <div class="form-group hidden">
                            <label>Disconto</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>
                 <input type="number" class="form-control" name="txtdiscount" id="txtdiscount" required value="0">
                            </div>
                        </div>


            </div>
            <div class="col-md-6">
                 <div class="form-group hidden">
                        <label>Iva (17%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                   MT
                                </div>
                        <input type="text" class="form-control txtiva" name="txtiva" id="txtiva" required readonly>
                            </div>
                        </div>
                <div class="form-group hidden">
                    <label>Subtotal</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            MT
                        </div>

                        <input type="text" class="form-control" value="<?php echo $subtotal?>" name="txtsubtotal" id="txtsubtotal" required readonly>
                    </div>
                </div>

                <div class="form-group" hidden>
                    <label>Total</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            MT
                        </div>

                        <input type="text" class="form-control" value="<?php echo $total?>" name="txttotal" id="txttotal"  readonly onblur="calcularTroco()" >
                    </div>
                </div>
                <!--Modal de pagamento Botao-->
                <div class="form-group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Pronto Pagamento <img src="../images/icons8-request_money.png" width="30px"></button>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dividaModal" data-whatever="@mdo">POS Pagamento <img src="../images/due-date.png" width="30px"></button>
                </div>
                <!--Total Oculto-->
                
                <div class="hidden">
                <div class="form-group">
                            <label>Dinheiro</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>
                                <input type="text" class="form-control " name="txtpaid"  id="txtpaid" required onblur="soma()" autocomplete="off">
                            </div>
                        </div>
              
                        <div class="form-group">
                            <label>Troco</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>
                                <input type="text" class="form-control" name="txtdue" id="txtdue" required readonly >
                            </div>

                        </div> 
                        </div>                      
                        <!-- radio -->
                        <!--<label>Metodo de Pagamento</label>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Dinheiro em mao" checked> Dinheiro
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="cartao"> Cartao
                            </label>
                          
                        </div>-->

                        <!--ESTADO - MESA-->
                    <div class="col-md-6">
                        <div class="form-group">                       
                            <div class="input-group date">
                                
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>



            </div>



        </div><!-- tax dis. etc -->

        <hr>

        <!--<div align="center">

            <input type="submit" name="btnupdateorder" value="Finalizar" class="btn btn-success">

        </div>-->

        <hr>

    </form>
    </div>
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<?php 
if (isset($_POST["btn_fechar"])) {
     //$divida = $_POST['cdivida'];
     $cash = $_POST['txtcash'];
     $mpesa = $_POST['txtmpesa'];
     $emola = $_POST['txtemola'];
     $pos = $_POST['txtpos'];
     $troco = $_POST['txttroco'];
     $recebido = $_POST['txtvalorreceber'];

     $mesa_text=$_POST['txtmesa'];

     $data = getDataCaixaAberto($idUser, $mysqli);

     
    $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$codemesa");
    $insert_mesa->bindParam(":estado",$mesa_text);
    $insert_mesa->execute();
        
    $stmt = "INSERT INTO tbl_control_payment VALUES 
        (NULL,'$max','$mpesa','$emola','$cash','$pos','$recebido','$troco','$data','$idfuncionario')";

    //pronto pagamento sem devida
    $stmt2 = "INSERT INTO tbl_devida VALUES 
        (NULL,'$max','0','0','$data','1','$idfuncionario')";
        mysqli_query($mysqli, $stmt2);


        //$update->execute();
        if (mysqli_query($mysqli, $stmt)) {
            echo '<script>
            alert("Obrigado, Pagamento Finalizado com sucesso!")
            </script>';
            echo '<meta http-equiv="refresh" content="1; url=pagaw.php?id='.$max.'">';
        } else {
            echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
        }

}

//pospagmento
if (isset($_POST["btn_processar"])) {
     $divida = $_POST['cdivida'];

     $numero=$_POST['cnumero'];

     $data = date('Y-m-d');
     $mesa_text=$_POST['txtmesapos'];
     
    $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$codemesa");
    $insert_mesa->bindParam(":estado",$mesa_text);
    $insert_mesa->execute();
        
    $stmt = "INSERT INTO tbl_devida VALUES 
        (NULL,'$max','$numero','$divida','$data','0','$idfuncionario')";

        //$update->execute();
        if (mysqli_query($mysqli, $stmt)) {
            echo '<script>
            alert("Obrigado, Divida registado com sucesso!")
            </script>';
            echo '<meta http-equiv="refresh" content="1; url=ticketDivida.php?id='.$max.'">';
        } else {
            echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
        }

}

?>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <h1>Total: <span style="color:red;"><?php echo $total; ?> MT</span></h1>

       <h2>Troco: <span id="troco" style="color: green;">0.00 MT</span></h2>
      </div>
     <form method="post">
    <input type="hidden" class="form-control pull-right" name="txtmesa" value="0">
    <input type="hidden" class="form-control pull-right" name="txttroco" id="trocoInput">
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">CASH:</label>
        <div class="options">
            <input type="checkbox" name="payment-option" id="cash-option" value="cash">
        </div>
        <input type="number" class="form-control" placeholder="CASH" name="txtcash" id="txtcash" style="display: none;" value="0">
         <label for="recipient-name" class="col-form-label">Valor a Receber:</label>
    <input type="number" class="form-control" placeholder="Valor a Receber" name="txtvalorreceber" id="txtvalorreceber" required>
    </div>

    <div class="form-group">
        <label for="recipient-name" class="col-form-label">M-PESA:</label>
        <div class="options">
            <input type="checkbox" name="payment-option" id="mpesa-option" value="mpesa">
        </div>
        <input type="number" class="form-control" placeholder="M-PESA" name="txtmpesa" id="txtmpesa" style="display: none;" value="0">
    </div>
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">E-MOLA:</label>
        <div class="options">
            <input type="checkbox" name="payment-option" id="emola-option" value="emola">
        </div>
        <input type="number" class="form-control" placeholder="E-MOLA" name="txtemola" id="txtemola" style="display: none;" value="0">
    </div>
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">POS:</label>
        <div class="options">
            <input type="checkbox" name="payment-option" id="pos-option" value="pos">
        </div>
        <input type="number" class="form-control" placeholder="POS" name="txtpos" id="txtpos" style="display: none;" value="0">
    </div>
    <div class="form-group">
    <div id="numeroPessoa" style="display: none;">
        <label for="txtNumeroPessoa">Número da Pessoa:</label>
        <input type="text" id="txtNumeroPessoa" name="txtNumeroPessoa"  class="form-control" placeholder="Digite o número da pessoa" />
    </div>
    </div>
   
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="btn_fechar">Finalizar</button>
    </div>
</form>

  </div>
</div>
</div>

<div class="modal fade" id="dividaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <h1>Total: <span style="color:red;"><?php echo $total; ?> MT</span></h1>
       
      </div>
   
     <form method="post">
         <div class="hidden">
        <input type="text" class="form-control" name="cdivida" value="<?php echo round($total,0) ?>">
        <input type="hidden" class="form-control pull-right" name="txtmesapos" value="0">
    </div>
        <div class="form-group">
        <label for="recipient-name" class="col-form-label">Numero de Celular</label>
        <input type="number" class="form-control" placeholder="Numero do cliente" name="cnumero" required>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="btn_processar">Processar</button>
    </div>
</form>

  </div>
</div>
</div>
</section>
<!-- /.content -->
</div>
  

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

     $('.productidedit').select2()

     $(".productidedit").on('change' , function(e){

        var productid = this.value;
        var tr=$(this).parent().parent();  
        $.ajax({

            url:"../getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){

         //console.log(data); 
         tr.find(".pname").val(data["pname"]);
         tr.find(".comercial").val(data["nome_comerc"]);
         //tr.find(".d").val(data["dosagem"]);
         tr.find(".iva").val(data["iva"]);

         tr.find(".stock").val(data["pstock"]);
         tr.find(".price").val(data["saleprice"]); 
         tr.find(".qty").val(1);
         tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 

          //total com iva
        tr.find('.Subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));

        //iva combrado
        tr.find('.totaliva').val(tr.find(".txtivas").val()*tr.find(".total").val());

        //calculoDinheiro();
        //$("txtdue").val(("#txttotal").val(net_total.toFixed(2))-("#txtpaid").val());
        
         calculate(0,0); 
         $("#txtpaid").val("");
     }   
 })   
    })      




     $(document).on('click','.btnadd',function(){

        var html='';
        html+='<tr>';
        
        html+='<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';
        
        html+='<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">Select Option</option><?php echo fill_product($pdo,''); ?> </select></td>';
        html+='<td><input type="text" class="form-control comercial" name="comercial[]" readonly></td>';
            html+='<td><input type="text" class="form-control d" name="d[]" readonly></td>';
        html+='<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
        html+='<td><input type="text" class="form-control price" name="price[]" readonly></td>';
        html+='<td><input type="number" min="1" class="form-control qty" name="qty[]" ></td>';
        html+='<td><input type="text" class="form-control total" name="total[]" readonly></td>';

        html+='<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>'; 
        
        $('#producttable').append(html);
        

      //Initialize Select2 Elements
      $('.productid').select2()

      $(".productid").on('change' , function(e){

        var productid = this.value;
        var tr=$(this).parent().parent();  
        $.ajax({

            url:"../getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){

         //console.log(data); 
         tr.find(".pname").val(data["pname"]);
         tr.find(".stock").val(data["pstock"]);
         tr.find(".price").val(data["saleprice"]); 
         tr.find(".qty").val(1);
         tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 
         calculate(0,0); 
         //$("#txtpaid").val(""); 
        //$('#txtdue').val("");



     }   
 })   
    })    




    }) // btnadd end here    


     $(document).on('click','.btnremove',function(){

        $(this).closest('tr').remove(); 
        calculate(0,0);
        
        $("#txtpaid").val("");
        $('#txtdue').val("");

     }) // btnremove end here  


     $("#producttable").delegate(".qty","keyup change" ,function(){

        var quantity = $(this);
        var tr=$(this).parent().parent(); 
        $("#txtpaid").val("");
        $('#txtdue').val("");

        //quantity.val(1);

        tr.find(".total").val(quantity.val() *  tr.find(".price").val());

              //total com iva
        tr.find('.Subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));

        //iva combrado
        tr.find('.totaliva').val(tr.find(".txtivas").val()*tr.find(".total").val());

        calculate(0,0);

        //calculoDinheiro();
        //$("txtdue").val(("#txttotal").val(net_total.toFixed(2))-("#txtpaid").val());

             
        //$('#txtdue').val(parseFloat(('#txttotal').val())-parseFloat($('#txtpaid').val()));

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
           

            net_total=(subtotal+iva);  //50+1000 =1050
            //net_total=net_total-discount;   
            due=paid-net_total;   
            var t=totalIva+totalIva;    

            // Atualizar os campos do formulário
            $("#txtsubtotal").val(subtotal.toFixed(2)); 
            $("#txttotal").val(net_total.toFixed(2));
            $("#txtdiscount").val(discount);
            $("#txtiva").val(iva.toFixed(2));
            
            // Atualizar os valores no cabeçalho diretamente
            document.getElementById('header-iva').innerHTML = iva.toFixed(2) + " MT";
            document.getElementById('header-subtotal').innerHTML = subtotal.toFixed(2) + " MT";
            document.getElementById('header-total').innerHTML = net_total.toFixed(2) + " MT";
            
            // Log para debug
            console.log('Calculate - IVA: ' + iva.toFixed(2) + ', Subtotal: ' + subtotal.toFixed(2) + ', Total: ' + net_total.toFixed(2));
     }// function calculate end here 

     /*$("#txtdiscount").keyup(function(){
        var discount = $(this).val();
        calculate(discount,0);


    }) */

     /*$("#txtpaid").keyup(function(){
        var paid = $(this).val();  
        var discount = $("#txtdiscount").val();
        calculate(discount,paid);

    })*/

    /*function calcularTroco(){
        var apagar = parseInt(document.getElementById('txttotal').value,10);
        var dinheiro = parseInt(document.getElementById('txtpaid').value,10);
        //document.getElementById('txtdue').innerHTML = apagar + dinheiro; 
    }*/
    /*function click(){
        var apagar = document.querySelector("#txttotal").value;
        var dinheiro = document.querySelector("#txtpaid").value;

        var result = parseFloat(apagar) + parseFloat(dinheiro);
        document.querySelector("#txtdue").innerHTML=result;
        console.log(result);
    }*/

    function calc(txtdue,txtpaid,txttotal){
         document.form.txtdue.value=parseFloat(document.form.txttotal.value)*
        parseFloat(document.form.txtpaid.value);
    }



 });


</script>

<script>
/*$(document).ready(function() {
    // Function to show/hide input fields based on checked checkboxes
    $("input[name='payment-option']").change(function() {
        var selectedOptions = $("input[name='payment-option']:checked");

        // Hide all input fields
        $("input[id^='txt']").hide();

        // Show the input fields corresponding to the checked checkboxes
        selectedOptions.each(function() {
            var selectedOption = $(this).val();
            $("#txt" + selectedOption).show();
        });
    });


    // Function to calculate and update the troco when the "Valor a Receber" field changes
     $("#txtvalorreceber").on("input", function() {
        var total = <?php echo $total; ?>;
        var valorReceber = parseFloat($(this).val());

        if (!isNaN(valorReceber)) {
            var troco = valorReceber - total;
            if (troco >= 0) {
                $("#txtvalorreceber").val(valorReceber);
                $("#troco").html(troco.toFixed(2) + " MT</span>");
                 $("#trocoInput").val(troco);
            } else {
                $("#troco").html("Valor a receber deve ser igual ou maior que o total.");
                // Desmarque a opção "CASH"
                $("#cash-option").prop("checked", false);
                $("#txtcash").hide();
            }
        } else {
            $("#troco").html("Insira um valor válido no campo 'Valor a Receber'.");
        }
    });
});*/
   $(document).ready(function() {
    // Function to show/hide input fields based on checked checkboxes
    $("input[name='payment-option']").change(function() {
        var selectedOptions = $("input[name='payment-option']:checked");

        // Hide all input fields
        $("input[id^='txt']").hide();

        // Show the input fields corresponding to the checked checkboxes
         // Show the input fields corresponding to the checked checkboxes
        selectedOptions.each(function() {
            var selectedOption = $(this).val();
            $("#txt" + selectedOption).show();
        });

        // Check if "CASH" is selected
        if ($("#cash-option").is(":checked")) {
            // Set the "Valor a Receber" and troco to 0
            $("#txtvalorreceber").val("0");
            $("#trocoInput").val("0");
        } else {
            // Set the "Valor a Receber" and troco to 0 for other options
            $("#txtvalorreceber").val("0");
            $("#trocoInput").val("0");
        }

        // Hide the troco display
        $("#trocoDisplay").hide();
    });

    // Function to calculate and update the troco in real-time when the "Valor a Receber" field changes
    $("#txtvalorreceber").on("input", function () {
        var total = <?php echo $total; ?>;
        var valorReceber = parseFloat($(this).val());

        if ($(this).val() === "") {
            // Quando o campo está vazio
            $("#troco").html(""); // Limpa a exibição do troco
            $("#trocoInput").val(""); // Limpa o valor do troco no input
            $("#numeroPessoa").hide(); // Esconde o campo número da pessoa
            return; // Sai da função
        }

        if (!isNaN(valorReceber)) {
            var troco = valorReceber - total;
            if (troco >= 0) {
                // Valor recebido é suficiente ou maior que o total
                $("#troco").html(troco.toFixed(2) + " MT</span>"); // Exibe o troco
                $("#trocoInput").val(troco); // Define o valor do troco em trocoInput
                $("#numeroPessoa").hide(); // Esconde o campo número da pessoa
            } else if (valorReceber < total) {
                // Valor recebido é insuficiente
                $("#troco").html("Faltam " + Math.abs(troco.toFixed(2)) + " MT</span>"); // Exibe o valor faltante
                $("#trocoInput").val(""); // Não define troco para valor insuficiente
                $("#numeroPessoa").show(); // Mostra o campo número da pessoa
            }
        } else {
            // Quando o valor em "Valor a Receber" não é um número válido
            $("#troco").html("0.00 MT</span>"); // Mostra o troco como zero
            $("#trocoInput").val("0"); // Define o valor do troco em trocoInput como 0
            $("#numeroPessoa").hide(); // Esconde o campo número da pessoa
        }
    });

});
</script>




<script type="text/javascript">

function id(valor_campo)
{
    return document.getElementById(valor_campo);
}
function getValor(valor_campo)
{
    var valor = document.getElementById(valor_campo).value.replace( ',', '.');
    /*document.write("Valor: " + valor);*/
    return parseFloat( valor ) * 100;
}


function soma()
{
    // Calcular o troco
    var valorPago = parseFloat(document.getElementById('txtpaid').value) || 0;
    var valorTotal = parseFloat(document.getElementById('txttotal').value) || 0;
    var troco = valorPago - valorTotal;
    
    // Atualizar o campo de troco
    document.getElementById('txtdue').value = troco.toFixed(2);
    
    // Atualizar os valores no cabeçalho
    document.getElementById('header-iva').innerHTML = document.getElementById('txtiva').value + " MT";
    document.getElementById('header-subtotal').innerHTML = document.getElementById('txt_subtotal').value + " MT";
    document.getElementById('header-total').innerHTML = document.getElementById('txttotal').value + " MT";
}
</script>

<!--Modal de pagamento-->
<script type="text/javascript">
    $('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  
  
})
</script>
<?php
include_once 'footer.php';
?>


