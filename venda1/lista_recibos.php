<?php 
include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';




if($_SESSION['role']=="Admin"){


 include_once'header.php';  
}else{

  include_once'headeruser.php';   
}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
     Painel de Vendedor
     <small></small>
   </h1>
   <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Level</a></li>
    <li class="active">Here</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
  
            <div class="box-header with-border">
              <h3 class="box-title">Listas de Mesas Pagas e Respeitivos Recibos</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="box-body">

              <div style="overflow-x:auto;" > 

               <table id="orderlisttable" class="table table-striped">
                <thead>
                  <tr>
                    <th>Mesa</th>
                    <th>Data</th>   
                    <th>Total</th>   
                    <th>Print</th> 
                          
                  </tr>    

                </thead> 



                <tbody>

                  <?php
                  $select=$pdo->prepare("select * from tbl_invoice order by invoice_id asc");

                  $select->execute();

                  while($row=$select->fetch(PDO::FETCH_OBJ)  ){

                    echo'
                    <tr>
                    <td>'.$row->mesa.'</td>

                    <td>'.$row->order_date.'</td>
                    <td>'.$row->total.'</td>
                    <td>
                    <a href="../recibo/pdf.php?id='.$row->invoice_id.'" class="btn btn-warning" role="button" target="_blank"><span class="glyphicon glyphicon-print"  style="color:#ffffff" data-toggle="tooltip"  title="Print Recibo"></span></a>
                    </td>
                   
                    </tr>
                    ';

                  }          
                  ?>        

                </tbody>               
              </table>  </div>     








            </div>
            <!--              </form>-->
          </div>




        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->


      <script>
        $(document).ready( function () {
          $('#orderlisttable').DataTable({
            "order":[[0,"desc"]]    
          });
        } );  


      </script>

      <script>
        $(document).ready( function () {
          $('[data-toggle="tooltip"]').tooltip();
        } );  


        $(document).ready(function() {
          $('.btndelete').click(function() {
            var tdh = $(this);
            var id = $(this).attr("id");
            swal({
              title: "Desejas apagar a venda?",
              text: "Deletado, voçe não podera recupera-lo!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

               $.ajax({
                url: 'orderdelete.php',
                type: 'post',
                data: {
                  pidd: id
                },
                success: function(data) {
                  tdh.parents('tr').hide();
                }


              });



               swal("Your Order has been deleted!", {
                icon: "success",
              });
             } else {
              swal("Your Order is safe!");
            }
          });
            


          });
        });     






      </script>




      <?php

      include_once'footer.php';

      ?>





