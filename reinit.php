<?php

include "main.php";

$error = 0;

if(isset($_REQUEST['md5']))
{
	$md5 = $_REQUEST['md5'];
	$SQL = "SELECT COUNT(*) FROM pas_user WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if($req[0] == 0)
	{
		header("Location: $url_script");
		exit;
	}
}
else
{
	header("Location: $url_script");
	exit;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$error = 0;
		$md5 = $_REQUEST['md5'];
		$password1 = $_REQUEST['password1'];
		$password2 = $_REQUEST['password2'];
		
		if($password1 != $password2)
		{
			$error = 1;
		}
		
		if($password1 == '')
		{
			$error = 3;
		}
		if($password2 == '')
		{
			$error = 3;
		}
		
		if($error == 0)
		{
			/* Reinit */
			$SQL = "UPDATE pas_user SET password = '$password1' WHERE md5 = '$md5'";
			$pdo->query($SQL);
			$error = 2;
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Réinitialiser votre mot de passe</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo getConfig("template"); ?>/css/main.css">
	<link href="css/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
</head>
<style>
.prefooter
{
	bottom: 0px;
	position: fixed;
	width: 100%;
}
</style>
<body>
	<?php
	
	include "header.php";
	
	?>
	<div class="container topMargin">
		<H1>Réinitialiser votre mot de passe</H1>
		<p>
		Veuillez entrer votre nouveau mot de passe pour votre compte.
		</p>
		<?php
		if($error == 1)
		{
			?>
			<div class="errormsg">
			Le mot de passe n'est pas identique veuillez réessayez.
			</div>
			<?php
		}
		else if($error == 3)
		{
			?>
			<div class="errormsg">
			Votre mot de passe ne peux pas être vide.
			</div>
			<?php
		}
		else if($error == 2)
		{
			?>
			<div class="validmsg">
			Votre mot de passe à été réinitialiser avec succés vous pouvez vous connecter dés maintenant.
			</div>
			<?php
		}
		?>
		<form method="POST" action="reinit.php">
			<input type="password" name="password1" placeholder="Entrer votre nouveau mot de passe" class="inputbox">
			<input type="password" name="password2" placeholder="Confirmer votre nouveau mot de passe" class="inputbox">
			<div style="margin-top:10px;margin-bottom:10px;">
			<input type="hidden" name="md5" value="<?php echo $md5; ?>">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Réinitialiser votre mot de passe" class="btnConfirm">
			</div>
		</form>
	</div>
	<?php
	
	include "footer.php";
	
	?>
</body>
</html>
