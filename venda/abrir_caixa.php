<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
error_reporting(0);
date_default_timezone_set('Africa/Maputo');

include_once 'cabecalho_user.php';
include_once 'funcoes_caixa.php';

	$idfuncionario = $idUser;
	$dia = date('d');
    $ano = date('Y');
    $mes = date('m');
    $hora_atual = date('H:i:s'); // Obtém a hora atual no formato 'H:i:s'

	// Subtrai 25 minutos da hora atual
	$nova_hora = date('H:i:s', strtotime($hora_atual));

if(isset($_POST['btnaddorder'])){
	
    $data=date('Y-m-d');
  
	$valor_inicial = $_POST['txtvalor_inicial'];
	$hora =date('H:i:s', strtotime($hora_atual));

	$estado = 1;
	

$stmt = "INSERT INTO tbl_caixa VALUES 
		(NULL,'$valor_inicial','0','$idfuncionario','$data','$hora','00:00:00','0000-00-00','$estado','0')";

	
		if (mysqli_query($mysqli, $stmt)) {
			
			echo '<script>
			alert("Obrigado,Caixa inicializado com sucesso!")
			</script>';
			echo '<meta http-equiv="refresh" content="1; url=mesa.php">';
		} else {
			echo "Erro de processamento!!" . $stmt . "<br>" . mysqli_error($mysqli);
		}

 
}
  
 


?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">
    	<?php 
    		$data = date('Y-m-d');
			$estado = 1;

			$sql = mysqli_query($mysqli, "SELECT * 
          FROM tbl_caixa 
          WHERE id_user = '$idUser' 
            AND estado = '1' 
            AND closed_by = '0' 
           
            AND data_final = '0000-00-00'

            AND id = (SELECT MAX(id) FROM tbl_caixa WHERE id_user = '$idUser')");

			if (mysqli_num_rows($sql) > 0) {
			    // Já existe um registro com a data e estado especificados
			    $res = mysqli_fetch_array($sql);	

				$horas_db = date('H:i:s',strtotime($res['hora']));
				$data_db = $res['data'];
				$dia1 = date('d',strtotime($data_db));
				$ano1 = date('Y',strtotime($data_db));
				$mes1 = date('m',strtotime($data_db));
			 echo ' <h1>
        Caixa inicializado no dia '.$dia1.'/'.$mes1.'/'.$ano1.' pelas horas: '.$horas_db.' </h1>

      </h1>';
      echo '<br>';
      	echo '<table class="table table-borderless table-dark">
			    <thead>
			        <tr>
			            <th scope="col">#</th>
			            <th scope="col">Valor Inicial</th>
			            <th scope="col">Hora inicial</th>
			            <th scope="col">Ação</th>
			        </tr>
			    </thead>
			    <tbody>';

			// Realize a consulta SQL e obtenha os resultados
			    $data=date('Y-m-d');
			$sqli1 = mysqli_query($mysqli, "SELECT * 
          FROM tbl_caixa 
          WHERE id_user = '$idUser' 
            AND estado = '1' 
            AND closed_by = '0' 
           
            AND data_final = '0000-00-00'

            AND id = (SELECT MAX(id) FROM tbl_caixa WHERE id_user = '$idUser')");
			while ($linha = mysqli_fetch_array($sqli1)) {
			    echo '<tr>
			            <th scope="row">1</th>
			            <td>' . $linha['valor_inicial'] . '</td>
			            <td>' . $linha['hora'] . '</td>
			            <td><a  href="fechar_caixa.php?id='.$linha[0].'"
						>Fechar</td>
			        </tr>';
			}

			echo '</tbody>
			    </table>';
			    
			} else {

    	 ?>
      <h1>
      	

        Abertura de caixa do dia : <?php echo $dia.'/'.$mes.'/'.$ano ?> Pela as Horas: <?php echo $nova_hora; ?> </h1>

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
                <input type="text" name="txtvalor_inicial" class="form-control" placeholder="valor Inicial" autocomplete="off">
              </div>

             </div>

             <div class="box-footer">
           

             <button type="submit" class="btn btn-info" name="btnaddorder">Continuar</button>            

              </div>

              </form>
          </div>
      <?php } ?>

    </section>

    <!-- /.content -->

  </div>
  
          

  <!-- /.content-wrapper -->



  <?php



include_once'footer.php';



?>