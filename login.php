<?php
// Sistema de licença - Verificar a licença antes de processar login
function verificarLicenca() {
  $arquivo_licenca = 'licenca.txt'; // Arquivo onde a licença está armazenada
  
  // Verificar se o arquivo de licença existe
  if (!file_exists($arquivo_licenca)) {
    return false;
  }
  
  // Ler a licença do arquivo
  $licenca = trim(file_get_contents($arquivo_licenca));
  
  // Verificar se a licença não está vazia
  if (empty($licenca)) {
    return false;
  }
  
  // Decodificar a licença (formato: data_expiracao|chave_hash)
  $partes = explode('|', $licenca);
  
  if (count($partes) != 2) {
    return false;
  }
  
  $data_expiracao = $partes[0];
  $hash = $partes[1];
  
  // Verificar se a data de expiração é válida
  $hoje = date('Y-m-d');
  if ($hoje > $data_expiracao) {
    return false; // Licença expirada
  }
  
  // Verificar se o hash é válido (chave secreta = 'maphezu_sistema')
  $chave_secreta = 'maphezu_sistema';
  $hash_esperado = md5($data_expiracao . $chave_secreta);
  
  return ($hash === $hash_esperado);
}

// Verificar a licença antes de permitir o login
if (!verificarLicenca()) {
  // Redirecionar para a página de licença expirada
  header('Location: licenca_expirada.php');
  exit();
}

include("conexao.php");

$login = $_POST['txt_email'];
$entrar = $_POST['btn_login'];
$senha = $_POST['txt_senha'];

if (isset($entrar)) 
{						    
	$verifica = mysqli_query($mysqli,"SELECT * FROM tbl_user WHERE BINARY  useremail = '$login' and  BINARY  password = '$senha' AND role = 'admin' ") or die("erro ao selecionar");
	if (mysqli_num_rows($verifica)>0)
	{
		session_start();
		setcookie("txt_email", $login, time() + (86400 * 30), "/"); 
		$_SESSION['txt_email'] = $login; 
		$_SESSION['txt_senha'] = $_POST['txt_senha'];
		header("Location:gestor/darshboard.php");


	}
	elseif (isset($entrar)) 
	{						    
		$verifica = mysqli_query($mysqli,"SELECT * FROM tbl_user WHERE BINARY  useremail = '$login' and  BINARY  password = '$senha' AND role = 'Caixa'") or die("erro ao selecionar");
		if(mysqli_num_rows($verifica)>0)
		{

			session_start();
			setcookie("txt_email", $login, time() + (86400 * 30), "/"); 
			$_SESSION['txt_email'] = $login; 
			$_SESSION['txt_senha'] = $_POST['txt_senha'];
			header("Location:venda/mesa.php");
		}
		else
		{
			echo("<script language='javascript' type='text/javascript'>alert('Nome de usuario e/ou senha incorretos');window.location.href='index.php';</script>");
		}
	}

}
?>

