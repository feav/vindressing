<?php

include "../main.php";
include "version.php";

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$username_admin = AntiInjectionSQL($_REQUEST['username_admin']);
		$password_admin = AntiInjectionSQL($_REQUEST['password_admin']);
			
		$SQL = "INSERT INTO pas_admin_user (username,password,type_compte) VALUES ('$username_admin','$password_admin','moderateur')";
		$pdo->query($SQL);
			
		header("Location: moderateur.php");
		exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Ajouter d'un compte modérateur</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
		<a href="moderateur.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label>Nom d'utilisateur :</label>
			<input type="text" class="inputbox" name="username_admin" placeholder="Nom d'utilisateur">
			<label>Mot de passe :</label>
			<input type="password" class="inputbox" name="password_admin" placeholder="Mot de passe de l'utilisateur">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>