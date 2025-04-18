<?php 

	try{
		$pdo = new PDO('mysql:host=localhost; dbname=maphezu','root','');
		//echo "Conectado com sucesso";
	}	
	catch(PDOException $e){

		 echo "Erro ao estabelecer a conexão"." ".$e->getmessage();
	}
	

 ?>