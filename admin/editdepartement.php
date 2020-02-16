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
		$region = AntiInjectionSQL($_REQUEST['region']);
		
		$slug = slugify($nom);
		
		$SQL = "UPDATE pas_departement SET departement_nom = '$nom' WHERE departement_id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_departement SET departement_slug = '$slug' WHERE departement_id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_departement SET idregion = $region WHERE departement_id = $id";
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
	
	$SQL = "SELECT * FROM pas_departement WHERE departement_id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$idregion = $req['idregion'];
	
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
		<H1>Modification du département "<?php echo $req['departement_nom']; ?>"</H1>
		<form>
			<label>Nom du département :</label>
			<input type="text" name="nom" value="<?php echo $req['departement_nom']; ?>" class="inputbox">
			<label>Région associer :</label>
			<select name="region" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_region";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					if($idregion == $req['id'])
					{
					?>
					<option value="<?php echo $req['id']; ?>" selected><?php echo str_replace("'","&apos;",$req['titre']); ?></option>
					<?php	
					}
					else
					{
					?>
					<option value="<?php echo $req['id']; ?>"><?php echo str_replace("'","&apos;",$req['titre']); ?></option>
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