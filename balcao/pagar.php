<?php

include_once'../dbconnect.php';

session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']==""){


    header('location:../index.php');
}

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
    $mesa_text=$_POST['txtmesa'];
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

    $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$codemesa");
    $insert_mesa->bindParam(":estado",$mesa_text);
    $insert_mesa->execute();
        
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

if($_SESSION['role']=="Admin"){


   include_once'header.php';  
}else{

  include_once'cabecalho_user.php';   
}







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
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
            <form action="pagaw.php?id=<?php $id; ?>" method="post" name="">
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
                    <div class="col-md-12">
                     <div style="overflow-x:auto;" > 
                      <table class="table table-bordered" id="producttable"  >

                        <thead>
                                <th>#</th>
                                <th>Produto</th>
                                <th>N.comercial</th>
                                <th>Iva</th>
                                <th>Stock</th>
                                <th>Preço</th>
                                <th>Qtd</th>
                                <th>Subtotal</th>
                                <th>T.Iva</th>  
                             
                       </thead>

                       <?php
                       foreach($row_invoice_details as $item_invoice_details){

                        $select=$pdo->prepare("select * from tbl_product where pid ='{$item_invoice_details['product_id']}'");
                        $select->execute();

                        $row_product=$select->fetch(PDO::FETCH_ASSOC); 


                        ?>
                        <tr>
                            <?php
                            echo'<td><input type="hidden" class="form-control pname" name="productname[]" value="'.$row_product['pname'].'" readonly></td>';

                            echo'<td><select class="form-control productidedit" name="productid[]" style="width: 250px"; readonly><option value="">Select Option</option>'.fill_product($pdo,$item_invoice_details['product_id']).' </select></td>';
                            echo '<td><input type="text" class="form-control comercial" name="comercial[]" value="'.$row_product['nome_comerc'].'" readonly></td>';
                            echo '<td><input type="text" class="form-control iva" name="iva[]" value="'.$row_product['iva'].'" readonly></td>';
                            echo'<td><input type="text" class="form-control stock" name="stock[]" value="'.$row_product['pstock'].'" readonly></td>';
                            echo'<td><input type="text" class="form-control price" name="price[]" value="'.$row_product['saleprice'].'" readonly></td>';
                            echo'<td><input type="number" min="1" class="form-control totaliva" name="totaliva[]" value="'.$item_invoice_details['qty'].'" readonly ></td>';

                            echo'<td><input type="text" class="form-control total" name="total[]" value="'.$row_product['saleprice']*$item_invoice_details['qty'].'" readonly></td>';
                            echo'<td><input type="number" min="1" class="form-control qty" name="qty[]" value="'.$item_invoice_details['t_iva'].'" readonly ></td>';
                            //echo'<td><input type="text" class="form-control total" name="total[]" value="'.$item_invoice_details['t_sub'].'" readonly></td>';
                            ?>      
                        </tr>   

                    <?php } ?>

                </table></div>
            </div>
        </div><!-- this for table -->

        <div class="box-body">

            <div class="col-md-6">
            	 <div class="form-group">
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
                <div class="form-group">
                    <label>Subtotal</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            MT
                        </div>

                        <input type="text" class="form-control" value="<?php echo $subtotal?>" name="txtsubtotal" id="txtsubtotal" required readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Total</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            MT
                        </div>

                        <input type="text" class="form-control" value="<?php echo $total?>" name="txttotal" id="txttotal" required readonly >
                    </div>
                </div>

                <div class="form-group">
                            <label>Dinheiro</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>

                                <input type="text" class="form-control" name="txtpaid"  id="txtpaid" min="1" required >
                            </div>
                           
                        </div>

                        <div class="form-group">
                            <label>Troco</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    MT
                                </div>
                                <input type="text" class="form-control" name="txtdue" id="txtdue" required readonly>
                            </div>

                        </div>
                       

                        <!-- radio -->
                        <label>Metodo de Pagamento</label>
                        <div class="form-group">

                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Dinheiro em mao" checked> Dinheiro
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="cartao"> Cartao
                            </label>
                          
                        </div>

                        <!--ESTADO - MESA-->
                    <div class="col-md-6">
                        <div class="form-group">                       
                            <div class="input-group date">
                                <input type="hidden" class="form-control pull-right" name="txtmesa" value="0">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>



            </div>



        </div><!-- tax dis. etc -->

        <hr>

        <div align="center">

            <input type="submit" name="btnupdateorder" value="Finalizar" class="btn btn-success">

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
        //$('#txtdue').val("");

     }) // btnremove end here  


     $("#producttable").delegate(".qty","keyup change" ,function(){

        var quantity = $(this);
        var tr=$(this).parent().parent(); 
        //$("#txtpaid").val("");
        //$('#txtdue').val("");

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
        var tax=0;
        var discount = dis;     
        var net_total=0;
        var paid_amt=paid;
        var due=0;


        var totalIva=$('.totaliva').val();
            
            $(".total").each(function(){

                subtotal = subtotal+($(this).val()*1); 
            })  

        net_total=subtotal+parseFloat(totalIva);
        //net_total=subtotal;  //50+1000 =1050
        net_total=net_total-discount;   
        due=paid_amt-net_total;         


        $("#txtsubtotal").val(subtotal.toFixed(2)); 
        //$("#txttax").val(tax.toFixed(2));   
        $("#txttotal").val(net_total.toFixed(2));
        //$("#txtdiscount").val(discount);
        //$("#txtdue").val(due.toFixed(2));

        //calcular troco
        //$("txtdue").val(("#txttotal").val(net_total.toFixed(2))-("#txtpaid").val());

         //calculoDinheiro();
     }// function calculate end here 

     $("#txtdiscount").keyup(function(){
        var discount = $(this).val();
        calculate(discount,0);


    }) 

     /*$("#txtpaid").keyup(function(){
        var paid = $(this).val();  
        var discount = $("#txtdiscount").val();
        calculate(discount,paid);

    })*/

    /*function calculoDinheiro(){
        alert("troco");

    }

    function clicar(){
        alert("OK");
    }*/





 });


</script>


<?php

include_once'footer.php';

?>