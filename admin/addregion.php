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
		$nom = AntiInjectionSQL($_REQUEST['nom']);
		$idmap = AntiInjectionSQL($_REQUEST['idmap']);
		
		$slug = slugify($nom);
		
		$SQL = "INSERT INTO pas_region (titre,slug,code,idmap) VALUES ('$nom','$slug','','$idmap')";
		$pdo->query($SQL);
		
		header("Location: region.php");
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
	<style>
	label
	{
		font-weight:bold;
	}
	</style>
	<div class="container">
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="region.php" class="btn blue">Retour</a>
		</div>
		<H1>Ajouter une nouvelle région</H1>
		<form>
			<label>Nom de la région :</label>
			<input type="text" name="nom" value="" class="inputbox">
			<label>ID d'association à la carte :</label>
			<input type="text" name="idmap" value="" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>