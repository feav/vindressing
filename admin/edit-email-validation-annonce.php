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
		$sujet_validation_annonce = $_REQUEST['sujet_validation_annonce'];
		updateConfig("sujet_validation_annonce",$sujet_validation_annonce);
		
		$email_validation_annonce_html = $_REQUEST['email_validation_annonce_html'];
		updateConfig("email_validation_annonce_html",$email_validation_annonce_html);
		
		$email_validation_annonce_texte = $_REQUEST['email_validation_annonce_texte'];
		updateConfig("email_validation_annonce_texte",$email_validation_annonce_texte);
								
		header("Location: edit-email-validation-annonce.php");
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<div style="margin-bottom:10px;">
			<a href="email.php" class="btn blue"><i class="fas fa-arrow-circle-left"></i> Retour</a>
		</div>
		<H1><i class="fas fa-envelope"></i> Edition du contenue de l'email de validation d'une annonce</H1>
		<form method="POST" enctype="multipart/form-data">
			<div class="info">
			Vous pouvez ici rédiger l'email envoyé après l'inscription à la plateforme à vos utilisateurs, quelques connaissances en HTML sont est nécessaires mais ne sont pas obligatoires, certains champs peuvent
			être utilisés comme les champs de remplissage.<br>
			[logo] = Affiche le logo du site internet en HTML.<br>
			[email] = Adresse email de l'utilisateur .<br>
			[pseudo] = Pseudo de l'utilisateur.<br>
			[saut_ligne] = Sauter une ligne en HTML.<br>
			</div>
			<label>Sujet de validation d'une annonce</label>
			<input type="text" name="sujet_validation_annonce" class="inputbox" value="<?php echo getConfig("sujet_validation_annonce"); ?>" placeholder="Entrer le sujet du mail que recevront les utilisateurs si leur annonce est valider sur le site internet">
			<label>Email de validation d'une annonce HTML :</label>
			<textarea type="text" name="email_validation_annonce_html" class="textareabox" placeholder="Entrer le message que les utilisateur recevrons les utilisateurs si leur annonce est valider sur le site internet HTML"><?php echo getConfig("email_validation_annonce_html"); ?></textarea>
			<label>Email de validation d'une annonce TEXTE :</label>
			<textarea type="text" name="email_validation_annonce_texte" class="textareabox" placeholder="Entrer le message que les utilisateur recevrons les utilisateurs si leur annonce est valider sur le site internet TEXTE"><?php echo getConfig("email_validation_annonce_texte"); ?></textarea>
			<input type="hidden" name="action" value="1">
			<button type="submit" class="btn blue"><i class="fas fa-edit"></i> Modifier</button>
		</form>
	</div>
</body>
</html>