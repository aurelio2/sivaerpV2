
<?php
include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
error_reporting(0);



include_once 'header.php';

$data = date('Y-m-d');

$sql = mysqli_query($mysqli, "SELECT * FROM tbl_caixa WHERE estado = '2' ");
$res = mysqli_fetch_array($sql);
$data_c = $res['data'];
$data_final = $res['data_final'];
$aberto_money = $res['valor_inicial'];
$fecho_money = $res['valor_final'];
$user_id1 = $res['id_user'];
$user_id2 = $res['closed_by'];



$query = "SELECT SUM(subtotal) AS total FROM tbl_invoice WHERE order_date BETWEEN '$data_c' AND '$data_final'";

$result = $mysqli->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $total_dia = $row['total'];
} else {
    echo "Erro na consulta: " . $mysqli->error;
}

     
        
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      
      Fecho e seus detalhes  <b>
    </h1>

  </section>

  <!-- Main content -->
  <section class="content container-fluid">
        <div class="box box-warning">
        <div class="box-header with-border">
	
		<table id="" class="table table-striped">
              <thead>
                <tr>
                  <th>Ord</th>
                  <th>Usuario</th>
                  <th>Inicio Data</th>
                  <th>Final Data</th>
                  
                  <th>Producto</th>  
                  <th>Qtd</th>
                  <th>Preco Unit</th>
                  <th>Subtotal</th>
                </tr>                    
              </thead>              
              <tbody>
         <?php
         $id = 1;
          $sql_caixa = mysqli_query($mysqli, "SELECT data_final FROM tbl_caixa WHERE estado = '2'");
        while ($res_caixa = mysqli_fetch_array($sql_caixa)) {
            $data_i = $res_caixa['data_final'];
            $data_final = $res_caixa['data_final'];

            
            $cons = mysqli_query($mysqli, "SELECT
                i.customer_name,
                d.product_name,
                SUM(d.qty) as qtd,
                d.price,
                d.total
            FROM tbl_invoice AS i
            JOIN tbl_invoice_details AS d
            ON i.invoice_id = d.invoice_id
            WHERE d.order_date BETWEEN '$data_i' AND '$data_final' GROUP BY d.product_name");

            while ($res = mysqli_fetch_array($cons)) {
                echo '
                    <tr>
                        <td>' . $id++ . '</td>
                        <td>' . $res['customer_name']. '</td>
                        <td>' . $data_i.'</td>
                        <td>' . $data_final. '</td>
                        <td>' . $res['product_name'] . '</td>
                        <td>' . $res['qtd']. '</td>
                        <td>' . number_format($res['price'], 2) . '</td>
                        <td>' . number_format($res['total'], 2) . '</td>
                    </tr>';
                  }
                }          
                ?>                       
              </tbody>               
            </table>     

      </div>
      
      
    </section>
    
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  <script  type="text/javascript">
   

   $('#salesreporttable').DataTable({
    
    "order":[[0,"desc"]]    
    
    
    
  });
</script>


<?php

include_once'footer.php';

?>






