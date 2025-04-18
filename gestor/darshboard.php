  <?php 
      //session_start();
  include_once '../dbconnect.php';
  include_once "../session.php";
  include_once '../conexao.php';
  error_reporting(1);


  //     //Total em Dinheiro
  // $select = $pdo->prepare("select sum(total) as t , count(id) as inv from tbl_saidas");
  // $select->execute();
  // $row=$select->fetch(PDO::FETCH_OBJ);

  // $total_order=$row->inv;

  // $net_total=$row->t;

      //itens vendidos


  include_once 'header.php';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="bg-gray">
        Painel de Administrador
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Nivel</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
     <?php 
     $select = $pdo->prepare("select count(username) as u from tbl_user");
     $select->execute();
     $row=$select->fetch(PDO::FETCH_OBJ);

     $total_user=$row->u;

     ?>

     <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-blue">
        <div class="inner">
          <h3><?php echo $total_user;?></h3>

          <p>Usuarios existentes</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="registration.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <?php 
    $select = $pdo->prepare("SELECT COUNT(pid) as p FROM tbl_product WHERE pstock =0");
    $select->execute();
    $row=$select->fetch(PDO::FETCH_OBJ);

    $stock_0=$row->p;

    ?>
    <!--<h3>Producto com stock 0....</h3>-->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3><?php echo $stock_0;?></h3>
          <p>Producto com Stock 0</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="ListStock0.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

          <?php 
          $select = $pdo->prepare("select sum(purchaseprice*pstock) as compra from tbl_product");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);

          $total_compras=$row->compra;
          ?>
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
               <h3><?php echo number_format($total_compras,2);?></h3>
               
               <h4>Total de compras</h4>
             </div>
             <div class="icon">
              p
            </div>
            <a href="productlist.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>



        <?php
        $select = $pdo->prepare("select sum(total) as t , count(invoice_id) as inv from tbl_invoice");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total_order=$row->inv;

        $net_total=$row->t;
        ?>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
             <h3><?php echo number_format($net_total,2);?></h3>

             <p>Total vendido</p>
           </div>
           <div class="icon">
            <i class="ion-cash">MT</i>
          </div>
          <a href="#" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
        

      </div>
      <!--<h3>Producto existentes no sistema....</h3>-->
      <?php 
      $select = $pdo->prepare("select count(pname) as p from tbl_product");
      $select->execute();
      $row=$select->fetch(PDO::FETCH_OBJ);

      $total_produto=$row->p;
      ?>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
           <h3><?php echo $total_produto;?></h3>

           <h4>Listagem de Produtos</h4>
         </div>
         <div class="icon">
          p
        </div>
        <a href="productlist.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

          
          <?php 
          $select = $pdo->prepare("select sum(pstock) as p from tbl_product");
          $select->execute();
          $row=$select->fetch(PDO::FETCH_OBJ);

          $total_stock=$row->p;
          ?>
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
               <h3><?php echo $total_stock; ?></h3>
               
               <p>Stock Geral</p>
             </div>
             <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>    
        </div>


        <?php 
        $select = $pdo->prepare("select sum(saleprice*pstock) as vendas from tbl_product");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);

        $total_vendas=$row->vendas;
        ?>
        <div class="col-lg-3 col-xs-6">

          <div class="small-box bg-green">
            <div class="inner">
             <h3><?php  echo number_format($total_vendas,2); ?> MT</h3>

             <p>Total de vendas</p>
           </div>
           <div class="icon">
            <i class="ion ion-cash"></i>
          </div>
          <a href="lucro.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>    
      </div>

      <?php 
      $select = $pdo->prepare("select count(id) as devedores from tbl_devida where estado = '0'");
      $select->execute();
      $row=$select->fetch(PDO::FETCH_OBJ);

      $total_devedores=$row->devedores;
      ?>
      <div class="col-lg-3 col-xs-6">

        <div class="small-box bg-red">
          <div class="inner">
           <h3><?php  echo number_format($total_devedores); ?></h3>

           <p>Devedores</p>
         </div>
         <div class="icon">
          <i class="ion ion-cash"></i>
        </div>
        <a href="devedoreslist.php" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
      </div>    
    </div>
    
    <?php
        
     $hoje = date('Y-m-d');

    $select = $pdo->prepare("
        SELECT 
            order_date,
            SUM(total) as total
        FROM 
            tbl_invoice
        WHERE 
            MONTH(order_date) = MONTH(:hoje)
            AND YEAR(order_date) = YEAR(:hoje)
        GROUP BY 
            order_date
        LIMIT 30
    ");
    $select->bindParam(':hoje', $hoje);

     
            
    $select->execute();
                  
    $ttl=[];
    $date=[];              
                
    while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
        
    extract($row);
        
        $ttl[]=$total;
        $date[]=$order_date;
        
        
    }
    
    ?>

    <div class="">
      <div class="box-header with-border">
        <h3 class="box-title">Earning By Date</h3>
      </div>

      <div class="box-body">        
        <div class="chart">
          <canvas id="earningbydate" style="height:250px"></canvas>
        </div>         
      </div>
    </div>

            <div class="col-md-6">

              <div class="box box-info">
               <div class="box-header with-border">
                <h3 class="box-title">Best Selling Product</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <div class="box-body">        

                <table id="bestsellingproductlist" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Product ID</th>
                      <th>Product Name</th>   
                      <th>Qty</th>
                      <th>Price</th>   
                      <th>Total</th>           
                    </tr>               
                  </thead>                    
                  <tbody>

                    <?php
                    $select=$pdo->prepare(" SELECT 
                                product_id,
                                product_name,
                                price,
                                SUM(qty) as q,
                                SUM(qty * price) as total
                            FROM 
                                tbl_invoice_details
                            GROUP BY 
                                product_id
                            ORDER BY 
                                SUM(qty) DESC
                            LIMIT 15");

                    $select->execute();

                    while($row=$select->fetch(PDO::FETCH_OBJ)  ){

                      echo'
                      <tr>
                      <td>'.$row->product_id.'</td>
                      <td>'.$row->product_name.'</td>
                      <td><span class="label label-info">'.$row->q.'</span></td>
                      <td><span class="label label-success">'.$row->price.'MT</span></td>
                      <td><span class="label label-danger">'.$row->total.'MT</span></td>



                      </tr>
                      ';

                    }          
                    ?>        

                  </tbody>               
                </table>         


              </div></div>    


            </div>

            <div class="col-md-6">
             <div class="box box-info">
               <div class="box-header with-border">
                <h3 class="box-title">Recent Orders</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <div class="box-body">        

                <table id="orderlisttable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Invoice ID</th>
                      <th>Use</th>   
                      <th>Order Date</th>   
                      <th>Total</th>   
                    </tr>    
                  </thead> 

                  <tbody>
                    <?php
                    $select=$pdo->prepare("select * from tbl_invoice  order by invoice_id desc LIMIT 15");

                    $select->execute();

                    while($row=$select->fetch(PDO::FETCH_OBJ)  ){

                      echo'
                      <tr>
                      <td><a href="editorder.php?id='.$row->invoice_id.'">'.$row->invoice_id.'</a></td>
                      <td>'.$row->customer_name.'</td>
                      <td>'.$row->order_date.'</td>
                      <td><span class="label label-danger">'.$row->total.'MT</span></td>';


                   }          
                   ?>        

                 </tbody>               
               </table>  


             </div>

           </div>    




           </div>       

          
      
       </section>
       <!-- /.content -->
     </div>
     <!-- /.content-wrapper -->
       <script>
      var ctx = document.getElementById('earningbydate').getContext('2d');
      var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'bar',

        // The data for our dataset
        data: {
          labels: <?php echo json_encode($date);?>,
          datasets: [{
            label: 'Total Earning',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',

            data:<?php echo json_encode($ttl);?>
          }]
        },

        // Configuration options go here
        options: {}
      });




    </script>
    <?php 
    include_once 'footer.php'
  ?>