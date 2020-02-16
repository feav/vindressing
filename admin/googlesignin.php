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
		$google_connect_id_client = $_REQUEST['google_connect_id_client'];
		$google_connect_secret_client = $_REQUEST['google_connect_secret_client'];
		$google_connect_name_application = $_REQUEST['google_connect_name_application'];
		$google_connect_activate = $_REQUEST['google_connect_activate'];
		
		updateConfig("google_connect_id_client",$google_connect_id_client);
		updateConfig("google_connect_secret_client",$google_connect_secret_client);
		updateConfig("google_connect_name_application",$google_connect_name_application);
		updateConfig("google_connect_activate",$google_connect_activate);
		
		header("Location: googlesignin.php");
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
		<H1><img src="images/google-sign-in-big-icon.png"> Google Sign-In</H1>
		<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video" style="display:none;">
				<iframe width="400" height="222" src="https://www.youtube.com/embed/Cxp66-zxNPI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		<script>
		function showHelpVideo()
		{
			var help = $('#help-video').css('display');
			if(help == 'none')
			{
				$('#help-video').css('display','block');
			}
			else
			{
				$('#help-video').css('display','none');
			}
		}
		</script>
		<div class="info">
		Depuis cette interface vous pourrez configurer Google connect qui permettra de connecter et d'inscrire vos utilisateur directement sur votre site internet.
		Il vous faudra pour celà un compte Google API et vous connecter à l'interface <a href="https://console.cloud.google.com/apis/?pli=1" target="new">Google API</a> pour y renseigner les champs ici présent.
		</div>
		<form method="POST">
			<label><b>ID Client :</b></label>
			<input type="text" name="google_connect_id_client" value="<?php echo getConfig("google_connect_id_client"); ?>" class="inputbox" placeholder="Exemple : 263469124784-7rr6uvtrjfl6c7ubff7nt0vbbpr33pq7.apps.googleusercontent.com">
			<label><b>Code secret client :</b></label>
			<input type="text" name="google_connect_secret_client" value="<?php echo getConfig("google_connect_secret_client"); ?>" class="inputbox" placeholder="Exemple : Vn9myxk1TZNh7DakTShwA8Kx">
			<label><b>Nom de l'application :</b></label>
			<input type="text" name="google_connect_name_application" value="<?php echo getConfig("google_connect_name_application"); ?>" class="inputbox" placeholder="Nom de l'application tel qu'elle apparaitra lors de la demande d'autorisation à une connexion avec Google">
			<?php
			if(getConfig("google_connect_activate") == 'yes')
			{
				?>
				<input type="checkbox" name="google_connect_activate" value="yes" checked> <b>Activer la connexion Google Sign-In</b><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="google_connect_activate" value="yes"> <b>Activer la connexion Google Sign-In</b><br>
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