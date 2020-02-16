<?php

include "../config.php";
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
		$facebook_page_url = $_REQUEST['facebook_page_url'];
		updateConfig("facebook_page_url",$facebook_page_url);
		
		$twitter_page_url = $_REQUEST['twitter_page_url'];
		updateConfig("twitter_page_url",$twitter_page_url);
		
		$whatsapp_page_url = $_REQUEST['whatsapp_page_url'];
		updateConfig("whatsapp_page_url",$whatsapp_page_url);
		
		$pinterest_page_url = $_REQUEST['pinterest_page_url'];
		updateConfig("pinterest_page_url",$pinterest_page_url);
		
		$messenger_page_url = $_REQUEST['messenger_page_url'];
		updateConfig("messenger_page_url",$messenger_page_url);
		
		$linkedin_page_url = $_REQUEST['linkedin_page_url'];
		updateConfig("linkedin_page_url",$linkedin_page_url);
		
		$visibilite_social_top = $_REQUEST['visibilite_social_top'];
		updateConfig("visibilite_social_top",$visibilite_social_top);
		
		$visibilite_social_bottom = $_REQUEST['visibilite_social_bottom'];
		updateConfig("visibilite_social_bottom",$visibilite_social_bottom);
		
		$color_social_button = $_REQUEST['color_social_button'];
		updateConfig("color_social_button",$color_social_button);
		
		$color_social_button_survol = $_REQUEST['color_social_button_survol'];
		updateConfig("color_social_button_survol",$color_social_button_survol);
		
		$youtube_page_url = $_REQUEST['youtube_page_url'];
		updateConfig("youtube_page_url",$youtube_page_url);
		
		$visibilite_float_left = $_REQUEST['visibilite_float_left'];
		updateConfig("visibilite_float_left",$visibilite_float_left);
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
		<H1>Réseaux Sociale</H1>
		<div class="info">
		Vous retrouverez ici la posibilité de rattacher vos comptes de réseaux sociaux sur votre site internet, ils se retrouverons en bas , en haut ou sur le côté de votre site internet ou bien en même temps à plusieurs endroits.
		</div>
		<form method="POST" enctype="multipart/form-data">
			<H2>Visibilité :</H2>
			<?php
			if(getConfig("visibilite_social_top") == 'yes')
			{
				?>
				<input type="checkbox" name="visibilite_social_top" value="yes" checked> Partage en haut du site<br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="visibilite_social_top" value="yes"> Partage en haut du site<br>
				<?php
			}
			
			if(getConfig("visibilite_social_bottom") == 'yes')
			{
				?>
				<input type="checkbox" name="visibilite_social_bottom" value="yes" checked> Partage en bas du site<br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="visibilite_social_bottom" value="yes"> Partage en bas du site<br>
				<?php
			}
			
			if(getConfig("visibilite_float_left") == 'yes')
			{
				?>
				<input type="checkbox" name="visibilite_float_left" value="yes" checked> Partage flottant à gauche<br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="visibilite_float_left" value="yes"> Partage flottant à gauche<br>
				<?php
			}
			?>
			<H2>Compte Réseaux Sociaux :</H2>
			<label>Page Facebook :</label>
			<input type="text" name="facebook_page_url" value="<?php echo getConfig("facebook_page_url"); ?>" class="inputbox" placeholder="Entrer l'url de votre page Facebook">
			<label>Page Twitter :</label>
			<input type="text" name="twitter_page_url" value="<?php echo getConfig("twitter_page_url"); ?>" class="inputbox" placeholder="Entrer l'url de votre page Twitter">
			<label>Page Pinterest :</label>
			<input type="text" name="pinterest_page_url" value="<?php echo getConfig("pinterest_page_url"); ?>" class="inputbox" placeholder="Entrer l'url de votre page Pinterest">
			<label>Page Linkedin :</label>
			<input type="text" name="linkedin_page_url" value="<?php echo getConfig("linkedin_page_url"); ?>" class="inputbox" placeholder="Entrer l'url de votre page Linkedin">			
			<label>Compte Messenger Facebook :</label>
			<input type="text" name="messenger_page_url" value="<?php echo getConfig("messenger_page_url"); ?>" class="inputbox" placeholder="Entrer votre compte Messenger Facebook http://m.me/shuacreation">
			<label>Compte Whatsapp :</label>
			<input type="text" name="whatsapp_page_url" value="<?php echo getConfig("whatsapp_page_url"); ?>" class="inputbox" placeholder="Entrer le numero de votre whatsapp https://api.whatsapp.com/send?phone=15551234567">
			<label>Chaine Youtube :</label>
			<input type="text" name="youtube_page_url" value="<?php echo getConfig("youtube_page_url"); ?>" class="inputbox" placeholder="Entrer l'url de votre page Youtube">
			<H2>Apparance & Design</H2>
			<label>Couleur de fond des boutons de partage :</label>
			<input class="jscolor" name="color_social_button" value="<?php echo getConfig("color_social_button"); ?>">
			<label>Couleur de fond des boutons de partage au survol de la souris :</label>
			<input class="jscolor" name="color_social_button_survol" value="<?php echo getConfig("color_social_button_survol"); ?>">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>