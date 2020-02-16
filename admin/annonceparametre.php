<?php
include "../config.php";
include "../engine/class.monetaire.php";
include "version.php";

$monetaire = new Monetaire();
$sigle = $monetaire->getSigle();

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
		$nbr_annonce_page = $_REQUEST['nbr_annonce_page'];
		updateConfig("nbr_annonce_page",$nbr_annonce_page);
		
		$moderation_activer = $_REQUEST['moderation_activer'];
		updateConfig("moderation_activer",$moderation_activer);
		
		$access_website = $_REQUEST['access_website'];
		updateConfig("access_website",$access_website);
		
		$duree_validite_annonce = $_REQUEST['duree_validite_annonce'];
		updateConfig("duree_validite_annonce",$duree_validite_annonce);
		
		$nbr_max_photo = $_REQUEST['nbr_max_photo'];
		updateConfig("nbr_max_photo",$nbr_max_photo);
		
		$nbr_max_photo_payant = $_REQUEST['nbr_max_photo_payant'];
		updateConfig("nbr_max_photo_payant",$nbr_max_photo_payant);
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
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<form>
		<H1>Paramètre des annonces</H1>
		<div class="info">
		Régler le paramètrage des annonces dans cette section, nombre d'annonces, modération des annonces, nombre d'annonce afficher, durée de validité d'une annonces, etc...
		</div>
		<H3>Modération des annonces</H3>
		<?php
		
		$moderation_activer = getConfig("moderation_activer");
		
		if($moderation_activer == 'yes')
		{
			?>
			<input type="checkbox" name="moderation_activer" value="yes" checked> Activer la modération des annonces<br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="moderation_activer" value="yes"> Activer la modération des annonces<br><br>
			<?php
		}			
		?>
		<H3>Accésibilité des annonce du site</H3>
		<?php
		$access_website = getConfig("access_website");
		if($access_website == 'full')
		{
			?>
			<input type="radio" name="access_website" value="full" checked> Les annonces sont visibles même par les visiteurs<br>
			<input type="radio" name="access_website" value="limited"> Les annonces sont inacessibles uniquement pour les membres connectés<br><br>
			<?php
		}
		else if($access_website == 'limited')
		{
			?>
			<input type="radio" name="access_website" value="full"> Les annonces sont visibles même par les visiteurs<br>
			<input type="radio" name="access_website" value="limited" checked> Les annonces sont inacessibles uniquement pour les membres connectés<br><br>
			<?php
		}
		else
		{
			?>
			<input type="radio" name="access_website" value="full" checked> Les annonces sont visibles même par les visiteurs<br>
			<input type="radio" name="access_website" value="limited"> Les annonces sont inacessibles uniquement pour les membres connectés<br><br>
			<?php
		}
		?>
		<H3>Nombre de photo maximum par annonce gratuite</H3>
		<select name="nbr_max_photo" class="inputbox">
		<?php
		
		$nbrphoto = getConfig("nbr_max_photo");
		
		for($x=1;$x<8;$x++)
		{
			if($x == $nbrphoto)
			{
			?>
			<option value="<?php echo $x; ?>" selected><?php echo $x; ?> photo par annonces</option>
			<?php	
			}
			else
			{
			?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?> photo par annonces</option>
			<?php
			}
		}				
		?>	
		</select>
		<H3>Nombre de photo maximum dans le pack de photo payant</H3>
		<select name="nbr_max_photo_payant" class="inputbox">
		<?php
		
		$nbrphoto = getConfig("nbr_max_photo_payant");
		
		for($x=1;$x<8;$x++)
		{
			if($x == $nbrphoto)
			{
			?>
			<option value="<?php echo $x; ?>" selected><?php echo $x; ?> photo par annonces</option>
			<?php	
			}
			else
			{
			?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?> photo par annonces</option>
			<?php
			}
		}				
		?>	
		</select>
		<H3>Nombre d'annonces par page</H3>
		<select name="nbr_annonce_page" class="inputbox">
		<?php
		
		$nbrAnnonce = getConfig("nbr_annonce_page");
		
		for($x=6;$x<21;$x++)
		{
			if($x == $nbrAnnonce)
			{
			?>
			<option value="<?php echo $x; ?>" selected><?php echo $x; ?> Annonces</option>
			<?php	
			}
			else
			{
			?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?> Annonces</option>
			<?php
			}
		}				
		?>
		</select>
		<H3>Durée de validité d'une annonce</H3>
		<select name="duree_validite_annonce" class="inputbox">
			<?php
			
			$duree = getConfig("duree_validite_annonce");
			
			if($duree == 0)
			{
				?>
				<option value="0" selected>Illimité</option>
				<?php
			}
			else
			{
				?>
				<option value="0">Illimité</option>
				<?php
			}
			
			for($x=1;$x<366;$x++)
			{
				if($duree == $x)
				{
					?>
					<option value="<?php echo $x; ?>" selected><?php echo $x; ?> jours</option>
					<?php
				}
				else
				{
					?>
					<option value="<?php echo $x; ?>"><?php echo $x; ?> jours</option>
					<?php
				}
			}
			
			?>
		</select>
		<input type="hidden" name="action" value="1">
		<div style="margin-top:20px;margin-bottom:20px;">
		<input type="submit" value="Modifier" class="btn blue">
		</div>
		</form>
		<H3>Remonter des annonces automatiser</H3>
		<div class="info">La remonter des annonces automatiser doit être executer régulièrement depuis la commande CRONTAB sur votre serveur, si vous utiliser un hebergement mutualiser ou
		un serveur regler cette fonctionnalité pour qu'elle soit appeler 1 fois par jour au minimum. Si votre script est heberger sur nos serveur le service est inclus, si votre hebergeur ne propose
		pas cette option, vous pouvez réaliser cette opération manuellement tous les jours, ou opter pour notre service de CRONTAB à 1€ / mois.</div>
		<input type="text" name="" value="<?php echo $url_script; ?>/cron.php" class="inputbox"><br><br>
		<a href="<?php echo $url_script; ?>/cron.php" class="btn blue" target="newpage">Executer la remonter des annonces automatiser manuellement</a>
		<H3>Abonnement remonter automatiser par CRONTAB</H3>
		<div class="info">
		Votre hebergeur ne propose pas l'option de CRONTAB, optez pour le service de CRONTAB de remonter des annonces automatiser toute les 30 minutes pour 1€/ mois
		</div>
		<?php
		
		$website = $_SERVER['SERVER_NAME'];
		$data = file_get_contents("https://www.shua-creation.com/api/checkcrontab.php?website=".$url_script);
		
		if($data == 'non')
		{
			?>
			<b>Aucun service de remonter automatique trouver pour votre site internet</b><br><br>
			<a href="https://www.shua-creation.com/paidmodule.php?id=13" class="btn blue">Service de CRONTAB (1€/mois)</a>
			<?php
		}
		else
		{
			?>
			<b>Service de remonter automatiser par CRONTAB en place ! (La tache cron est appeler toutes les 30 minutes par nos serveurs)</b>
			<?php
		}
		
		?>
	</div>
</body>
</html>