<?php 

	// Incluir arquivo de configuração
	require_once __DIR__ . '/db_config.php';
	
	try{
		$pdo = new PDO('mysql:host='.$db_host.'; dbname='.$db_name, $db_user, $db_pass);
		//echo "Conectado com sucesso";
	}	
	catch(PDOException $e){

		 echo "Erro ao estabelecer a conexão"." ".$e->getmessage();
	}
	
 ?>