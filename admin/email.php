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
	/* Configuration de l'expedition des emails*/	
	if($action == 1)
	{
		$email_reception = AntiInjectionSQL($_REQUEST['email_reception']);
		updateConfig("email_reception",$email_reception);
		
		$nom_expediteur_mail = AntiInjectionSQL($_REQUEST['nom_expediteur_mail']);
		updateConfig("nom_expediteur_mail",$nom_expediteur_mail);
		
		$adresse_expediteur_mail = AntiInjectionSQL($_REQUEST['adresse_expediteur_mail']);
		updateConfig("adresse_expediteur_mail",$adresse_expediteur_mail);
		
		$reply_expediteur_mail = AntiInjectionSQL($_REQUEST['reply_expediteur_mail']);
		updateConfig("reply_expediteur_mail",$reply_expediteur_mail);
		
		header("Location: email.php");
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
	<style>
	.theme-preview-button
	{
		height: 10px;
		width: 15px;
		margin-top: 10px;
		margin-left: 20px;
	}
	
	.palette-block
	{
		float: left;
		margin-right: 5px;
		width: 40px;
		height: 28px;
	}
	
	.bselected
	{
		border:2px solid #ff0000;
	}
	</style>
	<div class="container">
		<H1><i class="fas fa-envelope"></i> Configuration Email</H1>
		<div class="info">
		Gérer depuis cette interface toutes les informations concernant les emails envoyés à vos utilisateurs.
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<div class="tabs-cover">
			<a href="javascript:void(0);" onclick="showTabs(1)">
				<div class="tabs-element">
					<i class="fas fa-envelope"></i> Gestion du contenue des emails
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(2)">
				<div class="tabs-element">
					<i class="fas fa-cogs"></i> Configuration de l'expedition des emails
				</div>
			</a>
		</div>
		<div class="tabs" id="tabs-1">
			<H2><i class="fas fa-envelope"></i> Gestion du contenue des emails</H2>
			<table>
				<tr>
					<th>Type d'email</th>
					<th>Action</th>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email d'inscription</td>
					<td><a href="edit-email-inscription.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email d'inscription professionel</td>
					<td><a href="edit-email-inscription-pro.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email de correspondance</td>
					<td><a href="edit-email-correspondance.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email de réponse lors d'un dépôt d'annonce</td>
					<td><a href="edit-email-reponse-depot-annonce.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email de validation d'une d'annonce</td>
					<td><a href="edit-email-validation-annonce.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email de mot de passe oubliée</td>
					<td><a href="edit-email-mot-de-passe-oubliee.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email de fin de validité d'annonce</td>
					<td><a href="edit-email-fin-validite-annonce.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email d'attente de réglement par Chèque</td>
					<td><a href="edit-email-attente-reglement-cheque.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
				<tr>
					<td><i class="fas fa-envelope"></i> Email d'attente de réglement de Virement bancaire</td>
					<td><a href="edit-email-attente-reglement-virement.php" class="btn blue"><i class="fas fa-edit"></i> Modifier</a></td>
				</tr>
			</table>
		</div>
		<div class="tabs" id="tabs-2" style="display:none;">
			<H2><i class="fas fa-cogs"></i> Configuration de l'expedition des emails</H2>
			<div class="info">
			Pour éviter que les email envoyer depuis votre site internet arrive en SPAM. Vous devez configurer une adresse email de l'expediteur qui existe et qui au nom de votre nom de domaine.
			<i>Exemple : votre site internet à pour domaine www.monsite.com votre email de l'expéditeur finira par @monsite.com</i>
			</div>
			<form method="POST">
				<label><b>Email de reception :</b></label>
				<input type="text" name="email_reception" value="<?php echo getConfig("email_reception"); ?>" placeholder="Entrer l'email ou vous souhaitez recevoir les notifications du script" class="inputbox">
				<label><b>Nom de l'expéditeur :</b></label>
				<input type="text" name="nom_expediteur_mail" value="<?php echo getConfig("nom_expediteur_mail"); ?>" placeholder="Entrer le nom de l'expediteur" class="inputbox">
				<label><b>Adresse email de l'expéditeur :</b></label>
				<input type="text" name="adresse_expediteur_mail" value="<?php echo getConfig("adresse_expediteur_mail"); ?>" placeholder="Entrer une adresse valide et correspondant à votre domaine pour éviter les spams et indésirable" class="inputbox">
				<label><b>Email de réponse :</b></label>
				<input type="text" name="reply_expediteur_mail" value="<?php echo getConfig("reply_expediteur_mail"); ?>" placeholder="Si vous souhaitez que vos utilisateur puissent répondre à vos email automatique (non obligatoire)" class="inputbox">
				<input type="hidden" name="action" value="1">
				<div style="margin-top:10px;">
					<button type="submit" class="btn blue"><i class="fas fa-edit"></i> Modifier</button>
				</div>
			</form>
		</div>
		<script>
		var oldtabs = 1;
		function showTabs(id)
		{
			$('#tabs-'+oldtabs).css('display','none');
			$('#tabs-'+id).css('display','block');
			oldtabs = id;
		}
		</script>
	</div>
</body>
</html>