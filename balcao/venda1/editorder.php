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

$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_invoice where invoice_id =$id");
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];
$order_date=date('Y-m-d',strtotime($row['order_date']));
$total=$row['total'];
$codemesa=$row['mesa'];

$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id =$id");
$select->execute();
$row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['btnupdateorder'])){
    $txt_customer_name=$_POST['txtcustomer'];
    $txt_order_date=date('Y-m-d',strtotime($_POST['orderdate']));
    $txt_total=$_POST['txttotal'];
    $mesa_text=$_POST['txt_mesa'];


    ////////////////////////////////
    
    $arr_productid=$_POST['productid'];
    $arr_productname=$_POST['productname'];
    $arr_stock=$_POST['stock'];
    $arr_qty=$_POST['qty'];
    $arr_price=$_POST['price'];
    $arr_total=$_POST['total'];
    //$arr_categoria=$_POST['categary'];

    
    foreach($row_invoice_details as $item_invoice_details){

        $updateproduct=$pdo->prepare("update tbl_product set pstock=pstock+".$item_invoice_details['qty']." where pid='".$item_invoice_details['product_id']."'");
        $updateproduct->execute();
    }    
    
    $delete_invoice_details=$pdo->prepare("delete from tbl_invoice_details where invoice_id=$id");
    $delete_invoice_details->execute();    
    
    // 4) Write update query for tbl_invoice table data.
    $update_invoice=$pdo->prepare("update tbl_invoice set mesa=:mesa, customer_name=:cust,order_date=:orderdate,total=:total where invoice_id=$id");
    $update_invoice->bindParam(':cust',$txt_customer_name);
    $update_invoice->bindParam(':orderdate',$txt_order_date);
    $update_invoice->bindParam(':total',$txt_total);
    $update_invoice->bindParam(':mesa',$mesa_text);
    $update_invoice->execute();
    
    $invoice_id=$pdo->lastInsertId();
    if($invoice_id!=null){

        for($i=0 ; $i<count($arr_productid) ; $i++){
            $selectpdt=$pdo->prepare("select * from tbl_product where pid='".$arr_productid[$i]."'");
            $selectpdt->execute();

            while($rowpdt=$selectpdt->fetch(PDO::FETCH_OBJ)){
            $db_stock[$i]=$rowpdt->pstock;
                if($db_stock[$i]==0){

                $rem_qty[$i]=0;

            }else{
            $rem_qty = $db_stock[$i]-$arr_qty[$i];                
               $update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");
               $update->execute();
           }
       }    


       $insert=$pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,total, order_date) values(:invid,:pid,:pname,:qty,:price,:total,:orderdate)");
       $insert->bindParam(':invid',$id);
       $insert->bindParam(':pid', $arr_productid[$i]);
       $insert->bindParam(':pname',$arr_productname[$i]);
       $insert->bindParam(':qty',$arr_qty[$i]);
       $insert->bindParam(':price',$arr_price[$i]);
       $insert->bindParam(':total',$arr_total[$i]);
       $insert->bindParam(':orderdate',$txt_order_date);
       $insert->execute();

   }        
   header('location:mesa.php');     
}    
}

  include_once'headeruser.php';   

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Vender
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
            <form action="" method="post" name="">
                <input type="hidden" name="txt_mesa" value="<?php echo $codemesa; ?>">

                <div class="box-header with-border">
                    <h3 class="box-title">Venda</h3>
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
                    <div class="col-md-12">
                     <div style="overflow-x:auto;" > 
                      <table class="table table-bordered" id="producttable"  >

                        <thead>
                                <th>Produto</th>
                                <th>Stock</th>
                                <th>Preço</th>
                                <th>Enter Quantity</th>
                                <th>Total</th>
                       </thead>

                       <?php
                       foreach($row_invoice_details as $item_invoice_details){
                        $select=$pdo->prepare("select * from tbl_product where pid ='{$item_invoice_details['product_id']}'");
                        $select->execute();
                        $row_product=$select->fetch(PDO::FETCH_ASSOC); 
						$cat = $row_product['pcategory'];

                        ?>
						
                        <tr>
                            <?php
							
                            echo'<td><input type="hidden" class="form-control pname" name="productname[]" value="'.$row_product['pname'].'">';
                            if($cat=='Cozinha'){
								echo'<input  readonly="readonly" class="form-control" name="productid[]" style="width: 250px"; value="'.$item_invoice_details['product_id'].'"></td>';
							}else{
								echo'<select class="form-control productidedit" name="productid[]" style="width: 250px";><option value="">Select Option</option>'.fill_product($pdo,$item_invoice_details['product_id']).' </select></td>';
							}
							
							echo'<td><input type="text" class="form-control stock" name="stock[]" value="'.$row_product['pstock'].'" readonly></td>';
                            echo'<td><input type="text" class="form-control price" name="price[]" value="'.$row_product['saleprice'].'" readonly></td>';
                            if($cat=='Cozinha'){
								echo'<td><input type="number" min="1" class="form-control qty" name="qty[]" value="'.$item_invoice_details['qty'].'" readonly></td>';
                            }else{
								echo'<td><input type="number" min="1" class="form-control qty" name="qty[]" value="'.$item_invoice_details['qty'].'" ></td>';
							}
							echo'<td><input type="text" class="form-control total" name="total[]" value="'.$row_product['saleprice']*$item_invoice_details['qty'].'" readonly></td>';
                            if($cat=='Cozinha'){
								echo "";
							}else{
							echo'<td><center><but ton type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>';  
							}
                            
							?>      
                        </tr>   

                    <?php } ?>
                </table></div>
            </div>
        </div><!-- this for table -->

        <div class="box-body">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Total</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-usd"></i>
                        </div>

                        <input type="text" class="form-control" value="<?php echo $total?>" name="txttotal" id="txttotal" required readonly>
                    </div>
                </div>


            </div>



        </div><!-- tax dis. etc -->

        <hr>

        <div align="center">

            <input type="submit" name="btnupdateorder" value="ACTUALIZAR" class="btn btn-warning">
			<a href="add_conta.php?id=<?php echo $id;?>&m=<?php echo $codemesa;?>"><button type="button" name="add" value="" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"> ADICIONAR</span></button></a>
		
		<br><br>NOTA: <i>Ao clicar no botão actualizar, deverá imprimir novamente a lista dos pedidos da cozinha para esta mesa. 
		<br>O Sistema irá imprimir todos os pedidos confirmados para este cliente<i>
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

            url:"getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){

         console.log(data); 
         tr.find(".pname").val(data["pname"]);
         tr.find(".stock").val(data["pstock"]);
         tr.find(".price").val(data["saleprice"]);
         //tr.find(".pcategoria").val(data["pcategory"]);

         tr.find(".qty").val(1);
         tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 
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

            url:"getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){

         console.log(data); 
         tr.find(".pname").val(data["pname"]);
         tr.find(".stock").val(data["pstock"]);
         tr.find(".price").val(data["saleprice"]); 
         tr.find(".pcategoria").val(data["pcategory"]);         
         tr.find(".qty").val(1);
         tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 
         calculate(0,0); 
         $("#txtpaid").val("");    

     }   
 })   
    })    




    }) // btnadd end here    


     $(document).on('click','.btnremove',function(){

        $(this).closest('tr').remove(); 
        calculate(0,0);
        
        $("#txtpaid").val("");

     }) // btnremove end here  


     $("#producttable").delegate(".qty","keyup change" ,function(){

      var quantity = $(this);
      var tr=$(this).parent().parent(); 
      $("#txtpaid").val("");


             //quantity.val(1);

             tr.find(".total").val(quantity.val() *  tr.find(".price").val());
             calculate(0,0);




         })    


     function calculate(dis,paid){

        var subtotal=0;
        var tax=0;
        var discount = dis;     
        var net_total=0;
        var paid_amt=paid;
        var due=0;


        $(".total").each(function(){

            subtotal = subtotal+($(this).val()*1);    

        })

        tax=0.05*subtotal;
        net_total=subtotal;  //50+1000 =1050
        net_total=net_total-discount;   
        due=net_total-paid_amt;         


        $("#txtsubtotal").val(subtotal.toFixed(2)); 
        $("#txttax").val(tax.toFixed(2));   
        $("#txttotal").val(net_total.toFixed(2));
        $("#txtdiscount").val(discount);
        $("#txtdue").val(due.toFixed(2));



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