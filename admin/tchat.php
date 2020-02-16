<?php

include "../config.php";
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
		$code_tawk = $_REQUEST['code_tawk'];
		
		updateConfig("code_tawk",$code_tawk);		
		header("Location: tchat.php");
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
		<H1>Module Tchat</H1>
		<div class="info">
		Le module Tchat vous permet d'integrer à votre site internet un Tchat interactif pour discuter avec vos utilisateur en temps-réel pour les aider, les conseiller et répondre à leur question. Pour l'integrer à votre site internet vous pouvez utiliser le Tchat gratuit de Tawk.to, il vous suffit de vous inscrire <a href="https://www.tawk.to/" target="tawk">ici</a> à cette adresse et d'indiquer le code Tchat dans le bloc plus bas et d'enregistrer. 
		</div>
		<form method="POST">
			<label>Code Tawk.to :</label>
			<textarea name="code_tawk" placeholder="Code HTML fourni par Tawk.to" class="textareabox"><?php echo getConfig("code_tawk"); ?></textarea>
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>