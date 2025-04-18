<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(1);
date_default_timezone_set('Africa/Maputo');
include_once 'cabecalho_user.php';
include_once 'funcoes_caixa.php';


$ref = $_GET['id'];

$idfuncionario = $idUser;
//$sql1 = mysqli_query($mysqli, "SELECT * FROM tbl_caixa WHERE id = '$ref'");
 $sql1 = mysqli_query($mysqli, "SELECT * 
          FROM tbl_caixa 
          WHERE id_user = '$idUser' 
            AND estado = '1' 
            AND closed_by = '0' 
            AND id = (SELECT MAX(id) FROM tbl_caixa WHERE id_user = '$idUser')");
        $res1 = mysqli_fetch_array($sql1);
        $data_c = $res1['data'];
        $data_f = $res1['data_final'];
        $horas_db = $res1['hora'];
        $caixa_estado = $res1['estado']; // Obtém o estado da caixa
        $valor_inicial_db = $res1['valor_inicial']; // Obtém o estado da caixa
        $valor_final_caixa_anterior = $res1['valor_final']; // Obtém o valor final da caixa anterior

        // Verifica se a caixa foi fechada no mesmo dia
        if ($data_c != $data_f && $caixa_estado == 1) {
            $data_f = date('Y-m-d', strtotime($data_c . ' + 1 day'));

            // Se a caixa é reaberta no mesmo dia, use o valor final anterior como base
            if ($valor_final_caixa_anterior > 0) {
                $total_order1 = $valor_final_caixa_anterior;
            } else {
                // Caso contrário, calcule o total das vendas
                $select1 = mysqli_query($mysqli, "SELECT
                    SUM(subtotal) AS total1,
                    COUNT(invoice_id) AS invoice
                  FROM tbl_invoice
                  WHERE order_date BETWEEN '$data_c' AND CURRENT_DATE
                    AND user = '$idUser'");
                $row = mysqli_fetch_array($select1);
                $total_order1 = $row['total1'];
            }
        } 
 
	

$dia = date('d',strtotime($data_c));
$ano = date('Y',strtotime($data_c));
$mes = date('m',strtotime($data_c));
$hora_atual = date('H:i:s',strtotime($horas_db)); // Obtém a hora atual no formato 'H:i:s'

// Subtrai 25 minutos da hora atual
$nova_hora = date('H:i:s');

if(isset($_POST['btnaddorder'])){
	
// Usar a data atual para o fechamento
$data=date('Y-m-d');
$valor_inicial = $_POST['txtvalor_inicial'];
$hora =date('H:i:s', strtotime($hora_atual));

$stmt = mysqli_query($mysqli,"UPDATE tbl_caixa SET 
         					   valor_final='$total_order1',
         					   hora_final='$nova_hora',
         					   data_final='$data',
         					   estado='2',
                     closed_by='$idfuncionario'
         					   where id = '$ref'");

	
 echo ("<script>
            alert('Obrigado, Fecho realizado com sucesso!');          
            </script>");
    echo '<meta http-equiv="refresh" content="1; url=print_fecho.php?id='.$ref.'">';

 
}
  
 


?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>
      	

        Fechar caixa que foi inicializado no dia : <?php echo $dia.'/'.$mes.'/'.$ano ?> Pela as Horas: <?php echo $nova_hora; ?> </h1>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>

        <li class="active">Here</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content container-fluid">
    	
         <div class="box box-info">

         <form action="" method="post" >

            <div class="box-body">
            <div class="col-md-12">
         	
              <div class="form-group" >
                <label>Valor Inicial</label>
                <input type="text" name="txtvalor_inicial" class="form-control" placeholder="valor Inicial" autocomplete="off" value="<?php echo $valor_inicial_db; ?>" readonly >
              </div>
                <div class="form-group" >
                <label>Valor Final</label>
                <input type="text" name="txtvalor_final" class="form-control" placeholder="valor Inicial" autocomplete="off" value="<?php echo $total_order1; ?>" readonly>
              </div>

             </div>

             <div class="box-footer">
           

             <button type="submit" class="btn btn-info" name="btnaddorder">Fecho</button>            

              </div>

              </form>
          </div>
  

    </section>

    <!-- /.content -->

  </div>
  
          

  <!-- /.content-wrapper -->



  <?php



include_once 'footer.php';



?>