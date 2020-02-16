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
		$color_header = $_REQUEST['color_header'];
		updateConfig("color_header",$color_header);
		
		$color_carte = $_REQUEST['color_carte'];
		updateConfig("color_carte",$color_carte);
		
		$color_button_confirm = $_REQUEST['color_button_confirm'];
		updateConfig("color_button_confirm",$color_button_confirm);
		
		$color_button_confirm_survol = $_REQUEST['color_button_confirm_survol'];
		updateConfig("color_button_confirm_survol",$color_button_confirm_survol);
		
		$color_button_connexion = $_REQUEST['color_button_connexion'];
		updateConfig("color_button_connexion",$color_button_connexion);
		
		$color_button_connexion_hover = $_REQUEST['color_button_connexion_hover'];
		updateConfig("color_button_connexion_hover",$color_button_connexion_hover);
		
		$color_button_paging = $_REQUEST['color_button_paging'];
		updateConfig("color_button_paging",$color_button_paging);
		
		$color_button_paging_select = $_REQUEST['color_button_paging_select'];
		updateConfig("color_button_paging_select",$color_button_paging_select);
		
		$color_footer = $_REQUEST['color_footer'];
		updateConfig("color_footer",$color_footer);
		
		$color_price_annonce = $_REQUEST['color_price_annonce'];
		updateConfig("color_price_annonce",$color_price_annonce);
		
		$color_number_annonce = $_REQUEST['color_number_annonce'];
		updateConfig("color_number_annonce",$color_number_annonce);
		
		$color_button_phone = $_REQUEST['color_button_phone'];
		updateConfig("color_button_phone",$color_button_phone);
		
		header("Location: apparence.php");
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
	<script src="js/jscolor.min.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.jscolor
	{
		width:100%;
		border:none;
		margin-top:10px;
		margin-bottom:10px;
		height:35px;
		box-sizing: border-box;
		padding-left: 10px;
		border: 1px solid #aaaaaa;
	}
	</style>
	<div class="container">
		<H1>Design & Apparence</H1>
		<div class="info">
		Vous retrouverez dans cette partie l'ensemble des paramètres modifiables pour le design de votre site internet.
		</div>
		<form method="POST" enctype="multipart/form-data">
			<label>Couleur du Header :</label>
			<input class="jscolor" name="color_header" value="<?php echo getConfig("color_header"); ?>">
			<label>Couleur de la carte :</label>
			<input class="jscolor" name="color_carte" value="<?php echo getConfig("color_carte"); ?>">
			<label>Couleur bouton de confirmation :</label>
			<input class="jscolor" name="color_button_confirm" value="<?php echo getConfig("color_button_confirm"); ?>">
			<label>Couleur bouton de confirmation survol :</label>
			<input class="jscolor" name="color_button_confirm_survol" value="<?php echo getConfig("color_button_confirm_survol"); ?>">
			<label>Couleur bouton de connexion :</label>
			<input class="jscolor" name="color_button_connexion" value="<?php echo getConfig("color_button_connexion"); ?>">
			<label>Couleur bouton de connexion survol :</label>
			<input class="jscolor" name="color_button_connexion_hover" value="<?php echo getConfig("color_button_connexion_hover"); ?>">
			<label>Couleur de bouton de pagination non selectionné :</label>
			<input class="jscolor" name="color_button_paging" value="<?php echo getConfig("color_button_paging"); ?>">
			<label>Couleur de bouton de pagination selectionné :</label>
			<input class="jscolor" name="color_button_paging_select" value="<?php echo getConfig("color_button_paging_select"); ?>">
			<label>Couleur de fond du footer :</label>
			<input class="jscolor" name="color_footer" value="<?php echo getConfig("color_footer"); ?>">
			<label>Couleur du prix des annonces :</label>
			<input class="jscolor" name="color_price_annonce" value="<?php echo getConfig("color_price_annonce"); ?>">
			<label>Couleur de l'indicateur du nombre de photos sur les annonces :</label>
			<input class="jscolor" name="color_number_annonce" value="<?php echo getConfig("color_number_annonce"); ?>">
			<label>Couleur de fond du bouton d'appel téléphonique :</label>
			<input class="jscolor" name="color_button_phone" value="<?php echo getConfig("color_button_phone"); ?>">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>