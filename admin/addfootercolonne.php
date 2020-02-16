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
		$SQL = "SELECT COUNT(*) FROM pas_footer";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$count = $req[0];
		
		if($count != 4)
		{		
			$titre_colonne = AntiInjectionSQL($_REQUEST['titre_colonne']);
			$SQL = "INSERT INTO pas_footer (colonne,titre,contenue) VALUES (0,'$titre_colonne','')";
			$pdo->query($SQL);
		}
		
		header("Location: footer.php");
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
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="footer.php" class="btn blue">Retour</a>
		</div>
		<H1>Ajout d'une nouvelle colonne au footer</H1>
		<form>
			<label><b>Titre de la colonne :</b></label>
			<input type="text" name="titre_colonne" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>