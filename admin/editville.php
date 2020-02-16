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
		$codepostal = AntiInjectionSQL($_REQUEST['codepostal']);
		$longitude = AntiInjectionSQL($_REQUEST['longitude']);
		$latitude = AntiInjectionSQL($_REQUEST['latitude']);
		$departement = AntiInjectionSQL($_REQUEST['departement']);
		$region = AntiInjectionSQL($_REQUEST['region']);
		
		$slug = slugify($nom);
		
		$SQL = "UPDATE pas_ville SET nom = '$nom' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_ville SET slug = '$slug' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_ville SET codepostal = '$codepostal' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_ville SET latitude = '$latitude' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_ville SET longitude = '$longitude' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_ville SET iddepartement = $departement WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_ville SET idregion = $region WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: ville.php");
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
	
	$SQL = "SELECT * FROM pas_ville WHERE id = $id";
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
			<a href="ville.php" class="btn blue">Retour</a>
		</div>
		<H1>Modification de la ville "<?php echo $req['nom']; ?>"</H1>
		<form>
			<label>Nom :</label>
			<input type="text" name="nom" value="<?php echo $req['nom']; ?>" class="inputbox">
			<label>Code postal :</label>
			<input type="text" name="codepostal" value="<?php echo $req['codepostal']; ?>" class="inputbox">
			<label>Longitude :</label>
			<input type="text" name="longitude" value="<?php echo $req['longitude']; ?>" class="inputbox">
			<label>Latitude :</label>
			<input type="text" name="latitude" value="<?php echo $req['latitude']; ?>" class="inputbox">
			<label>Département :</label>
			<select name="departement" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_departement";
				$reponse = $pdo->query($SQL);
				while($r = $reponse->fetch())
				{
					if($req['iddepartement'] == $r['departement_id'])
					{
						?>
						<option value="<?php echo $r['departement_id']; ?>" selected><?php echo $r['departement_nom']; ?></option>
						<?php
					}
					else
					{
						?>
						<option value="<?php echo $r['departement_id']; ?>"><?php echo $r['departement_nom']; ?></option>
						<?php
					}
				}
				
				?>
			</select>
			<label>Région :</label>
			<select name="region" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_region";
				$reponse = $pdo->query($SQL);
				while($r = $reponse->fetch())
				{
					if($req['idregion'] == $r['id'])
					{
						?>
						<option value="<?php echo $r['id']; ?>" selected><?php echo $r['titre']; ?></option>
						<?php
					}
					else
					{
						?>
						<option value="<?php echo $r['id']; ?>"><?php echo $r['titre']; ?></option>
						<?php
					}
				}
				
				?>
			</select>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>