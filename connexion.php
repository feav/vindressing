<?php

include "main.php";

$email = AntiInjectionSQL($_REQUEST['email']);
$password = AntiInjectionSQL($_REQUEST['password']);

$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email' AND password = '$password'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: login.php?error=1");
	exit;
}
else
{
	$SQL = "SELECT * FROM pas_user WHERE email = '$email' AND password = '$password'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$validate_account = $req['validate_account'];
	if($validate_account == 'yes')
	{	
		if($req['ban'] == 'yes')
		{
			header("Location: login.php?error=3");
			exit;
		}
		else
		{
			session_start();
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $password;
			$_SESSION['pseudo'] = $req['username'];
			$_SESSION['type_compte'] = $req['type_compte'];
			
			/* On update les favoris si besoin */
			$class_favoris->updateFavoris();
			
			header("Location: index.php");
			exit;
		}		
	}
	else
	{
		header("Location: login.php?error=2");
		exit;
	}
}

?>