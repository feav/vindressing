<?php
include "../../../main.php";
include "../../version.php";

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
		$id = $_REQUEST['id'];
		$duree = $_REQUEST['duree'];
		$prix = $_REQUEST['prix'];
		
		$SQL = "INSERT INTO emplacement_publicitaire_prix_duree (idemplacement,duree,prix) VALUES ($id,'$duree','$prix')";
		$pdo->query($SQL);
		
		header("Location: regiepub.php");
		exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="../../css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "../../header.php";
	include "../../sidebar.php";
	
	$id = $_REQUEST['id'];
	
	$SQL = "SELECT * FROM emplacement_publicitaire WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titre = $req['titre'];
	
	?>
	<div class="container">
		<H1>Ajout d'une politique de prix pour l'encart publicitaire "<?php echo $titre; ?>"</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="regiepub.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label>Durée</label>
			<select name="duree" class="inputbox">
				<?php
				
				for($x=1;$x<366;$x++)
				{
					?>
					<option value="<?php echo $x; ?>"><?php echo $x; ?> jours</option>
					<?php
				}
				
				?>
			</select>
			<label>Prix pour la durée</label>
			<input type="text" name="prix" value="" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>