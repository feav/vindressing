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
		$google_webmaster_tool = $_REQUEST['google_webmaster_tool'];
		updateConfig("google_webmaster_tool",$google_webmaster_tool);
		
		$bing_verification = $_REQUEST['bing_verification'];
		updateConfig("bing_verification",$bing_verification);
		
		$pinterest_verification = $_REQUEST['pinterest_verification'];
		updateConfig("pinterest_verification",$pinterest_verification);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.tabs
	{
		padding: 10px;
		background-color: #ffffff;
		box-sizing: border-box;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}
	
	.tabs-element
	{
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		float: left;
		padding: 10px;
		background-color: #dddddd;
		margin-right: 1px;
	}
	
	.tabs-element:hover
	{
		background-color: #ffdddd;
	}
	
	.tabs-cover
	{
		overflow:auto;
	}
	
	.help
	{
		position: absolute;
		background-color: #333333;
		color: #ffffff;
		font-size: 12px;
		padding: 20px;
		border-radius: 5px;
		display: none;
		margin-top: 25px;
		margin-left: -20px;
	}
	
	a
	{
		color:#96d2ff;
	}
	
	.englobehelp
	{
		display:initial;
	}
	
	.help-video
	{
		float: right;
		margin-top: -65px;
		font-size: 25px;
		text-align: center;
	}
	
	.littletext
	{
		font-size: 12px;
		font-weight: bold;
	}
	
	.help-video-container
	{
		padding: 10px;
		background-color: #323131;
		width: 400px;
		border-radius: 5px;
		margin-bottom: 10px;
		float: right;
	}
	</style>
	<div class="container">
		<H1>Bing/Google Verification</H1>
		<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video" style="display:none;">
				<video width="400" height="222" controls="controls">
				  <source src="https://www.shua-creation.com/video/pas_script_seo_bing_google.mp4.filepart" type="video/mp4" />
				</video>
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
		Depuis cette page renseigner les balises de Google , Bing pour la vérification de l'apartenance de votre site internet. Cela permettra d'indexer plus facilement votre site internet sur les moteurs de recherche Google, Bing et Yahoo.
		</div>
		<form method="POST">
		<H3><div style="float: left;margin-right: 5px;margin-top: -4px;"><img src="images/google_icon.png" width=30></div> Google vérification <div onclick="showHelp(2);" class="englobehelp"><i class="fas fa-info-circle"></i><div class="help" id="help-2">Pour apparaitre dans le moteur de recherche Google et optenir un meilleur référencement, inscrivez vous à <a href="https://www.google.com/webmasters/tools/home?hl=fr" target="newpage">Google Search Console</a> , pour valider la propriété de votre site internet il vous sera proposer plusieurs options, choissisez la balise HTML et indiquer uniquement les caractères et chiffre contenu dans le balise content="" <i>(ex : 8OqwWMuaR1bozYKBQ35xbJyw4f81skkWgjNI81tpvEE)</i></div></div></H3>
		<input type="text" name="google_webmaster_tool" value="<?php echo getConfig("google_webmaster_tool"); ?>" placeholder="Inserez le code de propriété de votre Google Search Console utiliser la methode HTML et copier uniquement le code (Ex : 8OqwWMuaR1bozYKBQ35xbJyw4f81skkWgjNI81tpvEE)" class="inputbox">
		<H3><div style="float: left;margin-right: 5px;margin-top: -4px;"><img src="images/bing_icon.png" width=30></div> Bing vérification <div onclick="showHelp(1);" class="englobehelp"><i class="fas fa-info-circle"></i><div class="help" id="help-1">Pour apparaitre dans le moteur de recherche Bing et optenir un meilleur référencement, inscrivez vous à <a href="https://www.bing.com/toolbox/webmaster" target="newpage">Bing Webmaster tool</a> , pour valider la propriété de votre site internet il vous sera proposer plusieurs options, choissisez l'option 2 et indiquer uniquement les chiffres de la balise Meta <i>(ex : 4811102C3EE89F61C7E42EB653691DC4)</i></div></div></H3>
		<input type="text" name="bing_verification" value="<?php echo getConfig("bing_verification"); ?>" placeholder="Entrer le code de vérification de Bing Webmaster tool (Option 2)" class="inputbox">
		<H3><div style="float: left;margin-right: 5px;margin-top: -4px;"><img src="images/pinterest_icon.png" width=30></div> Pinterest vérification <div onclick="showHelp(3);" class="englobehelp"><i class="fas fa-info-circle"></i><div class="help" id="help-3">Pour revendiquer votre site internet sur Pinterest vous devrez indiquer une balise HTML sur votre site internet, selectionné l'option balise HTML puis indiquer uniquement le code entre content="{code}" <i>(ex : dac6b3de69a2727d1489cc662814c52e)</i></div></div></H3>
		<input type="text" name="pinterest_verification" value="<?php echo getConfig("pinterest_verification"); ?>" placeholder="Entrer le code de revendication de site internet de Pinterest uniquement le code voir aide" class="inputbox">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
	<script>
	function showHelp(id)
	{
		var h = $('#help-'+id).css('display');
		if(h == 'none')
		{
			$('#help-'+id).css('display','initial');
		}
		else
		{
			$('#help-'+id).css('display','none');
		}
	}
	</script>
</body>
</html>