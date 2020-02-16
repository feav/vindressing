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

if(isset($_REQUEST['language']))
{
	$language = $_REQUEST['language'];
}
else
{
	$language = 'fr';
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$langue_principal = $_REQUEST['langue_principal'];
		updateConfig("langue_principal",$langue_principal);
	}
	
	/* Modification hauteur de carte sur diverse résolution */
	if($action == 2)
	{
		$pixel_top_1024 = $_REQUEST['pixel_top_1024'];
		updateConfig("pixel_top_1024",$pixel_top_1024);
		
		$pixel_top_960 = $_REQUEST['pixel_top_960'];
		updateConfig("pixel_top_960",$pixel_top_960);
		
		$pixel_top_640 = $_REQUEST['pixel_top_640'];
		updateConfig("pixel_top_640",$pixel_top_640);
		
		$pixel_top_320 = $_REQUEST['pixel_top_320'];
		updateConfig("pixel_top_320",$pixel_top_320);
		
		header("Location: carte.php");
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.infomap
	{
		position: absolute;
		margin-top: -30px;
		width: 300px;
		background-color: rgba(0,0,0,0.8);
		color: #ffffff;
		font-size: 12px;
		height: 30px;
		box-sizing: border-box;
		padding: 6px;
	}
	
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
	</style>
	<div class="container">
		<H1>Carte</H1>
		<div class="info">
		Depuis cette interface vous pourrez changer la carte de votre site internet, profitez de notre Store pour obtenir de nouvelles cartes pour votre site internet.<br>
		<i>Attention chaque changement de cartes corrompera les données des annonces sur votre site, pensez à réaliser une sauvegarde avant toutes opérations</i>
		</div>
		<div class="tabs-cover">
			<a href="javascript:void(0);" onclick="showTabs(1)">
				<div class="tabs-element">
					Gestion de vos carte
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(2)">
				<div class="tabs-element">
					Configuration avancée de la carte
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(3)">
				<div class="tabs-element">
					Boutique
				</div>
			</a>
		</div>
		<div class="tabs" id="tabs-1">
		<H2>Vos cartes</H2>
		<div style="overflow:auto;margin-bottom:20px;">
		<?php
		$dir = "../map/";
		$x = 0;
		//  si le dossier pointe existe
		if(is_dir($dir)) 
		{
			// si il contient quelque chose
			if ($dh = opendir($dir)) 
			{
			   // boucler tant que quelque chose est trouve
			   while (($file = readdir($dh)) !== false) 
			   {
				   if($file == '.' || $file == '..')
				   {
					   
				   }
				   else
				   {
					   $f = explode(".",$file);
					   if($f[1] == 'txt')
					   {
						   // Fichier texte
						   ?>
						   <a href="javascript:void(0);" onclick="selectMap('<?php echo $f[0].".php"; ?>','<?php echo $x; ?>');">
						   <?php
						   if(getConfig("map_active") == $f[0].".svg")
						   {
							?>
							<a href="javascript:void(0);" onclick="selectMap('<?php echo $f[0].".php"; ?>','<?php echo $x; ?>');">
								<div id="map-<?php echo $x; ?>" style="float:left;border:3px solid #ff0000;margin-right:10px;">
									<img src="../map/<?php echo $f[0]; ?>.jpg">
									<div class="infomap">
									<?php echo $f[0]; ?> - <b>[Active]</b>
									</div>
								</div>
							</a>
							<?php
						   }
						   else
						   {
							?>
							<a href="javascript:void(0);" onclick="selectMap('<?php echo $f[0].".php"; ?>','<?php echo $x; ?>');">
								<div id="map-<?php echo $x; ?>" style="float:left;margin-right:10px;">
									<img src="../map/<?php echo $f[0]; ?>.jpg">
									<div class="infomap">
									<?php echo $f[0]; ?>
									</div>
								</div>
							</a>
							<?php
						   }
					   }
				   }
				   
				   $x++;
			   }
			   // on ferme la connection
			   closedir($dh);
		   }
		}
		?>
		</div>
		<a href="javascript:void(0);" onclick="changeMap();" class="btn blue">Modifier</a>
		<script>
		var selecturl;
		var selectid;
		function selectMap(url,id)
		{
			$('#map-'+id).css('border','3px solid rgb(255, 144, 0)');
			selecturl = url;
			selectid = id;
		}
		
		function changeMap()
		{
			if(confirm("Le changement de carte videra la région actuelle par les région de la nouvelle carte, les annonces actuelle risque d'être désordonnée, souhaitez vous continuer ?"))
			{
				document.location.href="<?php echo $url_script; ?>/map/"+selecturl;
			}
		}
		</script>
		</div>
		<div class="tabs" id="tabs-2" style="display:none;">
			<div class="info">
			Configuration avancée de la carte, vous pourrez régler les aspects technique de la carte sous différente résolution
			</div>
			<form method="POST">
				<label>Hauteur en pixel (Résolution 1024px ordinateur) :</label>
				<input type="text" name="pixel_top_1024" value="<?php echo getConfig("pixel_top_1024"); ?>" placeholder="Par defaut -100" class="inputbox">
				<label>Hauteur en pixel (Résolution 960px tablette) :</label>
				<input type="text" name="pixel_top_960" value="<?php echo getConfig("pixel_top_960"); ?>" placeholder="Par defaut -40" class="inputbox">
				<label>Hauteur en pixel (Résolution 640px tablette et mobile) :</label>
				<input type="text" name="pixel_top_640" value="<?php echo getConfig("pixel_top_640"); ?>" placeholder="Par defaut -40" class="inputbox">
				<label>Hauteur en pixel (Résolution 320px mobile) :</label>
				<input type="text" name="pixel_top_320" value="<?php echo getConfig("pixel_top_320"); ?>" placeholder="Par defaut 0" class="inputbox">
				<input type="hidden" name="action" value="2">
				<input type="submit" value="Modifier" class="btn blue">
			</form>
		</div>
		<div class="tabs" id="tabs-3" style="display:none;">
		<H2>Carte disponible dans la boutique</H2>
		<div class="info">
		Des cartes supplémentaire sont disponible dans la boutique, le script est fourni avec la Carte de France, vous souhaitez integrer une carte sur le site en 1 clic choissisez parmis
		nos carte en vente dans notre boutique au prix de 9.00 €.
		</div>
		<?php
		
		$data = file_get_contents("http://www.shua-creation.com/store/pas_script_map.php");
		echo $data;
		
		?>
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