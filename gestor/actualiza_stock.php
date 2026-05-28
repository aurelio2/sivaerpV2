<link rel="stylesheet" type="text/css" href="../select2Pro/select2.min.css">
<script src="../select2Pro/select2.min.js"></script>

<?php
      include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';

include_once 'header.php';

$data_hoje = date('Y-m-d');

if (isset($_POST['btn_stock'])) {
  $order_date=date('Y-m-d',strtotime($_POST['orderdate']));
  $idproduto=$_POST['txtcodigo'];
  $quantidade=$_POST['novoQtd'];
  $stock =$_POST['pstock'];
  $pro_name =$_POST['txtproduto'];
  //$status=$_POST['txtestado'];
  $ajustado=$_POST['qtdAjust'];
  $dataajus=date('Y-m-d',strtotime($_POST['date_ajuste']));
  
  if(empty($pro_name || empty($quantidade))){
    echo '<script>alert("Porfavor escolha o nome de produto");</script>';
  }else{

    $inserir=$pdo->prepare("insert into tbl_entradas(idproduto,quantidade,qtd_anterior,produto,data,ajust,data_ajust)values(:idpro,:qtd,:qtd_ant,:pro,:data,:ajust,:data_ajust)");
    $inserir->bindParam(":idpro",$idproduto);
    $inserir->bindParam(":qtd",$quantidade);
    $inserir->bindParam(":qtd_ant",$stock);
    $inserir->bindParam(":data",$order_date);
    $inserir->bindParam(":pro",$pro_name);
    $inserir->bindParam(":ajust",$ajustado);
    $inserir->bindParam(":data_ajust",$dataajus);
    $inserir->execute(); 

    $aumentar=$stock+$quantidade;
    $update=$pdo->prepare("UPDATE tbl_armazem set pstock='$aumentar' where pid='".$idproduto."'");

    if ($update->execute()) {
      echo '
      <script>alert("Obrigado, Stock actualizado com sucesso!");</script>
      ';
    }else{
      echo '<script>alert("Error".$e->getmessage());</script>';
    }  
  }

  
}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Entrada de Produtos
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
          <div class="box-header with-border">
            <h6 class="box-title">Clica o botão para aumentar a quantidade do produto existente</h6><br>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddUsuario">Procurar Produto aqui <i class="fa fa-plus"></i></button> <br>
          </div>
          <!-- /.box-header -->
          <!-- form start -->

          <div class="box-body">
            <form role="form" action="" method="post">
              <div class="col-md-12">
                <table class="table table-striped" id="producttable">
                  <thead>
                    <tr>
                      <tr>
                        <td align="right">Codigo</td> 
                        <td align="right">Produto</td>                     
                        <td align="right">Qtd.Entrada</td>
                        <td align="right">Qtd.anterior</td>
                        <td align="right">Ajustado</td>
                        <td align="right">Total</td>
                        <td align="right">Data</td>
                        <td align="right">Ajuste </td>

                      </tr>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $select1=$pdo->prepare("select * from tbl_entradas order by data desc ");
                    $select1->execute();
                    while ($row=$select1->fetch(PDO::FETCH_OBJ)) {
                      $total = floatval($row->qtd_anterior) + floatval($row->quantidade) + floatval($row->ajust);
                      echo '
                      <tr>
                      <td align="right">'.$row->idproduto.'</td>
                      <td align="right">'.$row->produto.'</td>
                      <td align="right">'.number_format($row->quantidade, 2).'</td>
                      <td align="right">'.number_format($row->qtd_anterior, 2).'</td>
                      ';
                      if($row->ajust==0){
                        echo'<td align="right">-</td>';
                      }
                      else{
                        echo '
                        <td align="right">'.number_format($row->ajust, 2).'</td> 
                        '; 
                      }
                      echo ' 

                      <td align="right">'.number_format($total, 2).'</td>
                      <td align="right">'.$row->data.'</td>  
                       <td align="right">
                      ';

                      if($row->ajust==0){
                        echo'
                        
                         <a href="ajust.php?id='.$row->idproduto.'" class="btn btn-info" role="button"><i class="fa fa-spinner fa-spin" style="font-size:15px"></i> Ajustar</a> ';
                      }
                      echo'
                       
                      </td>        
                      </tr>';
                    }
                    ?>
                  </tbody>

                </table>


              </div>
               <div class="col-md-12">
                <h2>Entrada de Hoje</h2>
                <table class="table table-striped" id="producttable">
                  <thead>
                    <tr>
                      <tr>
                        <td align="right">Codigo</td> 
                        <td align="right">Produto</td>                     
                        <td align="right">Qtd.Entrada</td>
                        <td align="right">Qtd.anterior</td>
                        <td align="right">Ajustado</td>
                        <td align="right">Total</td>
                        <td align="right">Data</td>
                        <td align="right">Ajuste</td>

                      </tr>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $select1=$pdo->prepare("select * from tbl_entradas where
                     data = curdate()");
                    $select1->execute();
                    while ($row=$select1->fetch(PDO::FETCH_OBJ)) {
                      $total = floatval($row->qtd_anterior) + floatval($row->quantidade) + floatval($row->ajust);
                      echo '
                      <tr>
                      <td align="right">'.$row->idproduto.'</td>
                      <td align="right">'.$row->produto.'</td>
                      <td align="right">'.number_format($row->quantidade, 2).'</td>
                      <td align="right">'.number_format($row->qtd_anterior, 2).'</td>
                      ';
                      if($row->ajust==0){
                        echo'<td align="right">-</td>';
                      }
                      else{
                        echo '
                        <td align="right">'.number_format($row->ajust, 2).'</td> 
                        '; 
                      }
                      echo ' 

                      <td align="right">'.number_format($total, 2).'</td>
                      <td align="right">'.$row->data.'</td>  
                       <td align="right">
                      ';

                        echo'
                        <td>
                         <a href="ajust.php?id='.$row->idproduto.'" class="btn btn-info" role="button"><i class="fa fa-spinner fa-spin" style="font-size:15px"></i> Ajustar</a>
                          </td>
                          ';
                        
                      echo'
                       
                      </td>        
                      </tr>';
                    }
                    ?>
                  </tbody>
                </table>
               </div>
            </form>

          </section>
          <!-- /.content -->
        </div>
        <!--Modal para adicionar preco ao produto-->
        <div class="modal fade" id="modalAddUsuario">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" method="post" action="">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Actualizar Qtd</h4>

                  </div>
                  <div class="modal-body">
                    <div class="box-body">
                      <!--Nome-->
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                          <select class="form-control input-lg single" name="produto" id="piroduto" style="width:500px; height: 400px;" >
                            <option value="">Escolha o Produto</option>
                            <?php 
                            $buscar=$pdo->prepare("select * from tbl_armazem ORDER by saleprice");
                            $buscar->execute();

                            $resultado = $buscar->fetchAll();
                            foreach ($resultado as $key => $value) {
                              echo '
                              <option value="'.$value["pid"].'_'.$value['pstock'].'_'.$value['saleprice'].'_'.$value['pname'].'">'.$value["pid"].'-'.$value["pname"].'</option>
                              ';
                              
                            }

                            ?>
                          </select>
                        </div>


                        <div class="form-group" hidden>
                          <label>Produto</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                            <input type="text" name="txtproduto" class="form-control txtproduto" id="idpro" readonly="">
                            <input type="text" name="txtcodigo" class="form-control" id="idcodigo" readonly="">
                          </div>
                        </div>
                      </div>
                      <!--Stock-->
                      <div class="form-group">
                        <label>Stock existente</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                          <input type="number" class="form-control input-lg" id="pstock" name="pstock" readonly="">
                        </div>
                      </div>
                      <!--Quantidade-->
                      <div class="form-group">
                        <label>Preço</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                          <input type="number" class="form-control input-lg" id="preco" readonly="">
                        </div>
                      </div>

                      <!--Preco-->
                      <div class="form-group">
                        <label>Quantidade</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                          <input type="number" step="0.01" min="0.01" class="form-control input-lg" name="novoQtd" placeholder="Quantidade a inserir" required="">
                        </div>
                      </div>
                      <!--Estado-->
                      <div class="form-group">
                        <input type="hidden" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                        <input type="hidden" class="form-control pull-right" id="datepicker" name="txt_teste" value="entrada">
                      </div>

                      <div class="form-group" hidden>
                        <label >Ajuste +/-</label>
                        <input type="number" class="form-control" value="0" name="qtdAjust" placeholder="Quantidade a justar..." required>
                      </div>
                      <div class="form-group">
                        <input type="hidden" class="form-control pull-right" id="datepicker" name="date_ajuste" value="0000-000-00" data-date-format="yyyy-mm-dd">
                       
                      </div>                
                    </div>                         
                  </div>
                  <div class="modal-footer">
                    <button type="reset" class="btn btn-default pull-left" data-dismiss="modal">Sair <i class="fa fa-danger"></i></button>
                    <button type="submit" class="btn btn-success" name="btn_stock"><i class="fa fa-plus"></i>Actualizar</button>
                  </div>

                </form>

              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.content-wrapper -->
          
          <script type="text/javascript">
              $(document).ready( function () {
              $('#producttable').DataTable({
                "order":[[0,"desc"]]    
              });
            } ); 

            $("#piroduto").change(mostrarValores);

            function mostrarValores(){

              dadosProduto=document.getElementById('piroduto').value.split('_');
              $("#idpro").val(dadosProduto[3]);
              $("#pstock").val(dadosProduto[1]);
              $("#preco").val(dadosProduto[2]);
              $("#idcodigo").val(dadosProduto[0])
              //$("#txtProduto").val(dadosProduto[3]);


            }
          </script>
          
          <script type="text/javascript">
            $(document).ready(function() {
            $('.single').select2();
        });
          </script>
          <?php
          include_once'footer.php';
        ?>