<?php 

	try{
		$pdo = new PDO('mysql:host=localhost; dbname=db_texas','root','');
		//echo "Conectado com sucesso";
	}	
	catch(PDOException $e){

		 echo "Erro ao estabelecer a conexão"." ".$e->getmessage();
	}
	

 ?>