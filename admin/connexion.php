<?php

include "../config.php";

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

/* Injection SQL ici visible '%23 */

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = :username AND password = :password";
$reponse = $pdo->prepare($SQL);
$reponse->bindValue(':username',$username,PDO::PARAM_STR);
$reponse->bindValue(':password',$password,PDO::PARAM_STR);
$reponse->execute();
$req = $reponse->fetch();

/*$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '$username' AND password = '$password'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();*/

if($req[0] == 0)
{
	/* Le mot de passe ou l'utilisateur est invalide */
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$SQL = "SELECT COUNT(*) FROM connect_check WHERE username = '$username' AND ip = '$ip'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	$count = $req[0];
	
	if($count > 3)
	{
		header("Location: index.php?error=2");
		exit;
	}
	else
	{
		$SQL = "INSERT INTO connect_check (username,ip) VALUES ('$username','$ip')";
		$pdo->query($SQL);
		header("Location: index.php?error=1");
		exit;
	}
}
else
{
	session_start();
	
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$SQL = "SELECT COUNT(*) FROM connect_check WHERE username = '$username' AND ip = '$ip'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	$count = $req[0];
	
	if($count > 2)
	{
		header("Location: index.php?error=2");
		exit;
	}
	
	$SQL = "SELECT * FROM pas_admin_user WHERE username = :username AND password = :password";
	$reponse = $pdo->prepare($SQL);
	$reponse->bindValue(':username',$username,PDO::PARAM_STR);
	$reponse->bindValue(':password',$password,PDO::PARAM_STR);
	$reponse->execute();
	$req = $reponse->fetch();
	
	$_SESSION['admin_username'] = $username;
	$_SESSION['admin_password'] = $password;
	$_SESSION['admin_type_compte'] = $req['type_compte'];
	header("Location: home.php");
	exit;
}

?>