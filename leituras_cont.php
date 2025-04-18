<link rel="stylesheet" type="text/css" href="../select2Pro/select2.min.css">
<script src="../select2Pro/select2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<?php
include_once '../dbconnect.php'; 
include_once '../conexao.php'; 
session_start();  

if ($_SESSION['useremail']=="" OR $_SESSION['role']=="Caixa" OR $_SESSION['role']=="Balconista") {

	header('location:../index.php');
}
include_once 'header.php';
$data_hoje = date('Y-m-d');


$consulta=mysqli_query($mysqli,"SELECT * FROM despesas where data ='$data_hoje'");
$result=mysqli_num_rows($consulta);

//$idsaida=$result['invoice_id'];

$select = $pdo->prepare("SELECT sum(valor) as v FROM despesas where data='$data_hoje'");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$total_v=$row->v;


if(isset($_POST['btn_leitura'])){
  $contador = $_POST['txtcontador'];
  $l_ante = $_POST['txtanterior'];
  $l_actual = $_POST['txtactual'];
  $preco = $_POST['txtpreco'];
  $metrosM3 = $_POST['txtmetroscub'];
  $valor = $_POST['txtapagar'];
  $estado = $_POST['status'];
  //$data = date('Y-m-d',$_POST['data']);


  $stmt = "INSERT INTO leitura VALUES (NULL,'$contador','$l_ante','$l_actual','$preco','$metrosM3','$valor','$estado','$data_hoje')";
 
 if (mysqli_query($mysqli, $stmt)) {
        //header('location:iniciar_provacao.php?id_req='.$idsaida.'');  
        echo '<script>alert("leitura registado com sucesso!")
        window.location="leituras.php";
        </script>';
    } else {
        echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
    }


}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Formulario de Leituras
        			
			<small><h3 align="center">Registar leitura</h3></small>
		</h1>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">
        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
        	<div class="box-body">
            <form action="" method="post">
        			<div class="col-md-12">
        				<div class="form-group">
        					<label>Leituras</label>
        					<div class="input-group">
        						<span class="input-group-addon">Anterior</span>
        						<input type="number" class="form-control" name="txtanterior" id="txtanterior" onblur="reSum()" autocomplete="off" value="0">
        					</div>
        					<br>
        					<div class="input-group">
        						<span class="input-group-addon">Actual  </span>
        						<input type="number" class="form-control" name="txtactual" id="txtactual" onblur="metrocCub_apagar()" autocomplete="off" required>
        					</div>
        				</div>
        				 
        				<div class="form-group">
        					<label>Preço Unitário</label>
        					<div class="input-group">
        						<span class="input-group-addon">Preço UN</span>
        						<input type="number" class="form-control"  name="txtpreco" id="txtpreco" value="70" readonly onblur="metrocCub_apagar()">
        					</div>
        				</div>
        				<div class="form-group">
        					<label>Metros (M³)</label>
        					<div class="input-group">
        						<span class="input-group-addon">Metros (M³) a pagar </span>
        						<input type="number" class="form-control"  name="txtmetroscub" id="txtmetroscub" readonly>
        					</div>
        				</div>
        				<div class="form-group">
        					<label>Valor a pagar</label>
        					<div class="input-group">
        						<span class="input-group-addon">TOTAL</span>
        						<input type="number" class="form-control" name="txtapagar" id="txtapagar" readonly>
        					</div>
        				</div>
        				<button type="submit" class="btn btn-success" name="btn_leitura"><i class="fa fa-send"></i> Submeter</button>
        			</div>
        		</form>
        	</section>
        </div>

        <!-- /.content-wrapper -->
        <script type="text/javascript">
        	$("#piroduto").change(mostrarValores);

        	function mostrarValores(){

        		dadosProduto=document.getElementById('piroduto').value.split('_');
        		$("#txtcontador").val(dadosProduto[1]);
        		$("#nome_cliente").val(dadosProduto[2]);
        		$("#txtcontacto").val(dadosProduto[3]);
        		$("#txtmorada").val(dadosProduto[4])
              //$("#txtProduto").val(dadosProduto[3]);


        	}
        </script>

        <!--calulo-->
        <script type="text/javascript">
        	function metrocCub_apagar()
        {
            var lant = parseFloat(document.getElementById("txtanterior").value);
            var lact = parseFloat(document.getElementById("txtactual").value);
            document.getElementById("txtmetroscub").value = (lact - lant).toFixed(2);

            var metrosCub = parseFloat(document.getElementById("txtmetroscub").value);
            var precoFixo = parseFloat(document.getElementById("txtpreco").value);
            document.getElementById("txtapagar").value = (metrosCub *precoFixo).toFixed(2);
        }

			 </script>
        <!--Total a pagar-->
       

        <script type="text/javascript">
        	$(document).ready(function() {
        		$('.single').select2();
        	});
        </script>
        <?php
        include_once'footer.php';
    ?>