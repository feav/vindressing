<?php

include "../config.php";
include "version.php";

$langue = getConfig("langue_administration");

if($langue == 'fr')
{
	include "lang/fr.php";
}
else if($langue == 'en')
{
	include "lang/en.php";
}
else if($langue == 'it')
{
	include "lang/it.php";
}
else if($langue == 'es')
{
	include "lang/es.php";
}
else if($langue == 'de')
{
	include "lang/de.php";
}
else
{
	include "lang/fr.php";
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
	<div class="connexion">
		<div class="logoconnexion">
			<div class="round">
				<img src="<?php echo $url_script; ?>/images/big-toutou-pas-script.png">
			</div>
		</div>
		<?php
		
		if(isset($_REQUEST['error']))
		{
			$error = $_REQUEST['error'];
			if($error == 1)
			{
				?>
				<div class="errormsg">
				<?php echo $title_connexion_error_password; ?>
				</div>
				<?php
			}
			if($error == 2)
			{
				?>
				<div class="errormsg">
				<?php echo $title_connexion_error_quota; ?>
				</div>
				<?php
			}
		}
		
		if($demo)
		{
		?>
		<div class="info">
		Pour vous connecter à l'administration de démonstration utiliser les identifiants suivant :<br><br>
		Username : admin<br>
		Mot de passe : admin<br>
		</div>
		<?php
		}
		?>
		<form method="POST" action="connexion.php">
		<H1><?php echo $title_connexion; ?></H1>
		<input type="text" name="username" placeholder="<?php echo $title_connexion_placeholder_username; ?>" class="inputbox">
		<input type="password" name="password" placeholder="<?php echo $title_connexion_placeholder_password; ?>" class="inputbox">
		<input type="submit" value="<?php echo $title_connexion_btn_connect; ?>" class="btn blue">
		</form>
	</div>
</body>
</html>