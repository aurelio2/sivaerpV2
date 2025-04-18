
<?php
      include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
error_reporting(0);



include_once 'header.php';

  $id = $_GET['id'];
  $id_divida =$_GET['id_divida'];

  $seach = mysqli_query($mysqli,"SELECT * FROM tbl_devida where id_invoice = $id");
  $ver=mysqli_fetch_array($seach);

  $seach1 = mysqli_query($mysqli,"SELECT * FROM client_order_detalhes where invoice_id = $id");
  $ver1=mysqli_fetch_array($seach1);


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
  
      Datalhes de Venda nº <b><?php echo $id?><b>
      Cliente: <span style="color:red;"><?php echo $ver1['nome']; ?></span>
      Numero: <span style="color:red;"><?php echo $ver['celular']; ?></span>
      Divida: <span style="color:red;"><?php echo number_format($ver['valor'],2); ?></span>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <div class="box box-warning">
    <div class="box-header with-border">
    <h3 align="center">Detalhes desta venda em divida</h3>
    <hr>
    <table id="salesreporttables" class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Produto</th>  
                  <th>Quantidade</th>
                  <th>Preco Unit</th>
                  <th align="right">Subtotal</th>
                </tr>                    
              </thead>              
              <tbody>
         <?php
     $cons = mysqli_query($mysqli,"SELECT * FROM tbl_invoice_details WHERE invoice_id = $id");
     while($res=mysqli_fetch_array($cons)){
     
        echo'
                  <tr>
                  <td rowsapan=2></td>
                  <td>'.$res['product_name'].'</td>
                  <td>'.$res['qty'].'</td>
                  <td>'.number_format($res['price'],2).'</td>
                  <td>'.number_format($res['total']).'</td>

                  ';
                }          
                ?>                       
              </tbody>               
            </table>     
            
      </div>
    </div>

    </section>

      <section class="content container-fluid">
    <div class="box box-warning">
    <div class="box-header with-border">
    <h4 align="center">Pagamento</h4>
    <hr>
    <div class="form-group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Pagamento <img src="../images/icons8-request_money.png" width="20px"></button>
                </div>
    <table id="salesreporttables" class="table table-striped">
              <thead>
                <tr>
                 
                  <th>M-Pesa</th>  
                  <th>E-Mola</th>  
                  <th>Cash</th>  
                  <th>POS</th>  
                  <th>Data</th>  
                </tr>                    
              </thead>              
              <tbody>
         <?php
     $cons = mysqli_query($mysqli,"SELECT * FROM tbl_control_payment WHERE id_invoice = $id");
     while($res=mysqli_fetch_array($cons)){
     
        echo'
                  <tr>
                  <td rowsapan=2>'.$res['mpesa'].'</td>
                  <td>'.$res['emola'].'</td>
                  <td>'.$res['cash'].'</td>
                  <td>'.$res['pos'].'</td>
                  <td>'.$res['data'].'</td>

                  ';
                }          
                ?>                       
              </tbody>               
            </table>     
            
      </div>
    </div>
    <?php 
    	if(isset($_POST['btn_finalizar'])){

    		$estado = $_POST['estado_divida'];
    		$stmt1 = mysqli_query($mysqli,"UPDATE tbl_devida SET 
							estado='$estado'
		                     where id_invoice = '$id'");

            echo '<meta http-equiv="refresh" content="1; url=ticketDivida.php?id='.$id.'">';
    	}

     ?>
    <?php if($ver['valor']=='0'){ ?>
    	<!--fechar a divida-->
    	<form method="post">
    		<input type="hidden" name="estado_divida" value="1">
    	<button type="submit" class="btn btn-primary" name="btn_finalizar">Finalizar</button>
    </form>
    <?php } ?>
    </section>
    <!-- /.content -->
  </div>

  <?php 
if (isset($_POST["btn_fechar"])) {
     $cash = $_POST['txtcash'];
     $mpesa = $_POST['txtmpesa'];
     $emola = $_POST['txtemola'];
     $pos = $_POST['txtpos'];

     //$mesa_text=$_POST['txtmesa'];

     $data = date('Y-m-d');

     $divida = $ver['valor'];

     $todosformas = $cash+$mpesa+$emola+$pos;
     $pagar = $divida-$todosformas;
     
        
      $stmt = "INSERT INTO tbl_control_payment VALUES 
        (NULL,'$id','$mpesa','$emola','$cash','$pos','0','0','$data',$idUser)";

     

        //$update->execute();
        if (mysqli_query($mysqli, $stmt)) {
        	 $stmt1 = mysqli_query($mysqli,"UPDATE tbl_devida SET 
							valor='$pagar'
		                     where id_invoice = '$id'");
            echo '<script>
            alert("Obrigado, Pagamento Finalizado com sucesso!")
            </script>';
            echo '<meta http-equiv="refresh" content="1; url=control_devida.php?id='.$id.'&id_divida='.$id_divida.'">';
        } else {
            echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
        }

}
?>
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <h1>Total: <span style="color:red;"><?php echo number_format($ver['valor'],2); ?> MT</span></h1>
      </div>
     <form method="post">
    <input type="hidden" class="form-control pull-right" name="txtmesa" value="0">
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">CASH:</label>
        <div class="options">
            <input type="checkbox" name="payment-option" id="cash-option" value="cash">
        </div>
        <input type="number" class="form-control" placeholder="CASH" name="txtcash" id="txtcash" style="display: none;" value="0">
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
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="btn_fechar">Pagar</button>
    </div>
</form>

  </div>
</div>
</div>
  <!-- /.content-wrapper -->
  <script  type="text/javascript">
   

   $('#salesreporttable').DataTable({
    
    "order":[[0,"desc"]]    
    
    
    
  });
</script>
<script>
$(document).ready(function() {
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
});
</script>


<?php

include_once'footer.php';

?>






