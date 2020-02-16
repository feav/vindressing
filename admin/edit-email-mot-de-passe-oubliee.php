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
		$sujet_mail_oubliee = $_REQUEST['sujet_mail_oubliee'];
		updateConfig("sujet_mail_oubliee",$sujet_mail_oubliee);
		
		$email_mail_oubliee_html = $_REQUEST['email_mail_oubliee_html'];
		updateConfig("email_mail_oubliee_html",$email_mail_oubliee_html);
		
		$email_mail_oubliee_text = $_REQUEST['email_mail_oubliee_text'];
		updateConfig("email_mail_oubliee_text",$email_mail_oubliee_text);
								
		header("Location: edit-email-mot-de-passe-oubliee.php");
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
		<H1><i class="fas fa-envelope"></i> Edition du contenue de l'email de mot de passe oubliée</H1>
		<form method="POST" enctype="multipart/form-data">
			<div class="info">
			Vous pouvez ici rédiger l'email envoyé après l'inscription à la plateforme à vos utilisateurs, quelques connaissances en HTML sont est nécessaires mais ne sont pas obligatoires, certains champs peuvent
			être utilisés comme les champs de remplissage.<br>
			[logo] = Affiche le logo du site internet en HTML.<br>
			[email] = Adresse email de l'utilisateur .<br>
			[pseudo] = Pseudo de l'utilisateur.<br>
			[saut_ligne] = Sauter une ligne en HTML.<br>
			</div>
			<label>Sujet du mail oubliée :</label>
			<input type="text" name="sujet_mail_oubliee" value="<?php echo getConfig("sujet_mail_oubliee"); ?>" placeholder="Entrer le sujet du mail que recevront les utilisateurs quand il demanderont une réinitialisation de leur mot de passe" class="inputbox">
			<lable>Email mot de passe oubliée HTML :</label>
			<textarea name="email_mail_oubliee_html" class="textareabox" placeholder="Entrer le message que les utilisateur recevrons quand il ont oublier leur mot de passe HTML"><?php echo getConfig("email_mail_oubliee_html"); ?></textarea>
			<label>Email mot de passe oubliée TEXTE :</label>
			<textarea name="email_mail_oubliee_text" class="textareabox" placeholder="Entrer le message que les utilisateur recevrons quand il ont oublier leur mot de passe TEXTE"><?php echo getConfig("email_mail_oubliee_text"); ?></textarea>
			<input type="hidden" name="action" value="1">
			<button type="submit" class="btn blue"><i class="fas fa-edit"></i> Modifier</button>
		</form>
	</div>
</body>
</html>