<?php

include "../config.php";
include "../engine/class.monetaire.php";
include "../engine/class.map.php";
include "version.php";

$class_monetaire = new Monetaire();
$class_map = new Map();

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
}

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
	// creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);

    // copying relevant section from background to the cut resource
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    
	// copying relevant section from watermark to the cut resource
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    
	// insert cut resource to destination image
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
} 

function addFiligrame($filename,$logo)
{
	// On detecte ici l'extension
	$extension = explode(".",$filename);
	$extension = $extension[count($extension)-1];
	$extension = strtolower($extension);
	
	if($extension == 'jpg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'jpeg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'png')
	{
		$img = imagecreatefrompng($filename);
	}
	else
	{
		return "0";
	}
	
	$extensionlogo = explode(".",$logo);
	$extensionlogo = $extensionlogo[count($extensionlogo)-1];
	$extensionlogo = strtolower($extensionlogo);
	
	if($extensionlogo == 'jpg')
	{
		$imglogo = imagecreatefromjpeg($logo);
	}
	else if($extensionlogo == 'jpeg')
	{
		$imglogo = imagecreatefromjpeg($logo);
	}
	else if($extensionlogo == 'png')
	{
		$imglogo = imagecreatefrompng($logo);
	}
	else
	{
		return "0";
	}
	
	$widthlogo = imagesx($imglogo);
	$heightlogo = imagesy($imglogo);
	imagecopymerge_alpha($img, $imglogo, 10, 10, 0, 0, $widthlogo, $heightlogo, 100);
	
	if($extension == 'jpg')
	{
		imagejpeg($img,$filename);
	}
	else if($extension == 'jpeg')
	{
		imagejpeg($img,$filename);
	}
	else if($extension == 'png')
	{
		imagepng($img,$filename);
	}
}

function generateThumb($filename)
{
	$widthThumb = 192;
	$heightThumb = 149;
	
	// On detecte ici l'extension
	$extension = explode(".",$filename);
	$extension = $extension[count($extension)-1];
	$filenamecut = str_replace(".".$extension,"",$filename);
	$extension = strtolower($extension);
	
	if($extension == 'jpg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'jpeg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'png')
	{
		$img = imagecreatefrompng($filename);
	}
	else
	{
		return "0";
	}
		
	$width = imagesx($img);
	$height = imagesy($img);

	if($width > $height)
	{
		$ratio = $widthThumb / $width;
	}
	else
	{
		$ratio = $heightThumb / $height;
	}

	$thumb = imagecreatetruecolor($widthThumb,$heightThumb);
	$color = imagecolorallocate($thumb,180,180,180);
	imagefill($thumb,0,0,$color);
	$ratioWidth = $width * $ratio;
	$ratioHeight = $height * $ratio;
	$ratioPosHeight = 0;
	$ratioPosWidth = 0;

	if($ratioHeight < $heightThumb)
	{
		$ratioPosHeight = ($heightThumb/2)-($ratioHeight/2);
	}

	if($ratioWidth < $widthThumb)
	{
		$ratioPosWidth = ($widthThumb/2)-($ratioWidth/2);
	}

	imagecopyresampled($thumb,$img,$ratioPosWidth,$ratioPosHeight,0,0,$ratioWidth,$ratioHeight,$width,$height);
	imagejpeg($thumb,$filenamecut."-thumb.".$extension);
	
	return "1";
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$categorie = $_REQUEST['categorie'];
		$type_annonce = $_REQUEST['type_annonce'];
		$titre = $_REQUEST['titre'];
		$texte = $_REQUEST['texte'];
		$prix = $_REQUEST['prix'];
		$telephone = $_REQUEST['telephone'];
		$region = $_REQUEST['region'];
		$codepostal = $_REQUEST['codepostal'];
		$ville = $_REQUEST['ville'];
		$valider = $_REQUEST['valider'];
		$user = $_REQUEST['user'];
		
		$urgente = $_REQUEST['urgente'];
		$quinzejour = $_REQUEST['15jour'];
		$trentejour = $_REQUEST['30jour'];
		
		if($urgente == '')
		{
			$urgente = 1;
		}
		
		if($quinzejour == '')
		{
			$quinzejour = 'no';
		}
		
		if($trentejour == '')
		{
			$trentejour = 'no';
		}
		
		$titre = str_replace("'","''",$titre);
		$texte = str_replace("'","''",$texte);
		$ville = str_replace("'","''",$ville);
		
		$texte = nl2br($texte);
		
		$titre = strip_tags($titre);
		$texte = strip_tags($texte);
		
		$md5 = md5(microtime());
		
		$slug = slugify($titre);
		
		$option = $_REQUEST['option'];
		$option = implode(",",$option);
		
		$SQL = "INSERT INTO pas_annonce (iduser,slug,idregion,md5,titre,texte,offre_demande,idcategorie,prix,codepostal,valider,date_soumission,nbr_vue,telephone,ville,urgente,quinzejour,unmois,status,pro,option_annonce) VALUES ($user,'$slug',$region,'$md5','$titre','$texte','$type_annonce',$categorie,'$prix','$codepostal','$valider',NOW(),0,'$telephone','$ville',$urgente,'$quinzejour','$trentejour','FREE','','$option')";
		$pdo->query($SQL);
		
		$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/images/annonce/";
		if($_FILES['image1']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image1']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image1']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image1']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			$name_file1 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file1) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file1");
			}
			
			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file1))
			{
				unlink($chemin.$name_file1);
				exit("Le fichier n'est pas un fichier image");
			}

			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5','$name_file1')";
			$pdo->query($SQL);
			
			$urlfiligrane = getConfig("filigrane");
			generateThumb($chemin.$name_file1);
			addFiligrame($chemin.$name_file1,"images/".$urlfiligrane);
		}
		
		if($_FILES['image2']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image2']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image2']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image2']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			$name_file2 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file2) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file2");
			}	

			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file2))
			{
				unlink($chemin.$name_file2);
				exit("Le fichier n'est pas un fichier image");
			}
			
			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5','$name_file2')";
			$pdo->query($SQL);
			
			$urlfiligrane = getConfig("filigrane");
			generateThumb($chemin.$name_file2);
			addFiligrame($chemin.$name_file2,"images/".$urlfiligrane);
		}
		
		if($_FILES['image3']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image3']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image3']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image3']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			$name_file3 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file3) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file");
			}			

			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file3))
			{
				unlink($chemin.$name_file3);
				exit("Le fichier n'est pas un fichier image");
			}

			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5','$name_file3')";
			$pdo->query($SQL);
			
			$urlfiligrane = getConfig("filigrane");
			generateThumb($chemin.$name_file3);
			addFiligrame($chemin.$name_file3,"images/".$urlfiligrane);
		}
		
		if($_FILES['image4']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image4']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image4']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image4']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			$name_file4 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file4) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file4");
			}		
			
			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file4))
			{
				unlink($chemin.$name_file4);
				exit("Le fichier n'est pas un fichier image");
			}

			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5','$name_file4')";
			$pdo->query($SQL);
			
			$urlfiligrane = getConfig("filigrane");
			generateThumb($chemin.$name_file4);
			addFiligrame($chemin.$name_file4,"images/".$urlfiligrane);
		}
		
		header("Location: annonce.php");
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
	<style>
	.price
	{
		width:20%;
	}
	</style>
	<div class="container">
		<H1>Ajouter une annonces</H1>
		<div class="info">
		Vous pouvez ajouter une annonces manuellement facilement sur votre site internet.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
		<form method="POST" enctype="multipart/form-data">
			<label><b>Categorie :</b></label>
			<select name="categorie" class="inputbox">
			<?php
			
			echo $class_map->getAllCategorieOption();
			
			?>
			</select>
			<label><b>Type d'annonce :</b></label><br><br>
			<input type="radio" name="type_annonce" value="offre" checked> Offres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type_annonce" value="demande"> Demandes<br>
			<br>
			<label><b>Titre de l'annonce :</b></label>
			<input type="text" id="titre" name="titre" class="inputbox">
			<label><b>Texte de l'annonce :</b></label>
			<textarea id="texte" name="texte" class="textareabox"></textarea>
			<label><b>Prix :</b></label><br>
			<?php
			
			$currencysigle = $class_monetaire->getSigle();
			
			?>
			<input type="text" id="prix" name="prix" class="inputbox price"> <b><?php echo $currencysigle; ?></b><br>
			<label><b>Numéro de téléphone :</b></label>
			<div id="errorphone"></div>
			<input type="text" name="telephone" id="telephone" class="inputbox">
			<b>Photos :</b> <br><br>
			<input type="file" name="image1" class="inputbox">
			<input type="file" name="image2" class="inputbox">
			<input type="file" name="image3" class="inputbox">
			<input type="file" name="image4" class="inputbox">
			<input type="hidden" name="action" value="1">
			<label><b>Région</b></label>
			<select name="region" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_region";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<option value="<?php echo $req['id']; ?>"><?php echo $req['titre']; ?></option>
					<?php
				}
				
				?>
			</select>
			<label><b>Code postal</b></label>
			<input type="text" name="codepostal" class="inputbox">
			<label><b>Ville :</b></label>
			<input type="text" name="ville" class="inputbox">
			<label><b>Annonce valider :</b></label>
			<select name="valider" class="inputbox">
				<option value="yes">Valider</option>
				<option value="no">No Valider</option>
				<option value="expired">Expirée</option>
			</select>
			<label><b>Type d'option :</b></label><br><br>
			<?php
			
			$SQL = "SELECT * FROM pas_grille_tarif";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<input type="checkbox" name="option" value="<?php echo $req['id']; ?>"> <?php echo $req['nom']; ?><br>
				<?php
			}
			
			?>
			<br>
			<label><b>Utitilisateur :</b></label>
			<select name="user" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_user";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<option value="<?php echo $req['id']; ?>"><?php echo $req['username']; ?></option>
					<?php
				}
				
				?>
			</select>
			<input type="hidden" name="action" value="1">
			<div style="margin-top:10px;margin-bottom:10px;">
			<input type="submit" value="Valider" class="btn blue">		
		</form>
	</div>
</body>
</html>