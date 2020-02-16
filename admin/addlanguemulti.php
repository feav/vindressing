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

if(isset($_REQUEST['language']))
{
	$language = $_REQUEST['language'];
}
else
{
	$language = 'fr';
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	/* Langue principale du site internet */
	if($action == 1)
	{
		$currency = AntiInjectionSQL($_REQUEST['currency']);
		$langue = AntiInjectionSQL($_REQUEST['langue']);
		
		$SQL = "INSERT INTO pas_langue_multi (langue,currency) VALUES ('$langue','$currency')";
		$pdo->query($SQL);
		
		header("Location: langue.php");
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.tabs
	{
		padding: 10px;
		background-color: #ffffff;
		box-sizing: border-box;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}
	
	.tabs-element
	{
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		float: left;
		padding: 10px;
		background-color: #dddddd;
		margin-right: 1px;
	}
	
	.tabs-element:hover
	{
		background-color: #ffdddd;
	}
	
	.tabs-cover
	{
		overflow:auto;
	}
	
	.block-traduction
	{
		margin-bottom: 10px;
		padding: 10px;
		background-color: #aaaaaa;
		border-radius: 5px;
		padding-top: 18px;
	}
	
	.block-traduction-item
	{
		float: left;
		margin-right: 10px;
	}
	
	.language-change
	{
		height: 35px;
		width: 400px;
		margin-top: -10px;
		padding-left: 10px;
	}
	</style>
	<div class="container">
		<H1>Ajouter une langue à votre site pour le Multilanguage</H1>
		<div style="margin-top:10px;margin-bottom:10px;">
			<a href="langue.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label><b>Langue :</b></label>
			<select name="langue" class="inputbox">
			<?php
			
			$SQL = "SELECT * FROM pas_langue_add";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<option value="<?php echo $req['language']; ?>"><?php echo $req['language_texte']; ?></option>
				<?php
			}
			
			?>
			</select>
			<label><b>Monnaie</b></label>
			<select name="currency" class="inputbox">
			<?php
			
			$class_monetaire = new Monetaire();
			$array = $class_monetaire->getAllCurrencySupported();
			for($x=0;$x<count($array);$x++)
			{
				?>
				<option value="<?php echo $array[$x]['currency']; ?>"><?php echo $array[$x]['titre']." (".$array[$x]['sigle'].")"; ?></option>
				<?php
			}
			
			?>
			</select>
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>