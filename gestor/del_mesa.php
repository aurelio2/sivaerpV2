<?php

include_once '../dbconnect.php';
      include_once "../session.php";
      include_once '../conexao.php';

$idd=$_POST['pidd'];

$sql="delete from tbl_mesa where id=$idd";

$delete=$pdo->prepare($sql);


if($delete->execute()){


}else{

    

  echo'Error in Deleting';  

}


?>