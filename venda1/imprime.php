<?php

if(isset($_REQUEST['id'])&&($_REQUEST['id']!="")) {
$id = $_REQUEST['id'];

include_once'../dbconnect.php';

$data = date('Y-m-d h:m:i');
	
$d = new DateTime("now", new DateTimeZone("Africa/Maputo"));
$data= $d->format("r");	
$d = date("d");
$m = date("m");
$ano = date("Y");


$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select->execute();

$image="1";
$content="  \n";
$content.="QANI SALAO E CABELEREIRO\n";
$content.="NUIT: 120262971 \n";
$content.="Telefone: 845757617\n";
$content.="Email: qani@qani.co.mz\n\n\n";

$content.="RECIBO NUMERO ".$id."\n";
$h=date('H')+2;
$m=date('i');
$s=date('s');
$content.="DATA ".date('d/m/Y')." HORA ".$h.":".$m.":".$s."\n\n\n";

$content.="-----------------------------\n";
$content.="Qtd   item    Preco\n";
$content.="-----------------------------\n";
while($item=$select->fetch(PDO::FETCH_OBJ))
{ 

$content.= $item->qty."  ".$item->product_name."   ".number_format($item->price*$item->qty,2)." MT\n";

}
$content.="\n-----------------------------\n";
$select1=$pdo->prepare("select SUM(price)as total from tbl_invoice_details where invoice_id=$id");
$select1->execute();
$item1=$select1->fetch(PDO::FETCH_OBJ);
$content.="Total                ".number_format($item1->total,2)." MT\n";
$content.="\n-----------------------------\n";

$content.="       Muito Obrigado   \n";
$content.="      Visite-nos sempre!!\n";
$content.="\n\n\n\n";


//you can print text and image by sending request from your website
$a = array();
//sending image entry			
$obj2->type = 1;//image
$obj2->path = 'http://qani.co.mz/images/logo_barb.png';//complete filepath on your web server; make sure that it is not big size
$obj2->align = 1;//0 if left, 1 if center, 2 if right; set left align for big size images
array_push($a,$obj2);

//sending text entry	
$obj1->type = 0;//text
$obj1->content = $content;//any string	
$obj1->bold = 1;//0 if no, 1 if yes
$obj1->align =0;//0 if left, 1 if center, 2 if right
$obj1->format = 3;//0 if normal, 1 if double Height, 2 if double Height + Width, 3 if double Width
array_push($a,$obj1);

//sending barcode entry			
$obj3->type = 2;//barcode
$obj3->value = $id;//valid barcode value
$obj3->width = 400;//valid barcode width
$obj3->height = 100;//valid barcode height
$obj3->align = 1;//0 if left, 1 if center, 2 if right
array_push($a,$obj3);
//sending QR entry			
$obj4->type = 3;//QR code
$obj4->value = '';//valid QR code value
$obj4->size = 40;//valid QR code size in mm
$obj4->align = 2;//0 if left, 1 if center, 2 if right
array_push($a,$obj4);


echo json_encode($a,JSON_FORCE_OBJECT);

//Note that same sequence will be used for printing data

		} 


?>
