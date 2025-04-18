<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';
error_reporting(1);



$id=$_GET['id'];

$sql="delete from tbl_armazem where pid=$id";

$delete=$pdo->prepare($sql);

if($delete->execute()){
  echo '<script>alert("Produto, Produto delatado sucesso!");</script>';
  echo '<script>window.location="list_armazem.php"</script>';

}else{

  echo '<script>alert("Error in Deleting");</script>';
}

?>