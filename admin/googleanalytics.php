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
		$googleanalytics = $_REQUEST['googleanalytics'];
		$googleanalytics = str_replace("<!-- Global site tag (gtag.js) - Google Analytics -->","",$googleanalytics);
		$googleanalytics = str_replace('<script async src="https://www.googletagmanager.com/gtag/js?id=',"",$googleanalytics);
		$googleanalytics = str_replace('"></script>','',$googleanalytics);
		$googleanalytics = str_replace('<script>','',$googleanalytics);
		$googleanalytics = str_replace('window.dataLayer = window.dataLayer || [];','',$googleanalytics);
		$googleanalytics = str_replace('function gtag(){dataLayer.push(arguments);}','',$googleanalytics);
		$googleanalytics = str_replace("gtag('js', new Date());","",$googleanalytics);
		$googleanalytics = str_replace("gtag('config', 'UA-78685265-1');","",$googleanalytics);
		$googleanalytics = str_replace('</script>','',$googleanalytics);
		
		$googleanalytics = trim($googleanalytics);
		$googleanalytics = AntiInjectionSQL($googleanalytics);
		
		if(substr($googleanalytics,0,2) == 'UA')
		{
			updateConfig("google_analytics",$googleanalytics);
			header("Location: googleanalytics.php?valid=1");
			exit;
		}
		else
		{
			header("Location: googleanalytics.php?error=1");
			exit;
		}
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
		<H1><img src="images/google-analytics-icon.png"> Google Analytics</H1>
		<div class="info">
		Depuis cette page vous pourrez integrer à votre site internet le code de suivi Google Analytics.
		</div>
		<?php
		if(isset($_REQUEST['error']))
		{
			?>
			<div class="alert alert-danger" role="alert">
			Le code de suivi de Google Analytics est incorrect !
			</div>
			<?php
		}
		if(isset($_REQUEST['valid']))
		{
			?>
			<div class="alert alert-success" role="alert">
			Le code de suivi Google Analytics à bien été prise en compte.
			</div>
			<?php
		}
		?>
		<style>
		.badge
		{
			background-color: #53bf0f;
			padding: 3px;
			font-size: 11px;
			border-radius: 5px;
			color: #fff;
			font-weight: bold;
		}
		</style>
		<form>
			<label>Code Google Analytics <span class="badge badge-success">ID : <?php echo getConfig("google_analytics"); ?></span> :</label>
			<textarea name="googleanalytics" class="textareabox" placeholder="Entrer le code de suivi founi par Google Analytics"></textarea>
			<input type="hidden" name="action" value="1">
			<div style="margin-top:20px;">
				<button type="submit" class="btn blue"><i class="fas fa-sync-alt"></i> Modifier</button>
			</div>
		</form>
	</div>
</body>
</html>