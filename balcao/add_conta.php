<?php

include_once'../dbconnect.php';

session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="Balconista"){


    header('location:../index.php');
}

$codemesa=$_GET['m'];
$id=$_GET['id'];

$select=$pdo->prepare("select * from tbl_invoice where invoice_id =(select MAX(invoice_id) from tbl_invoice where invoice_id=$id)");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];
$order_date=date('Y-m-d',strtotime($row['order_date']));
$total_anterior=$row['total'];
$subtotal_anterior=$row['subtotal'];


function fill_product($pdo){
    $output='';
    $select=$pdo->prepare("select * from tbl_product ORDER BY pname asc"); 
    $select->execute();
    
    $result=$select->fetchAll();
    
    foreach($result as $row){
        $output.='<option value="'.$row["pid"].'">'.$row["pid"].'</option>';    
    }    
    return $output;   
}


if(isset($_POST['btnsaveorder'])){

    $customer_name=$_POST['txtcustomer'];
    $order_date=date('Y-m-d',strtotime($_POST['orderdate']));
    $total          =$_POST['txttotal'];
    $subtotal       =$_POST['txt_subtotal'];
    $totalacumulado =$_POST['totalacumulado'];
    $subtotalacumulado =$_POST['totalacumulado2'];
    
    $mesa           =$_POST['txt_mesa'];
    $mesa1          =$_POST['txtmesa'];

    ////////////////////////////////
    $arr_productid  =$_POST['productid'];
    $arr_productname=$_POST['productname'];
    $arr_stock      =$_POST['stock'];
    $arr_qty        =$_POST['qty'];
    $arr_price      =$_POST['price'];
    $arr_total      =$_POST['total'];
    $arr_cate       =$_POST['categaria'];
    //$arr_t_sub      =$_POST['Subtotal'];
    $arr_t_iva      =$_POST['totaliva'];
    
    
    //actualiza a tabela
    $soma=$total+$totalacumulado;
    $somasub=$subtotal+$subtotalacumulado;

    $insert_mesa=$pdo->prepare("UPDATE tbl_invoice SET total=:total,subtotal=:subtotal where invoice_id=$id");
    $insert_mesa->bindParam(":total",$soma);
    $insert_mesa->bindParam(":subtotal",$somasub);
    $insert_mesa->execute();
    
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
       $insert->bindParam(':orderdate',$txt_order_date);
       //$insert->bindParam(':t_sub',$arr_t_sub[$i]);
       $insert->bindParam(':t_iva',$arr_t_iva[$i]);
       $insert->execute();
     }        

     header('location:mesa.php');     

 }




}


if($_SESSION['role']=="Admin"){


 include_once'../header.php';  
}else{

  include_once'cabecalho_user.php';   
}



?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
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

                <div class="box-header with-border">
                    <h3 class="box-title">Adicionar a conta anterior</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <input type="hidden" name="txt_mesa" value="<?php echo $codemesa; ?>">
                <div class="box-body">

                    <!--User-->
                    <div class="col-md-6">
                        <div class="form-group">                       
                            <div class="input-group">
                                <input type="hidden" class="form-control" name="txtcustomer" value="<?php echo $_SESSION['username']?>" required>
                            </div>
                        </div>
                    </div>

                    <!--DATA-->
                    <div class="col-md-6">
                        <div class="form-group">                           
                            <div class="input-group date">                        
                                <input type="hidden" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd" >
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>


                    <!--ESTADO - MESA-->
                    <div class="col-md-6">
                        <div class="form-group">                       
                            <div class="input-group date">
                                <input type="hidden" class="form-control pull-right" id="datepicker" name="txtmesa" value="1">
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
                                    <th width="5px">
                                     <button type="button" name="add" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button>
                                     </th>
                                <th>Produto</th>
                                <th>N.comercial</th>
                                <th>Forma</th>
                                <th>Iva</th>
                                <th>Dosagem</th>
                                <th>Stock</th>
                                <th>Preço</th>
                                <th>Qtd a dar</th>
                                <th>Subtotal</th>
                                <th>T.Iva</th>                                      
                         </thead>
                     </table></div>

                 </div>

             </div><!-- this for table -->

             <div class="box-body">
                <div class="col-md-6">
                </div>
                <div class="col-md-6" >
                    <div class="form-group" style="background-color: seagreen; padding: 1px;">
                        <label>Subtotal actual</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-usd"></i>
                            </div>

                            <input type="text" class="form-control" name="txt_subtotal" id="txt_subtotal" required readonly>
                        </div>
                    </div>
                    <div class="form-group" style="background-color: seagreen; padding: 1px;">
                        <label>Total actual</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-usd"></i>
                            </div>

                            <input type="text" class="form-control" name="txttotal" id="txttotal" required readonly>
                        </div>
                    </div>
                    <hr >
                     <div class="form-group "style="background-color: gray; padding: 1px;" >
                        <label>Subtotal anterior</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-usd"></i>
                            </div>
                            <input type="text" class="form-control" name="totalacumulado2" id="totalacumulado2" value="<?php echo $subtotal_anterior?>" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="background-color: gray; padding: 1px;">
                        <label>Total anterior</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-usd"></i>
                            </div>
                            <input type="text" class="form-control" name="totalacumulado" id="totalacumulado" value="<?php echo $total_anterior?>" readonly>
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

        $(document).on('click','.btnadd',function(){

            var html='';
            html+='<tr>';
            html+='<td><input type="hidden" class="form-control pname" name="productname[]" readonly><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>'; 
            html+='<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">Select Option</option><?php echo fill_product($pdo); ?> </select></td>';
            html+='<td><input type="text" class="form-control comercial" name="comercial[]" readonly></td>';
            html+='<td><input type="text" class="form-control forma" name="forma[]" readonly></td>';
            html+='<td><input type="text" class="form-control txtivas" name="iva[]" readonly id="txt_txtivas"></td>';
            html+='<td><input type="text" class="form-control d" name="d[]" readonly></td>';
            html+='<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
            html+='<td><input type="text" class="form-control price" name="price[]" readonly id="txt_price"></td>';
            html+='<td><input type="number" min="1" class="form-control qty" name="qty[]" ></td>';
            html+='<td><input type="text" class="form-control total" name="total[]" readonly></td>';
            html+='<td><input type="text" class="form-control totaliva" name="totaliva[]" readonly></td>';
            

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
         tr.find(".comercial").val(data["nome_comerc"]);
         tr.find(".forma").val(data["pcategory"]);
         tr.find(".txtivas").val(data["iva"]);
         tr.find(".d").val(data["dosagem"]);
         tr.find(".stock").val(data["pstock"]);
         tr.find(".price").val(data["saleprice"]);
         tr.find(".pcategoria").val(data["pcategory"]); 
         tr.find(".qty").val(1);

         tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 

         //iva de cada produto
         tr.find('.totaliva').val(tr.find(".txtivas").val()*tr.find(".total").val());

         //total de iva + produto
         tr.find('.Subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));
         calculate(0,0); 
     }   
 })   
    })    




    }) // btnadd end here    

        
        $(document).on('click','.btnremove',function(){

            $(this).closest('tr').remove(); 
            calculate(0,0);
            $("#txtpaid").val(0);

     }) // btnremove end here  
        

       /* $("#producttable").delegate(".qty","keyup change" ,function(){

          var quantity = $(this);
          var tr = $(this).parent().parent(); 

        //quantity.val(0);
        
        tr.find(".total").val(quantity.val() *  tr.find(".price").val());
        calculate(0,0);

        
        
        }) */
        $("#producttable").delegate(".qty","keyup change" ,function(){

          var quantity = $(this);
          var tr = $(this).parent().parent(); 

        //quantity.val(0);        
        tr.find(".total").val(quantity.val() *  tr.find(".price").val());

       
        //total com iva
        tr.find('.Subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));

        //iva combrado
        tr.find('.totaliva').val(tr.find(".txtivas").val()*tr.find(".total").val());

        //$('#txt_subtotal').val(parseFloat(tr.find(".total").val())+parseFloat(tr.find(".totaliva").val()));

        calculate(0,0);
                
        
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
            due=net_total-paid_amt;         


            $("#txt_subtotal").val(subtotal.toFixed(2)); 
            //$("#txtsubtotal").val(subtotal.toFixed(2)); 
            //$("#txttax").val(tax.toFixed(2));   
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
