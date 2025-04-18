
<?php
      include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
      error_reporting(0);

include_once 'header.php';


?>
<script type="text/javascript">
      $(function() {
        $( "#date_1" ).datepicker({
          dateFormat: 'yy-mm-dd',
          dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
          dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
          dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
          monthNamesShort: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
          showOn: "button",
          buttonImage: "../images/calendario.png",
          buttonImageOnly: true,
          changeMonth: true,
          changeYear: true
        });
      });
</script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Histórico de Transferências
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
            <form  action="" method="post" name="">
           
            <h3 class="box-title">
            De : <?php echo isset($_POST['date_1']) ? $_POST['date_1'] : ''; ?> -- 
            Ate : <?php echo isset($_POST['date_2']) ? $_POST['date_2'] : ''; ?>
        </h3>
            <!-- /.box-header -->
            <!-- form start -->

        <div class="box-body">                  
        <div class="row">            
        <div class="col-md-5">            
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
        <input type="date" class="form-control pull-right" id="datepicker1" name="date_1"  data-date-format="AAAA-MM-DD" >
            </div>           
        </div>   
        <div class="col-md-5">
            
             <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
        <input type="date" class="form-control pull-right" id="datepicker2" name="date_2"  data-date-format="yyyy-mm-dd" >
                            </div> 
            
        </div>    
           
        <div class="col-md-2">
            <div align="left">

             <input type="submit" name="btndatefilter" value="Buscar" class="btn btn-success">

            </div>
                 
        </div>                           
        </div>  
           
      <!-- Info boxes -->
      <div class="row">
      
        <!-- /.col -->
       

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
        <!-- /.col -->
        
        <!-- /.col -->
      </div>
      <!-- /.row -->  
       <br>                                                                
      <a href="../recibos/transfer_history.php?dia=<?php echo $_POST['date_1']?>&dia2=<?php echo $_POST['date_2']?>" target="_blank"><img src="../images/pdf.ico" width="30px" title="Visualizar">Visualizar</a>
      <br>
      <br>
        <table id="salesreporttable" class="table table-striped">
            
        <thead>
        <tr>
            <th>ID</th>
            <th>ID do Produto</th>
            <th>Inicial</th>
            <th>Transferido</th>
            <th>Final</th>
            <th>Data de Transferência</th>
                
        </tr>    
            
        </thead>              
        <tbody>
        <?php
            $select = $pdo->prepare("
                SELECT 
                    product_id, 
                    SUM(quantity) AS total_quantity, 
                    date_transfer 
                FROM tbl_transfer_history 
                WHERE date_transfer BETWEEN :fromdate AND :todate 
                GROUP BY product_id, date_transfer
            ");
            $select->bindParam(':fromdate', $_POST['date_1']);  
            $select->bindParam(':todate', $_POST['date_2']);  

            $select->execute();

            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                // ID do produto transferido
                $produto = $row->product_id;

                // Buscar informações do produto na tabela tbl_product
                $cons = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE pid = '$produto'");
                $res = mysqli_fetch_array($cons);
                $product_name = $res['pname'];

                // Buscar informações do estoque inicial na tabela tbl_armazem
                $cons1 = mysqli_query($mysqli, "SELECT * FROM tbl_armazem WHERE pid = '$produto'");
                $res1 = mysqli_fetch_array($cons1);
                $stock_inicial = $res1['pstock'];

                // Exibir os resultados na tabela
                echo '
                <tr>
                    <td>' . $produto . '</td>
                    <td>' . $product_name . '</td>
                    <td>' . number_format($stock_inicial + $row->total_quantity) . '</td>
                    <td>' . $row->total_quantity . '</td>
                    <td>' . $stock_inicial . '</td>
                    <td>' . $row->date_transfer . '</td>
                </tr>
                ';
            }
            ?>


        </tbody>               
    </table>                                                                 
               
    </div>
    </form>

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






