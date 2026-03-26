<?php

      include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';


include_once'header.php';


if(isset($_POST['btnsave'])){
    $title = $_POST['txttitle'];
    $description = $_POST['txtdescription'];
    $amount = $_POST['txtamount'];
    //$expense_type = $_POST['txtexpensetype'];
    $category_id = isset($_POST['txtcategory']) && $_POST['txtcategory'] !== '' ? $_POST['txtcategory'] : null;
    $reference_month = isset($_POST['txtreferencemonth']) && $_POST['txtreferencemonth'] !== '' ? $_POST['txtreferencemonth'] : null;
    $paid = isset($_POST['txtpaid']) ? 1 : 0;
    $payment_date = isset($_POST['txtpaymentdate']) && $_POST['txtpaymentdate'] !== '' ? $_POST['txtpaymentdate'] : null;

    if(empty($title) || empty($amount) ){
       $error='<script type="text/javascript">
       jQuery(function validation(){
        swal({
          title: "Feild is Empty!",
          text: "Please Fill Feild!!",
          icon: "error",
          button: "Ok",
          });
        });
        </script>';   
        echo $error;  

      }

      if(!isset($error)){
        $insert=$pdo->prepare("insert into expenses(title,description,amount,category_id,reference_month,paid,payment_date) values(:title,:description,:amount,:category_id,:reference_month,:paid,:payment_date)");
        $insert->bindParam(':title',$title);
        $insert->bindParam(':description',$description);
        $insert->bindParam(':amount',$amount);
        //$insert->bindParam(':expense_type',$expense_type);
        $insert->bindParam(':category_id',$category_id);
        $insert->bindParam(':reference_month',$reference_month);
        $insert->bindParam(':paid',$paid,PDO::PARAM_INT);
        $insert->bindParam(':payment_date',$payment_date);
        if($insert->execute()){

        echo '<script type="text/javascript">
            jQuery(function validation(){
             Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Despesa salvo com sucesso",
                showConfirmButton: true,
                timer: 2000
                });
                });
                </script>';
            }else{
               echo '<script type="text/javascript">
               jQuery(function validation(){
                 Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Erro ao salvar a despesa",
                    showConfirmButton: false,
                    timer: 2000
                    });
                    });
                    </script>';
                }    

            }        

}

if(isset($_POST['btnupdate'])){
    $id = $_POST['txtid'];
    $title = $_POST['txttitle'];
    $description = $_POST['txtdescription'];
    $amount = $_POST['txtamount'];
    //$expense_type = $_POST['txtexpensetype'];
    $category_id = isset($_POST['txtcategory']) && $_POST['txtcategory'] !== '' ? $_POST['txtcategory'] : null;
    $reference_month = isset($_POST['txtreferencemonth']) && $_POST['txtreferencemonth'] !== '' ? $_POST['txtreferencemonth'] : null;
    $paid = isset($_POST['txtpaid']) ? 1 : 0;
    $payment_date = isset($_POST['txtpaymentdate']) && $_POST['txtpaymentdate'] !== '' ? $_POST['txtpaymentdate'] : null;

    if(empty($id) || empty($title) || empty($amount)){
        $errorupdate='<script type="text/javascript">
        jQuery(function validation(){
         Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Porfavor preencha os campos",
            showConfirmButton: false,
            timer: 2000
            });
            });
            </script>';    
        echo $errorupdate; 
    }

    if(!isset($errorupdate)){
        $update=$pdo->prepare("update expenses set title=:title, description=:description, amount=:amount, category_id=:category_id, reference_month=:reference_month, paid=:paid, payment_date=:payment_date where id=".$id);
        $update->bindParam(':title',$title);
        $update->bindParam(':description',$description);
        $update->bindParam(':amount',$amount);
        //$update->bindParam(':expense_type',$expense_type);
        $update->bindParam(':category_id',$category_id);
        $update->bindParam(':reference_month',$reference_month);
        $update->bindParam(':paid',$paid,PDO::PARAM_INT);
        $update->bindParam(':payment_date',$payment_date);

        if($update->execute()){
             echo '<script type="text/javascript">
             jQuery(function validation(){
                 Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Actualizado com sucesso",
                    showConfirmButton: false,
                    timer: 2000
                    });
                    });
                    </script>';   

                }else{

                  echo '<script type="text/javascript">
                  jQuery(function validation(){
                     Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Erro ao actualizar",
                        showConfirmButton: false,
                        timer: 2000
                        });
                        });
                        </script>';
                    }    
                }

}

if(isset($_POST['btndelete'])){
    $delete=$pdo->prepare("delete from expenses where id=".$_POST['btndelete']); 
    if($delete->execute()){
        echo '<script type="text/javascript">
        jQuery(function validation(){
           Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Deletado com sucesso",
              showConfirmButton: false,
              timer: 2000
              });
              });
              </script>'; 
    }else{
        echo '<script type="text/javascript">
        jQuery(function validation(){
            Swal.fire({
               position: "top-end",
               icon: "error",
               title: "Error ao deletar",
               showConfirmButton: false,
               timer: 2000
               });
               });
               </script>';
    }
}

if(isset($_POST['btnpaytoggle'])){
    $id = $_POST['btnpaytoggle'];
    $selectPaid = $pdo->prepare("select paid from expenses where id=".$id);
    $selectPaid->execute();
    $current = $selectPaid->fetch(PDO::FETCH_OBJ);
    if($current){
        $newPaid = $current->paid ? 0 : 1;
        $newPaymentDate = $newPaid ? date('Y-m-d') : null;
        $updatePaid=$pdo->prepare("update expenses set paid=:paid, payment_date=:payment_date where id=".$id);
        $updatePaid->bindParam(':paid',$newPaid,PDO::PARAM_INT);
        $updatePaid->bindParam(':payment_date',$newPaymentDate);
        if($updatePaid->execute()){
            echo '<script type="text/javascript">
            jQuery(function validation(){
               Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: "Actualizado com sucesso",
                  showConfirmButton: false,
                  timer: 1500
                  });
                  });
                  </script>';
        }
    }
}

$categories = [];
$selectCats = $pdo->prepare("select * from expense_categories order by name asc");
$selectCats->execute();
while($cat = $selectCats->fetch(PDO::FETCH_OBJ)){
    $categories[] = $cat;
}

?>

        <div class="content-wrapper">

            <section class="content-header">

                <h1>
                    Despesas
                    <small></small>

                </h1>

                <ol class="breadcrumb">

                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>

                    <li class="active">Here</li>

                </ol>

            </section>

            <section class="content container-fluid">

        <div class="box box-warning">

            <div class="box-header with-border">

                <h3 class="box-title">Formulario de Despesa</h3>

            </div>

            <div class="box-body">

                <form role="form" action="" method="post">

                    <?php

                    if(isset($_POST['btnedit'])){

                        $select=$pdo->prepare("select * from expenses where id=".$_POST['btnedit']);
                        $select->execute();

                        if($select){

                            $row =$select->fetch(PDO::FETCH_OBJ);

                            $paidChecked = $row->paid ? 'checked' : '';

                            echo' 
                            <div class="col-md-4">
                              <div class="form-group">
                                <label >Titulo</label>
                                <input type="hidden" class="form-control" value="'.$row->id.'" name="txtid" >
                                <input type="text" class="form-control" value="'.htmlspecialchars($row->title, ENT_QUOTES).'" name="txttitle" placeholder="Titulo" >
                              </div>

                              <div class="form-group">
                                <label >Descrição</label>
                                <textarea class="form-control" name="txtdescription" rows="3" placeholder="Descrição">'.htmlspecialchars($row->description ?? '', ENT_QUOTES).'</textarea>
                              </div>

                              <div class="form-group">
                                <label >Valor</label>
                                <input type="number" step="0.01" class="form-control" value="'.$row->amount.'" name="txtamount" placeholder="0.00" >
                              </div>

                              <div class="form-group">
                                <label >Categoria</label>
                                <select class="form-control" name="txtcategory">
                                  <option value="">Sem categoria</option>';

                              foreach($categories as $c){
                                $sel = ($row->category_id == $c->id) ? 'selected' : '';
                                echo '<option value="'.$c->id.'" '.$sel.'>'.$c->name.'</option>';
                              }

                              echo'</select>
                              </div>

                              <div class="form-group">
                                <label >Mês de Referência</label>
                                <input type="date" class="form-control" value="'.($row->reference_month ?? '').'" name="txtreferencemonth" >
                              </div>

                              <div class="form-group">
                                <label>
                                  <input type="checkbox" name="txtpaid" '.$paidChecked.'> Paga
                                </label>
                              </div>

                              <div class="form-group">
                                <label >Data do Pagamento</label>
                                <input type="date" class="form-control" value="'.($row->payment_date ?? '').'" name="txtpaymentdate" >
                              </div>

                              <button type="submit" class="btn btn-info" name="btnupdate">Actualizar</button>
                            </div>';
                        }

                    }else{

                        echo' 
                        <div class="col-md-4">
                          <div class="form-group">
                            <label >Titulo</label>
                            <input type="text" class="form-control" name="txttitle" placeholder="Titulo" >
                          </div>

                          <div class="form-group">
                            <label >Descrição</label>
                            <textarea class="form-control" name="txtdescription" rows="3" placeholder="Descrição"></textarea>
                          </div>

                          <div class="form-group">
                            <label >Valor</label>
                            <input type="number" step="0.01" class="form-control" name="txtamount" placeholder="0.00" >
                          </div>

                          <div class="form-group">
                            <label >Categoria</label>
                            <select class="form-control" name="txtcategory">
                              <option value="">Sem categoria</option>';

                          foreach($categories as $c){
                            echo '<option value="'.$c->id.'">'.$c->name.'</option>';
                          }

                          echo'</select>
                          </div>

                          <div class="form-group">
                            <label >Mês de Referência</label>
                            <input type="date" class="form-control" name="txtreferencemonth" >
                          </div>

                          <div class="form-group">
                            <label>
                              <input type="checkbox" name="txtpaid"> Paga
                            </label>
                          </div>

                          <div class="form-group">
                            <label >Data do Pagamento</label>
                            <input type="date" class="form-control" name="txtpaymentdate" >
                          </div>

                          <button type="submit" class="btn btn-warning" name="btnsave">Salvar</button>
                        </div>';    

                        }

                    ?>

                    <div class="col-md-8">
                        <table id="tableexpenses" class="table table-striped">
                            <thead>
                                <tr>
                                   <th>#</th>
                                   <th>Titulo</th>
                                   <th>Valor</th>
                                   <th>Categoria</th>
                                   <th>Mês</th>
                                   <th>Paga</th>
                                   <th>Pagamento</th>
                                   <th>Editar</th>
                                   <th>Apagar</th>
                               </tr>
                           </thead>
                           <tbody>
                            <?php

                            $select=$pdo->prepare("select e.*, c.name as category_name from expenses e left join expense_categories c on c.id = e.category_id order by e.id desc");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                                $paidLabel = $row->paid ? '<span class="label label-success">Sim</span>' : '<span class="label label-default">Não</span>';

                                echo' <tr>
                                <td>'.$row->id.'</td>
                                <td>'.htmlspecialchars($row->title, ENT_QUOTES).'</td>
                                <td>'.number_format((float)$row->amount,2).'</td>
                                <td>'.htmlspecialchars($row->category_name ?? '', ENT_QUOTES).'</td>
                                <td>'.($row->reference_month ?? '').'</td>
                                <td>
                                  <button type="submit" value='.$row->id.' class="btn btn-xs btn-primary" name="btnpaytoggle">'.$paidLabel.'</button>
                                </td>
                                <td>'.($row->payment_date ?? '').'</td>
                                <td>
                                <button type="submit" value='.$row->id.' class="btn btn-success" name="btnedit">Edit</button>
                                </td>
                                <td>
                                <button type="submit" value="'.$row->id.'" class="btn btn-danger" name="btndelete">Delete</button>

                                </td>
                                </tr>';    
                                }              

                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="box-footer">
        </div>
    </div>

</section>

</div>

<script>

  $(document).ready( function () {

    $('#tableexpenses').DataTable();

} );  

</script>

<?php

include_once'footer.php';

?>
