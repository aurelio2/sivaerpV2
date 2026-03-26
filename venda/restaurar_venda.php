<?php
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
include_once 'funcoes_caixa.php';

$invoice_id = $_GET['id'];
$idfuncionario = $idUser;
$data = getDataCaixaAberto($idUser, $mysqli);

$select=$pdo->prepare("SELECT * from tbl_invoice where invoice_id = :invoice_id");
$select->bindParam(':invoice_id',$invoice_id);
$select->execute();
$invoice=$select->fetch(PDO::FETCH_OBJ);

if(!$invoice){
    echo "<script>alert('Venda não encontrada!'); window.location.href='diario.php';</script>";
    exit;
}

if(isset($_POST['btnrestaurar'])){
    
    $mpesa = isset($_POST['txtmpesa']) ? $_POST['txtmpesa'] : 0;
    $emola = isset($_POST['txtemola']) ? $_POST['txtemola'] : 0;
    $cash = isset($_POST['txtcash']) ? $_POST['txtcash'] : 0;
    $pos = isset($_POST['txtpos']) ? $_POST['txtpos'] : 0;
    $recebido = isset($_POST['txtrecebido']) ? $_POST['txtrecebido'] : 0;
    $troco = isset($_POST['txttroco']) ? $_POST['txttroco'] : 0;
    
    $total_formas = $mpesa + $emola + $cash + $pos;
    
    if($total_formas == 0){
        echo "<script>alert('Deve informar pelo menos um método de pagamento!');</script>";
    }else{
        
        $check = mysqli_query($mysqli,"SELECT * FROM tbl_control_payment where id_invoice = $invoice_id");
        
        if(mysqli_num_rows($check) > 0){
            $update = "UPDATE tbl_control_payment SET 
                       mpesa='$mpesa', emola='$emola', cash='$cash', pos='$pos', 
                       valor_recebido='$recebido', troco='$troco', data='$data' 
                       WHERE id_invoice='$invoice_id'";
            mysqli_query($mysqli, $update);
        }else{
            $stmt = "INSERT INTO tbl_control_payment VALUES 
                (NULL,'$invoice_id','$mpesa','$emola','$cash','$pos','$recebido','$troco','$data','$idfuncionario')";
            mysqli_query($mysqli, $stmt);
        }
        
        echo "<script>
                alert('Método de pagamento registrado com sucesso!');
                window.location.href='diario.php';
              </script>";
    }
}

include_once'cabecalho_user.php';
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Restaurar Método de Pagamento
      <small>Invoice #<?php echo $invoice_id; ?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="diario.php"><i class="fa fa-dashboard"></i> Relatório Diário</a></li>
      <li class="active">Restaurar Venda</li>
    </ol>
  </section>

  <section class="content container-fluid">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Detalhes da Venda</h3>
      </div>
      
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Data:</strong> <?php echo $invoice->order_date; ?></p>
            <p><strong>Cliente:</strong> <?php echo $invoice->customer_name; ?></p>
            <p><strong>Subtotal:</strong> <?php echo number_format($invoice->subtotal, 2); ?> MT</p>
            <p><strong>Total:</strong> <?php echo number_format($invoice->total, 2); ?> MT</p>
          </div>
        </div>
        
        <hr>
        
        <form method="post" action="">
          <h4>Registrar Método de Pagamento</h4>
          
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Cash (MT)</label>
                <input type="number" step="0.01" class="form-control" name="txtcash" id="txtcash" value="0" min="0">
              </div>
            </div>
            
            <div class="col-md-3">
              <div class="form-group">
                <label>M-PESA (MT)</label>
                <input type="number" step="0.01" class="form-control" name="txtmpesa" id="txtmpesa" value="0" min="0">
              </div>
            </div>
            
            <div class="col-md-3">
              <div class="form-group">
                <label>E-MOLA (MT)</label>
                <input type="number" step="0.01" class="form-control" name="txtemola" id="txtemola" value="0" min="0">
              </div>
            </div>
            
            <div class="col-md-3">
              <div class="form-group">
                <label>POS (MT)</label>
                <input type="number" step="0.01" class="form-control" name="txtpos" id="txtpos" value="0" min="0">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Valor Recebido (MT)</label>
                <input type="number" step="0.01" class="form-control" name="txtrecebido" id="txtrecebido" value="0" min="0">
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label>Troco (MT)</label>
                <input type="number" step="0.01" class="form-control" name="txttroco" id="txttroco" value="0" min="0" readonly>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-info">
                <strong>Total dos Métodos:</strong> <span id="total_metodos">0.00</span> MT
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <button type="submit" name="btnrestaurar" class="btn btn-success">
                <i class="fa fa-save"></i> Salvar Método de Pagamento
              </button>
              <a href="diario.php" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Voltar
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function(){
    
    function calcularTotais(){
        var cash = parseFloat($('#txtcash').val()) || 0;
        var mpesa = parseFloat($('#txtmpesa').val()) || 0;
        var emola = parseFloat($('#txtemola').val()) || 0;
        var pos = parseFloat($('#txtpos').val()) || 0;
        var recebido = parseFloat($('#txtrecebido').val()) || 0;
        
        var total_metodos = cash + mpesa + emola + pos;
        var troco = recebido - total_metodos;
        
        if(troco < 0) troco = 0;
        
        $('#total_metodos').text(total_metodos.toFixed(2));
        $('#txttroco').val(troco.toFixed(2));
    }
    
    $('#txtcash, #txtmpesa, #txtemola, #txtpos, #txtrecebido').on('input', function(){
        calcularTotais();
    });
    
    calcularTotais();
});
</script>

<?php
include_once'../venda/footer.php';
?>
