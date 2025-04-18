<?php

include("conexao.php");

$login = $_POST['txt_email'];
$entrar = $_POST['btn_login'];
$senha = $_POST['txt_senha'];

if (isset($entrar)) 
{						    
	$verifica = mysqli_query($mysqli,"SELECT * FROM tbl_user WHERE BINARY  useremail = '$login' and  BINARY  password = '$senha' AND role = 'admin'") or die("erro ao selecionar");
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

