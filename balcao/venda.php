<?php

include_once'../dbconnect.php';

session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="admin"){


    header('location:../index.php');
}



function fill_product($pdo){

    $output='';
    
    $select=$pdo->prepare("select * from tbl_servico"); 
    $select->execute();
    
    $result=$select->fetchAll();
    
    foreach($result as $row){

        $output.='<option value="'.$row["cod_servico"].'">'.$row["corte"].'</option>';    

    }    
    
    return $output;   
    
}


if(isset($_POST['btnsaveorder'])){

    $user_name=$_POST['txtcustomer'];
    $order_date=date('Y-m-d',strtotime($_POST['orderdate']));
    $total=$_POST['txttotal'];
    

    ////////////////////////////////
     //time
    $h  = date('H');
    $mi   = date('i');
    $seg  = date('s');
    $time =  ($h+1).''.$mi.''.$seg;

    $arr_productid=$_POST['productid'];
    $arr_productname=$_POST['productname'];
    $arr_stock=$_POST['stock'];
    $arr_qty=$_POST['qty'];
    $arr_price=$_POST['price'];
    $arr_total=$_POST['total'];
    
    
    $insert=$pdo->prepare("insert into tbl_saidas(total,user,data,hora) values(:total,:user,:data,:hora)");
    
    $insert->bindParam(':total',$total);
    $insert->bindParam(':user',$user_name);
    $insert->bindParam(':data', $order_date);
    $insert->bindParam(':hora',$time);
    
    
    $insert->execute();
    
    //2nd  insert query for tbl_invoice_details
    
    
    $invoice_id=$pdo->lastInsertId();
    if($invoice_id!=null){

        for($i=0 ; $i<count($arr_productid) ; $i++){

         $insert=$pdo->prepare("insert into tbl_itens_saidos(id_saida,item,produto,quant,valor,data) values(:id_saida,:item,:produto,:quant,:valor,:data)");

         $insert->bindParam(':id_saida',$invoice_id);
         $insert->bindParam(':item', $arr_productid[$i]);
         $insert->bindParam(':produto',$arr_productname[$i]);
         $insert->bindParam(':quant',$arr_qty[$i]);
         $insert->bindParam(':valor',$arr_price[$i]);
         $insert->bindParam(':data',$order_date);


         $insert->execute(); 




     }        

   //  echo"success fully created order";  
     echo '<script>alert("Obrigado, Pode Imprimir");
     
     window.location.replace("print.php");
     </script>';  
     //header('location:print.php');     

 }




}




include_once'cabecalho_user.php';   









?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <img src="../images/icons8-barber_brush_filled.png">

            <small><img src="../images/icons8-school_director.png"></small>
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

                <div class="box-header with-border">
                    <h3 class="box-title">Corte de cabelos</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->




                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                           

                            <div class="input-group">
                                
                                <input type="hidden" class="form-control" name="txtcustomer" value=" <?php echo $_SESSION['username'] ?>" readonly>
                            </div>
                        </div>



                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                           

                            <div class="input-group">
                                
                                <input type="hidden" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd" >
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
                                <th>Corte</th>
                                <th>Preço</th>
                                <th>Enter</th>
                                <th>Total</th>
                                <th>
                                   <center> <button type="button" name="add" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button></center>

                               </th>

                           </tr>

                       </thead>


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
                            MT
                        </div>



                        <input type="text" class="form-control" name="txttotal" id="txtsubtotal" required readonly>
                    </div>
                </div>


            </div>



        </div><!-- tax dis. etc -->

        <hr>

        <div align="center">

            <input type="submit" name="btnsaveorder" value="Finalizar" class="btn btn-info">

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

            html+='<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';

            html+='<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">Escolha o corte <img src="../icons8-barbershop.png"/></option><?php echo fill_product($pdo); ?> </select></td>';

           
            html+='<td><input type="text" class="form-control price" name="price[]" readonly></td>';
            html+='<td><input type="number" min="1" class="form-control qty" name="qty[]"></td>';
            html+='<td><input type="text" class="form-control total" name="total[]" readonly></td>';
            html+='<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>'; 

            $('#producttable').append(html);


      //Initialize Select2 Elements
      $('.productid').select2()

      $(".productid").on('change' , function(e){

        var productid = this.value;
        var tr=$(this).parent().parent();  
        $.ajax({

            url:"captproduto.php",
            method:"get",
            data:{id:productid},
            success:function(data){

         console.log(data); 
         tr.find(".pname").val(data["corte"]);
         tr.find(".stock").val(data["descricao"]);
         tr.find(".price").val(data["pre_unt"]); 
         tr.find(".qty").val(1);
         tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 
         calculate(0,0); 
     }   
 })   
    })    

    }) // btnadd end here    

        
        $("#producttable").delegate(".qty","keyup change" ,function(){

          var quantity = $(this);
          var tr = $(this).parent().parent(); 

          if((quantity.val()-0)>(tr.find(".stock").val()-0) ){

           Swal.fire("WARNING!","OPS! Essa quantidade não esta disponivel","warning");

           quantity.val(1);

           tr.find(".total").val(quantity.val() *  tr.find(".price").val());
           calculate(0,0);
       }else{

           tr.find(".total").val(quantity.val() *  tr.find(".price").val());
           calculate(0,0);
       }    



   })        
        
        $(document).on('click','.btnremove',function(){

            $(this).closest('tr').remove(); 
            calculate(0,0);
            $("#txtpaid").val(0);

     }) // btnremove end here  
        

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
            net_total=tax+subtotal;  //50+1000 =1050
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