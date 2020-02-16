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
		$sujet_mail_inscription = AntiInjectionSQL($_REQUEST['sujet_mail_inscription']);
		updateConfig("sujet_mail_inscription",$sujet_mail_inscription);
		
		$email_inscription_html = AntiInjectionSQL($_REQUEST['email_inscription_html']);
		updateConfig("email_inscription_html",$email_inscription_html);
		
		$email_inscription_text = AntiInjectionSQL($_REQUEST['email_inscription_text']);
		updateConfig("email_inscription_text",$email_inscription_text);
		
		header("Location: edit-email-inscription.php");
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
		<H1><i class="fas fa-envelope"></i> Edition du contenue de l'email d'inscription</H1>
		<form method="POST" enctype="multipart/form-data">
			<div class="info">
			Vous pouvez ici rédiger l'email envoyé après l'inscription à la plateforme à vos utilisateurs, quelques connaissances en HTML sont est nécessaires mais ne sont pas obligatoires, certains champs peuvent
			être utilisés comme les champs de remplissage.<br>
			[logo] = Affiche le logo du site internet en HTML.<br>
			[email] = Adresse email de l'utilisateur .<br>
			[pseudo] = Pseudo de l'utilisateur.<br>
			[lienconfirmation] = Lien de confirmation de l'inscription.<br>
			[saut_ligne] = Sauter une ligne en HTML.<br>
			</div>
			<label>Sujet du mail d'inscription :</label>
			<input type="text" name="sujet_mail_inscription" value="<?php echo getConfig("sujet_mail_inscription"); ?>" placeholder="Entrer le sujet du mail d'inscription" class="inputbox">
			<label>Email d'inscription au format HTML :</label>
			<textarea name="email_inscription_html" class="textareabox" placeholder="Entrer le message de votre email d'inscription au format HTML"><?php echo getConfig("email_inscription_html"); ?></textarea>
			<label>Email d'inscription au format TEXTE :</label>
			<textarea name="email_inscription_text" class="textareabox" placeholder="Entrer le message de votre email d'inscription au format TEXTE"><?php echo getConfig("email_inscription_text"); ?></textarea>
			<input type="hidden" name="action" value="1">
			<button type="submit" class="btn blue"><i class="fas fa-edit"></i> Modifier</button>
		</form>
	</div>
</body>
</html>