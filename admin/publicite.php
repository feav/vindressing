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

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$publicite_top = $_REQUEST['publicite_top'];
		$publicite_left_annonce = $_REQUEST['publicite_left_annonce'];
		
		if(!$demo)
		{			
			updatePublicite("publicite_top",$publicite_top);
			updatePublicite("publicite_left_annonce",$publicite_left_annonce);
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
		}
		
		header("Location: publicite.php");
	}
	
	if($action == 2)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		$SQL = "DELETE FROM pas_publicite WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: publicite.php");
		exit;
	}
	
	/* Publicité d'habillage */
	if($action == 3)
	{
		$habillage_actif = $_REQUEST['habillage_actif'];
		updateConfig("habillage_actif",$habillage_actif);
		
		if($_FILES['habillage']['tmp_name'] != '')
		{
			$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/images/";
			$tmp_file = $_FILES['habillage']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['habillage']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['habillage']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			
			if($extension == 'png')
			{
			}
			else if($extension == 'jpg')
			{
				
			}
			else
			{
				exit("L'habillage doit être au format PNG ou JPEG");
			}
			
			$name_file = md5(microtime()).$extension;
			
			if( !move_uploaded_file($tmp_file, $chemin.$name_file))
			{
				exit("Impossible de copier le fichier dans $chemin$name_file");
			}
			
			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file))
			{
				unlink($chemin.$name_file);
				exit("Le fichier n'est pas un fichier image");
			}
			
			updateConfig("habillage_image",$name_file);
		}
		
		header("Location: publicite.php");
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
	<script src="<?php echo $url_script; ?>/admin/js/sweetalert.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Gestion des publicités</H1>
		<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video" style="display:none;">
				<iframe width="400" height="222" src="https://www.youtube.com/embed/HU_dlWoiB78" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
		Gérer les publicités de votre site internet depuis cette espace. Gérer les block publicitaire par identifiant et placer les dans votre template pour les utilisers.
		</div>
		<H2>Gestion des blocks publicitaire</H2>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addpublicite.php" class="btn blue">Ajouter un nouveau bloc publicitaire</a>
		</div>
		<table>
			<tr>
				<th>Identifiant</th>
				<th>Action</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_publicite";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<tr>
					<td>{<?php echo $req['identifiant']; ?>} <a href="javascript:void(0);" onclick="copy('{<?php echo $req['identifiant']; ?>}');"><img src="images/copy-icon.png" title="Copier l'identifiant"></a></td>
					<td>
						<a href="publicite.php?action=2&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
						<a href="editpublicite.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
		<script>
		function copy(c)
		{
			navigator.clipboard.writeText(c);
			Swal.fire(
		  'URL Copier dans le presse-papier',
		  '',
		  'success'
		);
		}
		</script>
		<H2>Publicités habillage</H2>
		<div class="info">
		La publicité d'habillage est une publicités spécifique qui épouse l'ensemble de votre site pour utiliser les bordures de site non utiliser. Elle sera visible principalement
		sur ordinateur.
		</div>
		<form method="POST" enctype="multipart/form-data">
			<?php
			if(getConfig("habillage_actif") == 'yes')
			{
				?>
				<input type="checkbox" name="habillage_actif" value="yes" checked> Activer la publicité d'habillage<br><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="habillage_actif" value="yes"> Activer la publicité d'habillage<br><br>
				<?php
			}
			?>
			<label><b>Image de l'habillage</b> <i>(Utiliser le <a href="https://www.shua-creation.com/exe/gabarit-publicite-background.psd">gabarit</a>)</i> :</label>
			<?php
			if(getConfig("habillage_image") != '')
			{
				?>
				<br><br><img src="<?php echo $url_script; ?>/images/<?php echo getConfig("habillage_image"); ?>" width=300><br>
				<?php
			}
			?>
			<input type="file" name="habillage" class="inputbox">
			<input type="hidden" name="action" value="3">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>