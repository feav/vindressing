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
	
	/* Favicon */
	if($action == 1)
	{
		if($_FILES['favicon']['tmp_name'] != '')
		{
			$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/favicon/";
			$tmp_file = $_FILES['favicon']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['favicon']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['favicon']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			
			if($extension == 'png')
			{
			}
			else
			{
				exit("Le favicon doit être au format PNG uniquement");
			}
			
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
			
			$retour = $class_imagehelper->generateFavicon($chemin.$name_file);
			if($retour['error'] != '')
			{
				header("Location: parametretemplate.php?error=1&msg=".urlencode($retour['error']));
				exit;
			}
			else
			{
				header("Location: parametretemplate.php");
				exit;
			}
		}
	}
	
	/* Logo */
	if($action == 2)
	{
		$alt_logo = AntiInjectionSQL($_REQUEST['alt_logo']);
		updateConfig("alt_logo",$alt_logo);
		
		if($_FILES['logo']['tmp_name'] != '')
		{
			$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/images/";
			$tmp_file = $_FILES['logo']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['logo']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['logo']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			
			if($extension == 'jpg')
			{
			}
			else if($extension == 'png')
			{
			}
			else if($extension == 'jpeg')
			{
			}
			else if($extension == 'gif')
			{				
			}
			else
			{
				exit("Le logo doit être au format JPG,PNG ou GIF uniquement");
			}
			
			$name_file = md5(microtime()).".".$extension;
			
			if( !move_uploaded_file($tmp_file, $chemin . $name_file) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file");
			}
			
			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file))
			{
				unlink($chemin.$name_file);
				exit("Le fichier n'est pas un fichier image");
			}
			
			updateConfig("logo",$name_file);
			header("Location: parametretemplate.php");
			exit;
		}
	}
	
	/* Filigrane */
	if($action == 3)
	{
		if($_FILES['filigrane']['tmp_name'] != '')
		{
			$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/images/";
			$tmp_file = $_FILES['filigrane']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['filigrane']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['filigrane']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			
			if($extension == 'png')
			{
			}
			else
			{
				exit("Le filigrane doit être au format PNG uniquement");
			}
			
			if( !move_uploaded_file($tmp_file, $chemin . $name_file) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file");
			}
			
			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file))
			{
				unlink($chemin.$name_file);
				exit("Le fichier n'est pas un fichier image");
			}
			
			updateConfig("filigrane",$name_file);
			header("Location: parametretemplate.php");
			exit;
		}
	}
}

$langue = getConfig("langue_administration");
if($langue == 'fr')
{
	$titleh1 = "Paramètre du Template";
	$description = "Depuis cette interface vous pourrez configurer certain aspect du template.";
	$favicon_label = "Favicon :";
	$btn_edit = "Modifier";
	$logo_label = "Logo du site internet :";
	$filigrane_label = "Filigrane sur les photos du site internet :";
}
if($langue == 'en')
{
	$titleh1 = "Template setting";
	$description = "From this interface you can configure some aspect of the template.";
	$favicon_label = "Favicon :";
	$btn_edit = "Edit";
	$logo_label = "Logo of the website :";
	$filigrane_label = "Watermark on the photos of the website :";
}
if($langue == 'it')
{
	$titleh1 = "Impostazione del modello";
	$description = "Da questa interfaccia è possibile configurare alcuni aspetti del modello.";
	$favicon_label = "Favicon :";
	$btn_edit = "Cambiamento";
	$logo_label = "Logo del sito web :";
	$filigrane_label = "Filigrana sulle foto del sito Web :";
}
if($langue == 'bg')
{
	$titleh1 = "Настройка на шаблона";
	$description = "От този интерфейс можете да конфигурирате някой аспект на шаблона.";
	$favicon_label = "икона на сайта :";
	$btn_edit = "промяна";
	$logo_label = "Лого на сайта :";
	$filigrane_label = "Воден знак върху снимките на уебсайта :";
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
		<H1><?php echo $titleh1; ?></H1>
		<div class="info">
		<?php echo $description; ?>
		</div>
		<style>
		.filigrane-place
		{
			width: 300px;
			height: 300px;
			border: 1px dashed #000;
			background-image: url('images/background-transparent.png');
			text-align: center;
		}
		
		.filigrane-place-result
		{
			width: 300px;
			height: 300px;
			border: 1px dashed #000;
			background-image: url('images/background-transparent.png');
		}
		
		.filigrane-place img
		{
			padding-top:113px;
		}
		
		.filigrane-title
		{
			width: 300px;
			text-align: center;
			font-weight: bold;
		}
		
		.fillgrane-place-box
		{
			width: 300px;
			float: left;
			margin-right: 20px;
		}
		
		.filigrane-box
		{
			padding: 10px;
			background-color: #ccc;
			border-radius: 5px;
			overflow:auto;
		}
		
		.fillgrane-cmd
		{
			float:left;
			width:300px;
		}
		
		.pourcentage-title
		{
			text-align: center;
			margin-top: -16px;
			font-weight: bold;
			font-size: 14px;
		}
		</style>
		<H2><?php echo $favicon_label; ?></H2>
		<?php
		if(isset($_REQUEST['error']))
		{
			?>
			<div class="error-msg" style="width: 100%;padding: 10px;background-color: #f00;border-radius: 5px;text-align: center;color: #fff;font-weight: bold;">
			<?php echo $_REQUEST['msg']; ?>
			</div>
			<?php
		}
		
		if(file_exists("../favicon/favicon-16x16.png"))
		{
			?>
			<img src="../favicon/favicon-16x16.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/favicon-32x32.png"))
		{
			?>
			<img src="../favicon/favicon-32x32.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-57x57.png"))
		{
			?>
			<img src="../favicon/apple-icon-57x57.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-60x60.png"))
		{
			?>
			<img src="../favicon/apple-icon-60x60.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-72x72.png"))
		{
			?>
			<img src="../favicon/apple-icon-72x72.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-76x76.png"))
		{
			?>
			<img src="../favicon/apple-icon-76x76.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/favicon-96x96.png"))
		{
			?>
			<img src="../favicon/favicon-96x96.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-114x114.png"))
		{
			?>
			<img src="../favicon/apple-icon-114x114.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-120x120.png"))
		{
			?>
			<img src="../favicon/apple-icon-120x120.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-144x144.png"))
		{
			?>
			<img src="../favicon/apple-icon-144x144.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-152x152.png"))
		{
			?>
			<img src="../favicon/apple-icon-152x152.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/apple-icon-180x180.png"))
		{
			?>
			<img src="../favicon/apple-icon-180x180.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		if(file_exists("../favicon/android-icon-192x192.png"))
		{
			?>
			<img src="../favicon/android-icon-192x192.png?seed=<?php echo md5(microtime()); ?>">
			<?php
		}
		?>
		<form method="POST" enctype="multipart/form-data">
			<input type="file" name="favicon" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="<?php echo $btn_edit; ?>" class="btn blue">
		</form>
		<H2><?php echo $logo_label; ?></H2>
		<?php 
		$logo = getConfig("logo");
		?>
		<div class="filigrane-box">
			<div class="fillgrane-place-box">
				<div class="filigrane-place">
					<?php
					if($logo != '')
					{
					?>
					<img src="../images/<?php echo $logo; ?>" style="width: auto;height: 43px;padding-top: 129px;">
					<?php
					}
					?>
				</div>
				<div class="filigrane-title">Logo</div>
			</div>
			<div class="fillgrane-cmd">
				<form method="POST" enctype="multipart/form-data">
				<label><b>Modifier le logo du site internet :</b></label>
				<input type="file" name="logo" class="inputbox">
				<label><b>Balise titre du Logo :</b></label>
				<input type="text" name="alt_logo" class="inputbox" value="<?php echo getConfig("alt_logo"); ?>" placeholder="Titre ALT/TITLE de l'image">
				<input type="hidden" name="action" value="2">
				<input type="submit" value="<?php echo $btn_edit; ?>" class="btn blue">
				</form>
			</div>
		</div>
		<H2><?php echo $filigrane_label; ?></H2>
		<div class="filigrane-box">
			<div class="fillgrane-place-box">
				<div class="filigrane-place">
				<?php
				$logo = getConfig("filigrane");
				if($logo != '')
				{
					?>
					<img src="../images/<?php echo $logo; ?>" width=250>
					<?php
				}
				?>
				</div>
				<div class="filigrane-title">Filigrane</div>
			</div>
			<div class="fillgrane-place-box">
				<div class="filigrane-place-result" id="filigrane-result">
				<?php
				$logo = getConfig("filigrane");
				$pourcent_filigrane = getConfig("pourcent_filigrane");
				$position_filigrane = getConfig("position_filigrane");
				$activer_filigrane = getConfig("activer_filigrane");
				if($logo != '')
				{
					?>
					<img src="<?php echo $url_script; ?>/admin/preloadfiligrane.php?pourcent=<?php echo $pourcent_filigrane; ?>&position=<?php echo $position_filigrane; ?>" id="filigrane">
					<?php
				}
				?>
				</div>
				<div class="filigrane-title">Résultat</div>
			</div>
			<div class="fillgrane-cmd">
				<?php
				if($activer_filigrane == 'yes')
				{
					?>
					<input type="checkbox" name="filigrane_activer" id="filigrane_activer" checked> <b>Activer le filigrane des photos</b><br><br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="filigrane_activer" id="filigrane_activer"> <b>Activer le filigrane des photos</b><br><br>
					<?php
				}
				?>
				<label><b>Pourcentage :</b></label>
				<input type="range" min="1" max="100" step="1" value="<?php echo $pourcent_filigrane; ?>" name="pourcentage_filigrane" id="pourcent_filigrane" onchange="changePourcent();" class="inputbox">
				<div class="pourcentage-title" id="pourcentagetitle"><?php echo $pourcent_filigrane; ?> %</div>
				<label><b>Position :</b></label>
				<select name="position_filigrane" id="position_filigrane" class="inputbox" onchange="changePosition();">
				<?php
				if($position_filigrane == 'topleft')
				{
					?>
					<option value="topleft" selected>En haut à gauche</option>
					<?php
				}
				else
				{
					?>
					<option value="topleft">En haut à gauche</option>
					<?php
				}
				if($position_filigrane == 'topright')
				{
					?>
					<option value="topright" selected>En haut à droite</option>
					<?php
				}
				else
				{
					?>
					<option value="topright">En haut à droite</option>
					<?php
				}
				if($position_filigrane == 'center')
				{
					?>
					<option value="center" selected>Centrer</option>
					<?php
				}
				else
				{
					?>
					<option value="center">Centrer</option>
					<?php
				}
				if($position_filigrane == 'bottomleft')
				{
					?>
					<option value="bottomleft" selected>En bas à gauche</option>
					<?php
				}
				else
				{
					?>
					<option value="bottomleft">En bas à gauche</option>
					<?php
				}
				if($position_filigrane == 'bottomright')
				{
					?>
					<option value="bottomright" selected>En bas à droite</option>
					<?php
				}
				else
				{
					?>
					<option value="bottomright">En bas à droite</option>
					<?php
				}
				?>
				</select>
				<button onclick="modifFiligrane();" class="btn blue">Modifier</button>
			</div>
		</div>
		<script>
		function modifFiligrane()
		{
			var pourcent = $('#pourcent_filigrane').val();
			var position = $('#position_filigrane').val();
			var activer = $('#filigrane_activer').is(':checked');
			
			if(activer)
			{
				activer = 'yes';
			}
			else
			{
				activer = 'no';
			}
			
			$('#filigrane').attr('src','<?php echo $url_script; ?>/admin/preloadfiligrane.php?pourcent='+pourcent+'&position='+position+'&activer='+activer);
		}
		
		function changePourcent()
		{
			var pourcent = $('#pourcent_filigrane').val();
			$('#pourcentagetitle').html(pourcent+' %');
		}
		</script>
		<form method="POST" enctype="multipart/form-data">
			<br>
			<label><b>Modifier ou Ajouter un filigrane</b></label>
			<br><i style="font-size:10px;">Votre filigrane doit être au format PNG avec de la transparence pour pouvoir être collé sur les photos de vos utilisateurs
			lors du dépôt de nouvelles annonces.</i><br>
			<input type="file" name="filigrane" class="inputbox">
			<input type="hidden" name="action" value="3">
			<input type="submit" value="<?php echo $btn_edit; ?>" class="btn blue">
		</form>
	</div>
</body>
</html>