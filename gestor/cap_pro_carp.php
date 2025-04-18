<?php 


include_once '../dbconnect.php'; 

$id = $_GET["id"];

$select=$pdo->prepare("select * from tbl_producto_carp where id = :pid");
$select->bindParam(':pid',$id);
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);


$respone=$row;

header('Content-Type: application/json');

echo json_encode($respone);



 ?>