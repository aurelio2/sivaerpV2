<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';


$id=$_POST['pidd'];
$sql="delete from tbl_tipo where id=$id";
$delete=$pdo->prepare($sql);

if($delete->execute()){

}else{

  echo'Error in Deleting';  

}


?>