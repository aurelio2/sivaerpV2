<?php

include_once'../connectdb.php';

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
    //echo '<script>alert("ola");</script>';
    //mesas
 $max=$_GET['max']; //invoid_id
$mid=$_GET['id']; // mesa


//invoice
//$id=$_GET['id'];*/
$select=$pdo->prepare("select * from tbl_invoice where invoice_id =(select MAX(invoice_id) from tbl_invoice where invoice_id=$max)");
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];
$order_date=date('Y-m-d',strtotime($row['order_date']));
$total=$row['total'];
$codemesa=$row['mesa'];

$select=$pdo->prepare("select * from tbl_invoice_details where  status = 0 and invoice_id =$max");
$select->execute();

$row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);

}

if(isset($_POST['btnupdateorder'])){


//Steps for btnupdateorder button.

// 1) Get values from text feilds and from array in variables.
    $txt_customer_name=$_POST['txtcustomer'];
    $txt_order_date=date('Y-m-d',strtotime($_POST['orderdate']));
    
    $txt_total=$_POST['txttotal'];
    $mesa_text=$_POST['txtmesa'];
    $txt_mesas=$_POST['txt_mesa'];

    //disc,pago,troco,forma de pagamento
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
    $array_estado =$_POST['estados'];


    

    $insert_mesa=$pdo->prepare("UPDATE tbl_mesa SET status=:estado where cod_mesa=$codemesa");
    $insert_mesa->bindParam(":estado",$mesa_text);
    $insert_mesa->execute();
    
// 2) Write update query for tbl_product stock.
    
    foreach($row_invoice_details as $item_invoice_details){

        $updateproduct=$pdo->prepare("update tbl_product set pstock=pstock+".$item_invoice_details['qty']." where pid='".$item_invoice_details['product_id']."'");
        $updateproduct->execute();
    }    
    
    
    
    
    
// 3) Write delete query for tbl_invoice_details table data where invoice_id =$id .
    
    
    $delete_invoice_details=$pdo->prepare("delete from tbl_invoice_details where invoice_id=$id");
    
    $delete_invoice_details->execute();    
    
    // 4) Write update query for tbl_invoice table data.
    /*$update_invoice=$pdo->prepare("update tbl_invoice set mesa=:mesa, customer_name=:cust,order_date=:orderdate,total=:total,discount=:disc,paid=:pago,due=:troco,payment_type=:forma_paga where invoice_id=$max");
    
    $update_invoice->bindParam(':cust',$txt_customer_name);
    $update_invoice->bindParam(':orderdate',$txt_order_date);
    $update_invoice->bindParam(':total',$txt_total);
    $update_invoice->bindParam(':mesa',$txt_mesas);
    $update_invoice->bindParam(':disc',$disconto_text);
    $update_invoice->bindParam(':pago',$pagar_text);
    $update_invoice->bindParam(':troco',$troco_text);
    $update_invoice->bindParam(':forma_paga',$forma_text);
    
    $update_invoice->execute(); */
    
    
    

    
    $invoice_id=$pdo->lastInsertId();
    if($invoice_id!=null){

        for($i=0 ; $i<count($arr_productid) ; $i++){

// 5) Write select query for tbl_product table to get out stock value.    

            $selectpdt=$pdo->prepare("select * from tbl_product where pid='".$arr_productid[$i]."'");
            $selectpdt->execute();

            while($rowpdt=$selectpdt->fetch(PDO::FETCH_OBJ)){

             $db_stock[$i]=$rowpdt->pstock;


             
              $rem_qty = $db_stock[$i]-$arr_qty[$i];
             if($db_stock[$i]==0){
                $db_stock[$i]=0;

                //$update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");

                //$update->execute();
            }else{


        // 6) Write update query for tbl_product table to update stock values.
               

               $update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");

               $update->execute();
           }

       }    

    // 7) Write insert query for tbl_invoice_details for insert new records.


       $insert=$pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,order_date) values(:invid,:pid,:pname,:qty,:price,:orderdate)");

       $insert->bindParam(':invid',$id);
       $insert->bindParam(':pid', $arr_productid[$i]);
       $insert->bindParam(':pname',$arr_productname[$i]);
       $insert->bindParam(':qty',$arr_qty[$i]);
       $insert->bindParam(':price',$arr_price[$i]);
       $insert->bindParam(':orderdate',$txt_order_date);

       

       $insert->execute();


       $update_print=$pdo->prepare("update tbl_invoice_details set status=:estado where invoice_id=$max");
       $update_print->bindParam(":estado",$array_estado[$id]);
       $update_print->execute();






   }        

   //  echo"success fully created order";    
   header('location:pagaw.php');     

}    






}






if($_SESSION['role']=="Admin"){


   include_once'header.php';  
}else{

  include_once'headeruser.php';   
}







?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Enviar para cozinha
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
            <form action="print.php?id=<?php echo $max; ?>" method="post" name="">
                <input type="hidden" name="txt_mesa" value="<?php echo $codemesa; ?>">
                <input type="hidden" name="idvenda" value="<?php echo $max; ?>">


                <div class="box-header with-border">
                    <h3 class="box-title"><img src="../images/icons8-tables.png"> <?php echo $codemesa;?></h3>
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
                    <!--Estado -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group date">
                                
                                <input type="hidden" class="form-control pull-right" name="estados" value="1" >
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
                            <tr>
                                <th>#</th>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Enter Quantity</th>
                                <th>Total</th>

                           </tr>

                       </thead>

                       <?php
                       foreach($row_invoice_details as $item_invoice_details){

                        $select=$pdo->prepare("select * from tbl_product where pid ='{$item_invoice_details['product_id']}' and pcategory='Cozinha'");
                        $select->execute();

                        $row_product=$select->fetch(PDO::FETCH_ASSOC); 

                        if($row_product>0){
                           

                        ?>
                        <tr>
                            <?php
                            echo'<td><input type="hidden" class="form-control pname" name="productname[]" value="'.$row_product['pname'].'" readonly></td>';

                            echo'<td><select class="form-control productidedit" name="productid[]" style="width: 250px";><option value="">Select Option</option>'.fill_product($pdo,$item_invoice_details['product_id']).' </select></td>';

                            echo'<td><input type="text" class="form-control price" name="price[]" value="'.$row_product['saleprice'].'" readonly></td>';
                            echo'<td><input type="number" min="1" class="form-control qty" name="qty[]" value="'.$item_invoice_details['qty'].'" readonly></td>';
                            echo'<td><input type="text" class="form-control total" name="total[]" value="'.$row_product['saleprice']*$item_invoice_details['qty'].'" readonly></td>';
                           



                            ?>      
                        </tr>   

                    <?php } 


                }

                    ?>

                </table></div>



            </div>



        </div><!-- this for table -->

        <hr>
        
        <div align="center">

            <input type="submit" name="btnupdateorder" value="Enviar" class="btn btn-success">

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

            url:"../gestor/getproduct.php",
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

            url:"../gestor/getproduct.php",
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
        //var tax=0;
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
        due=paid_amt-net_total;         


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