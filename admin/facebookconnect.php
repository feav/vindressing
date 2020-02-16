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

function br2nl($string)
{
	$string = str_replace("<br />","",$string);
	return $string;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	/* Valider une campagne */
	if($action == 1)
	{
		$facebook_connect_app_id = $_REQUEST['facebook_connect_app_id'];
		updateConfig("facebook_connect_app_id",$facebook_connect_app_id);
		
		$facebook_connect_activate = $_REQUEST['facebook_connect_activate'];
		updateConfig("facebook_connect_activate",$facebook_connect_activate);
		
		header("Location: facebookconnect.php");
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1><img src="<?php echo $url_script; ?>/admin/images/facebook-big-icon.png"> Facebook Connect</H1>
		<div class="info">
		Depuis cette interface vous pourrez configurer Facebook connect qui permettra de connecter et d'inscrire vos utilisateur directement sur votre site internet.
		Il vous faudra pour celà un compte Facebook et vous connecter à l'interface <a href="https://developers.facebook.com/" target="new">Facebook Developer</a> pour y renseigner les champs ici présent.
		<br><br><b><i>Attention ! Votre site doit être en HTTPS pour utiliser cette option, sinon la connexion est impossible via Facebook</i></b></div>
		<form method="POST">
			<label>ID de l'APP :</label>
			<input type="text" name="facebook_connect_app_id" value="<?php echo getConfig("facebook_connect_app_id"); ?>" class="inputbox" placeholder="identifiant de l'application facebook à 15 chiffres">
			<?php
			if(getConfig("facebook_connect_activate") == 'yes')
			{
				?>
				<input type="checkbox" name="facebook_connect_activate" value="yes" checked> <b>Activer la connexion Facebook Connect</b><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="facebook_connect_activate" value="yes"> <b>Activer la connexion Facebook Connect</b><br>
				<?php
			}
			?>
			<input type="hidden" name="action" value="1">
			<div style="margin-top:10px;margin-bottom:20px;">
				<input type="submit" value="Modifier" class="btn blue">
			</div>
		</form>
	</div>
</body>
</html>