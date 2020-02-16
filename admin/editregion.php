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

$id = AntiInjectionSQL($_REQUEST['id']);

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$nom = AntiInjectionSQL($_REQUEST['nom']);
		$idmap = AntiInjectionSQL($_REQUEST['idmap']);
		
		$slug = slugify($nom);
		
		$SQL = "UPDATE pas_region SET titre = '$nom' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_region SET slug = '$slug' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_region SET idmap = '$idmap' WHERE id = $id";
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
	
	$SQL = "SELECT * FROM pas_region WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
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
		<H1>Modification de la région "<?php echo $req['titre']; ?>"</H1>
		<form>
			<label>Nom de la région :</label>
			<input type="text" name="nom" value="<?php echo $req['titre']; ?>" class="inputbox">
			<label>ID d'association à la carte :</label>
			<input type="text" name="idmap" value="<?php echo $req['idmap']; ?>" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>