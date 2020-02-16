<?php
include "../../../main.php";
include "../version.php";

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
		$emplacement = $_REQUEST['emplacement'];
		$code = $_REQUEST['code'];
		
		$emplacement = str_replace("'","''",$emplacement);
		$code = str_replace("'","''",$code);
		
		$type_banniere = $_REQUEST['type_banniere'];
		
		$SQL = "INSERT INTO emplacement_publicitaire (titre,code,type_banniere) VALUES ('$emplacement','$code','$type_banniere')";
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
	
	?>
	<div class="container">
		<H1>Ajout d'un encart publicitaire</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="regiepub.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label><b>Titre de l'emplacement</b></label>
			<input type="text" name="emplacement" value="" class="inputbox">
			<label><b>Code de l'emplacement</b></label>
			<input type="text" name="code" value="" class="inputbox">
			<label><b>Bannière publicitaire temporaire</b></label>
			<input type="file" name="banniere" class="inputbox">
			<label><b>Type de bannière</b></label>
			<select name="type_banniere" class="inputbox">
				<option value="728x90">Leaderboard (728 x 90 px)</option>
				<option value="300x250">Inline rectangle (300 x 250 px)</option>
				<option value="200x200">Small Square (200 x 200 px)</option>
				<option value="250x250">Square (250 x 250 px)</option>
				<option value="120x600">Skyscraper (120 x 600 px)</option>
				<option value="160x600">Wide Skyscraper (160 x 600 px)</option>
			</select>
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>