<?php 
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
include_once 'funcoes_caixa.php';
include_once '../config_imagem.php';
error_reporting(0);
  
    include_once 'cabecalho_user.php';
 ?>

  <!-- Content Wrapper. Contains page content -->
  <meta http-equiv="refresh" content="15">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <?php
        // Obter a data do caixa aberto
        $data_caixa = getDataCaixaAberto($idUser, $mysqli);
        
        // Consulta usando a data do caixa aberto em vez da data atual
        $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice 
                               from tbl_invoice 
                               where order_date='$data_caixa' and user='$idUser'");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total_order=$row->total;
        //$net_total=$row->t;
        ?>
        <?php
        
        $sql1 = mysqli_query($mysqli, "SELECT * 
          FROM tbl_caixa 
          WHERE id_user = '$idUser' 
            AND estado = '1' 
            AND closed_by = '0' 
            AND id = (SELECT MAX(id) FROM tbl_caixa WHERE id_user = '$idUser')");
        $res1 = mysqli_fetch_array($sql1);
        $data_c = $res1['data'];
        $data_f = $res1['data_final'];
        $caixa_estado = $res1['estado']; // Obtém o estado da caixa
        $valor_final_caixa_anterior = $res1['valor_final']; // Obtém o valor final da caixa anterior

        // Verifica se a caixa foi fechada no mesmo dia
        // Calcular o total das vendas usando a data do caixa aberto
        $data_caixa = getDataCaixaAberto($idUser, $mysqli);
        
        // Consulta para obter o total das vendas do dia do caixa aberto
        $select1 = mysqli_query($mysqli, "SELECT
            SUM(total) AS total1,
            COUNT(invoice_id) AS invoice
          FROM tbl_invoice
          WHERE order_date = '$data_caixa'
            AND user = '$idUser'");
        $row = mysqli_fetch_array($select1);
        $total_order1 = $row['total1'];
        
        // Se não houver vendas, inicializar com zero
        if (empty($total_order1)) {
            $total_order1 = 0;
        }
        
        // Formatar a data para exibição
        $data_formatada = date('d/m/Y', strtotime($data_caixa));
        
        ?>
              
      
      <p> <b>Operador:</b> <?php echo $_SESSION['username']; ?><b style="color:green;"> Online</b></p>
      <h1>
      <b>Data do Caixa: <?php echo $data_formatada; ?></b><br>
      <b>Total do Dia: <?php echo number_format($total_order1,2); ?> MT</b><br>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Nivel</a></li>
        <li class="active">Here</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content container">
        <!--Caixa-->
     
        <?php 

         $select=$pdo->prepare("select DISTINCT cod_mesa, id, status, descricao from tbl_mesa order by status=1 desc, cod_mesa asc ");
         $select-> execute();

         while ($row=$select->fetch(PDO::FETCH_OBJ)) {
          $id=$row->cod_mesa;
          $id_mesa=$row->id;
         $select1=$pdo->prepare("select * from tbl_invoice where invoice_id =(select MAX(invoice_id) from tbl_invoice where mesa=$id) ");
         $select1-> execute();
         $rows=$select1->fetch(PDO::FETCH_OBJ);
         $max=$rows->invoice_id;

         $sql = mysqli_query($mysqli,"SELECT * FROM client_order_detalhes WHERE id =
        (SELECT MAX(id) FROM client_order_detalhes WHERE id_order = $id)");
        $res = mysqli_fetch_array($sql);
        $nome_cliente_db = $res['nome'];

      
        // Usar a data do caixa aberto
      $data = getDataCaixaAberto($idUser, $mysqli);
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
           
            // Debug para verificar se a variável está definida
            // if (!isset($nome_imagem_tipo)) {
            //     $nome_imagem_tipo = '../images/restaurant.png'; // Definir um valor padrão caso não esteja definido
            // }
            
            if ($row->status==0) {
                echo '
                <div class="col-lg-3 col-xs-4">
                  <h2>'.$row->cod_mesa.'</h2>
                  <div class="icon">
                    <img src="'.$nome_imagem_tipo.'" alt="Imagem Mesa">
                    <a href="start_order.php?id='.$id.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-shopping_cart.png">Vender</i></a>
                  </div>
                  <br>
                </div>
                ';
            } else if ($row->status==1) {
               echo '<div class="col-lg-3 col-xs-6">
                <!-- small box -->
               <div class="box box-solid box-danger"> 
                  <div class="inner">
                    <h4 style="color:red;">'.$row->cod_mesa.' A pagar </h4>
                     <h4 style="color:blue;">Cliente: '.$nome_cliente_db.' </h4>
                  <div class="icon">
                    <a href="pagar.php?id='.$id.'&op=det&max='.$max.'" class="small-box-footer"><img src="../images/icons8-request_money.png"> '.$rows->total.' MT</a>
                  
                    <a href="../recibo1/conta.php?id='.$max.'" class="small-box-footer" target="_blank"><img src="../images/conta.png"> Conta</a> 

                  </div>
                  <a href="editorder.php?id='.$max.'" class="small-box-footer"><i class="fa fa-arrow-circle-right"><img src="../images/icons8-shopping_cart.png"> Detalhes</i></a>
                </div>
                </div>
              </div>';
            }
        } else {
            // Não existe um registro com a data e estado especificados
            //echo '<h1>Nenhum registro encontrado para a data especificada.</h1>';
        }


         ?>
     

      <?php } ?>
      
       <?php 
         $select = $pdo->prepare("SELECT COUNT(pid) as p FROM tbl_product WHERE datavalidade<=curdate()");
         $select->execute();
         $row=$select->fetch(PDO::FETCH_OBJ);

         $stock_exipirados=$row->p;
      ?>
      <!--<div class="col-lg-6 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3 style="text-align: center;"><?php echo $stock_exipirados;?></h3>

              <p style="text-align:center;">Expirados</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="prodExispirados.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>-->
        <!--<h3>Producto que vao expirar em 30 dias....</h3>-->
         <?php 
          $select = $pdo->prepare("SELECT count(pname) as pro FROM tbl_product WHERE datavalidade between NOW() and DATE_ADD(NOW(), INTERVAL 30 DAY) ORDER BY datavalidade");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);
          $total_nu=$row->pro;
          ?>
        <!--<div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
             <h3 style="text-align:center;"><?php echo $total_nu; ?></h3>
              <h6 style="text-align:center;"><b>Productos que vão expirar em 30 dias</b></h6>
            </div>
            <div class="icon">
               <i class="ion-android-warning"></i>
            </div>
            <a href="validade_pro.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>-->
         
    </section>

   
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
  <?php 
    include_once 'footer.php'
   ?>