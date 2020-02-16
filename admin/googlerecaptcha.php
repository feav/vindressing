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
		$recaptcha_active = AntiInjectionSQL($_REQUEST['recaptcha_active']);
		updateConfig("recaptcha_active",$recaptcha_active);
		
		$site_key_recaptcha_google = AntiInjectionSQL($_REQUEST['site_key_recaptcha_google']);
		updateConfig("site_key_recaptcha_google",$site_key_recaptcha_google);
		
		$secret_key_recaptcha_google = AntiInjectionSQL($_REQUEST['secret_key_recaptcha_google']);
		updateConfig("secret_key_recaptcha_google",$secret_key_recaptcha_google);
		
		header("Location: googlerecaptcha.php");
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
		<H1><img src="images/google-recaptcha-big-icon.png"> Google reCatpcha</H1>
		<div class="info">
		Depuis cette page vous pourrez integrer le captcha de Google à votre site internet, le captcha est placer directement à l'inscription sur le site internet.
		</div>
		<form>
			<?php
			if(getConfig("recaptcha_active") == 'yes')
			{
				?>
				<input type="checkbox" name="recaptcha_active" value="yes" checked> Activer le reCaptcha sur votre site internet<br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="recaptcha_active" value="yes"> Activer le reCaptcha sur votre site internet<br>
				<?php
			}
			?>
			<br>
			<label><b>Site key recaptcha google :</b></label>
			<input type="text" name="site_key_recaptcha_google" value="<?php echo getConfig("site_key_recaptcha_google"); ?>" placeholder="Entrer la clé site key de votre recaptcha google" class="inputbox">
			<label><b>Secret key recaptcha google :</b></label>
			<input type="text" name="secret_key_recaptcha_google" value="<?php echo getConfig("secret_key_recaptcha_google"); ?>" placeholder="Entrer la clé secret key de votre recaptcha google" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>