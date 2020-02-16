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
		$region = AntiInjectionSQL($_REQUEST['region']);
		
		$slug = slugify($nom);
		
		$SQL = "INSERT INTO pas_departement (idregion,departement_nom,departement_nom_uppercase,departement_slug) VALUES ($region,'$nom','".strtoupper($nom)."','$slug')";
		$pdo->query($SQL);
			
		header("Location: departement.php");
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
			<a href="departement.php" class="btn blue">Retour</a>
		</div>
		<H1>Ajouter une nouveaux département</H1>
		<form>
			<label>Nom du département :</label>
			<input type="text" name="nom" value="" class="inputbox">
			<label>Région associer :</label>
			<select name="region" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_region";
				$reponse = $pdo->query($SQL);
				while($r = $reponse->fetch())
				{
					?>
					<option value="<?php echo $r['id']; ?>"><?php echo $r['titre']; ?></option>
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