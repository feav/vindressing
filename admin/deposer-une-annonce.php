<?php

include "main.php";
include 'stripe/init.php';

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

function correctImageOrientation($filename) 
{
  if(function_exists('exif_read_data')) 
  {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) 
	{
      $orientation = $exif['Orientation'];
      if($orientation != 1)
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
		
        $deg = 0;
        switch ($orientation) 
		{
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if($deg) 
		{
			$img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename
		if($extension == 'jpg')
		{
			imagejpeg($img, $filename, 95);
		}
		else if($extension == 'jpeg')
		{
			imagejpeg($img, $filename, 95);
		}
		else if($extension == 'png')
		{
			imagepng($img,$filename);
		}
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
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
		/* On recupere l'identifiant de l'utilisateur */
		$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$iduser = $req['id'];
		$emailuser = $req['email'];
		$nom = $req['pseudo'];
		
		$categorie = $_REQUEST['categorie'];
		$type_annonce = $_REQUEST['type_annonce'];
		$titre = $_REQUEST['titre'];
		$texte = $_REQUEST['texte'];
		$prix = $_REQUEST['prix'];
		$codepostal = $_REQUEST['codepostal'];
		$telephone = $_REQUEST['telephone'];
		$telephone = str_replace(" ","",$telephone);
		$telephone = str_replace(".","",$telephone);
		
		$texte = nl2br($texte);
		
		$titre = strip_tags($titre);
		$texte = strip_tags($texte);
		$titre = str_replace("'","''",$titre);
		$texte = str_replace("'","''",$texte);
		$codepostal = explode("-",$codepostal);
		$codepostal = trim($codepostal[0]);
		
		$ville = $_REQUEST['ville'];
		$ville = str_replace("'","''",$ville);
		
		$md5annonce = md5(microtime());
		
		/* Recupere l'information si l'annonce est moderer ou non */
		$moderation_activer = getConfig("moderation_activer");
		if($moderation_activer == '')
		{
			$moderation_activer = 'yes';
		}
		else
		{
			$moderation_activer = 'no';
		}
		
		$idregion = $_REQUEST['idregion'];
		$slug = slugify($titre);
		
		$SQL = "INSERT INTO pas_annonce (iduser,slug,idregion,md5,titre,texte,offre_demande,idcategorie,prix,codepostal,valider,date_soumission,nbr_vue,telephone,ville,urgente,quinzejour,unmois,status) VALUES ($iduser,'$slug',$idregion,'$md5annonce','$titre','$texte','$type_annonce',$categorie,'$prix','$codepostal','$moderation_activer',NOW(),0,'$telephone','$ville',1,'no','no','FREE')";
		$pdo->query($SQL);
		
		// On envoie un email pour dire que l'annonce va être prise en compte sous 48h par exemple après modération		
		$sujet = getConfig("sujet_mail_depot_annonce");
		$messageHTML = getConfig("email_depot_annonce_html");
		$messageTEXTE = getConfig("email_depot_annonce_text");
		
		$sujet = str_replace("[titre]",$titre,$sujet);
		$sujet = str_replace("[nom]",$nom,$sujet);
		$sujet = str_replace("[email]",$email,$sujet);
		
		$messageHTML = str_replace("[titre]",$titre,$messageHTML);
		$messageHTML = str_replace("[email]",$email,$messageHTML);
		$messageHTML = str_replace("[nom]",$nom,$messageHTML);
		$messageHTML = str_replace("[message]",$msg,$messageHTML);
		$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
		$messageHTML = str_replace("[logo]",'<img src="'.$url_script.'/images/'.getConfig("logo").'">',$messageHTML);	
		$messageHTML = '<html><body>'.$messageHTML.'</body></html>';
		
		$messageTEXTE = str_replace("[titre]",$titre,$messageTEXTE);
		$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
		$messageTEXTE = str_replace("[nom]",$nom,$messageTEXTE);
		$messageTEXTE = str_replace("[message]",$msg,$messageTEXTE);
		$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
		$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
		
		$boundary = "-----=".md5(rand());
		$passage_ligne = "\r\n";
		
		$expediteurmail = getConfig("nom_expediteur_mail");
		$adresse_expediteur_mail = getConfig("adresse_expediteur_mail");
		$header = "From: \"$expediteurmail\"<$adresse_expediteur_mail>".$passage_ligne;
		$header.= "Reply-to: \"$nom\" <$emailuser>".$passage_ligne;
		$header.= "MIME-Version: 1.0".$passage_ligne;
		$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
		
		//=====Création du message.
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format texte.
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$messageTEXTE.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format HTML
		$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$messageHTML.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		
		mail($emailuser,$sujet,$message,$header);
		
		/* mail de notification d'une nouvelle annonces */
		$email_reception = getConfig("email_reception");
		$sujet = "Une nouvelle annonce à été déposer sur le site http://".$_SERVER['HTTP_HOST'];
		$message = "Bonjour,<br><br>Une nouvelle annonce à été déposer sur le site http://".$_SERVER['HTTP_HOST'].", rendez vous<br>dans votre administration pour la valider.<br><br>Ce message à été envoyez automatiquement depuis PAS Script";
		$headers = "From: \"$expediteurmail\"<$adresse_expediteur_mail>\n";
		$headers .= "Reply-To: $adresse_expediteur_mail\n";
		$headers .= "Content-Type: text/html; charset=\"UTF-8\"";
		mail($email_reception,$sujet,$message,$headers);
		
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
			
			/* Fix orientation mobile */
			correctImageOrientation($chemin . $name_file1);

			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5annonce','$name_file1')";
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

			/* Fix orientation mobile */
			correctImageOrientation($chemin . $name_file2);
			
			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5annonce','$name_file2')";
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

			/* Fix orientation mobile */
			correctImageOrientation($chemin . $name_file3);

			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5annonce','$name_file3')";
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

			/* Fix orientation mobile */
			correctImageOrientation($chemin . $name_file4);

			$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5annonce','$name_file4')";
			$pdo->query($SQL);
			
			$urlfiligrane = getConfig("filigrane");
			generateThumb($chemin.$name_file4);
			addFiligrame($chemin.$name_file4,"images/".$urlfiligrane);
		}
		
		/* On check si les annonces payante sont active */
		$urgente = getConfig("annonce_urgente_activer");
		if($urgente == 'yes')
		{
			/* On attend peux être un paiement vu que les options sont activer */
			$SQL = "UPDATE pas_annonce SET status = 'WAITING_PAID' WHERE md5 = '$md5annonce'";
			$pdo->query($SQL);
			
			header("Location: $url_script/deposer-une-annonce.php?step=2&md5=$md5annonce");
			exit;
		}
		
		/* Pas de paiement donc l'annonce passe en STATUS "TERMINER" */
		$SQL = "UPDATE pas_annonce SET status = 'FREE' WHERE md5 = '$md5annonce'";
		$pdo->query($SQL);

		header("Location: $url_script/deposer-une-annonce.php?valid=1");
		exit;
	}
}

$titleSEO = getTitleSEO('deposer_une_annonce');
$descriptionSEO = getDescriptionSEO('deposer_une_annonce');

$template = getConfig("template");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $titleSEO; ?></title>
	<meta name="Description" content="<?php echo $descriptionSEO; ?>">
    <meta name="Keywords" content="">
    <meta name="Author" content="">
    <meta name="Identifier-URL" content="">
    <meta name="Revisit-after" content="3 days">
    <meta name="Robots" content="all">
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo $template; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo $template; ?>/css/responsive.css">
	<link href="css/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
	<?php
	
	$class_templateloader->openBody();
	
	include "header.php";
	
	?>
	<div class="container topMargin" style="margin-bottom: 90px;">
		<?php
		if(isset($_REQUEST['valid']))
		{
			?>
			<div class="validmsg">
			Votre annonce à bien été prise en compte elle sera modérée par notre équipe et valider entre 24 et 48h.
			</div>
			<?php
		}
		
		if(isset($_REQUEST['step']))
		{
			$step = $_REQUEST['step'];
			if($step == 2)
			{
				$currencysigle = $class_monetaire->getSigle();
				$annoncepayante = getConfig("annonce_classique_payante");
				$md5 = $_REQUEST['md5'];
				?>
				<style>
				.prefooter
				{
					bottom: 0px;
					position: fixed;
					width: 100%;
				}
				</style>
				<H1>Ajouter des options à votre annonces</H1>
				<div class="info">
				Les options permet de vendre plus rapidement votre objet et d'augmenter la visibilité de votre annonces. Ces options ne sont pas obligatoire mais vivement conseiller pour augmenter vos ventes.
				</div>
				<form method="POST">
				<?php
				if(getConfig("annonce_urgente_activer") == 'yes')
				{
				?>
				<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">
					<div style="float:left;font-size:18px;">
						<input type="checkbox" id="urgente" onclick="addurgente();" name="urgente" value="yes"> Mettre sont annonce Urgente
					</div>
					<div style="float:right;font-size:18px;">
						Prix : <?php echo getConfig("prix_urgente"); ?> <?php echo $currencysigle; ?>
					</div>
				</div>
				<p>
				Mettre une annonce en Urgente permet de mettre en avant votre annonce d'être directement visible avec le bandeau
				"Urgente" et sa couleur et differente, de plus elle est toujours en tête de liste sur le site.
				</p>
				<?php
				}
				
				if(getConfig("annonce_remonter_semaine") == 'yes')
				{
				?>
				<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">
					<div style="float:left;font-size:18px;">
						<input type="checkbox" id="remonter15day" value="yes" onclick="add15day();" name="remonter_15jours" value="yes"> Annonces remonter toutes les semaines pendant 15 jours
					</div>
					<div style="float:right;font-size:18px;">
						Prix : <?php echo getConfig("prix_remonter_semaine"); ?> <?php echo $currencysigle; ?>
					</div>
				</div>
				<p>
				Mettre votre annonce en remonter toute les semaines permet à votre annonce d'être remonter en haut
				de la liste toutes les semaines, cette options dure 15 jours, votre annonce aura donc droit à deux remonter.
				</p>
				<?php
				}
				
				if(getConfig("annonce_remonter_mois") == 'yes')
				{
				?>
				<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">
					<div style="float:left;font-size:18px;">
						<input type="checkbox" id="remonter30day" value="yes" onclick="add30day();" name="remonter_30jours" value="yes"> Annonces remonter toutes les semaines pendant 1 mois
					</div>
					<div style="float:right;font-size:18px;">
						Prix : <?php echo getConfig("prix_remonter_mois"); ?> <?php echo $currencysigle; ?>
					</div>
				</div>
				<p>
				Mettre votre annonce en remonter toute les semaines permet à votre annonce d'être remonter en haut
				de la liste toutes les semaines, cette options dure 1 mois, votre annonce aura donc droit à quatre remonter.
				</p>
				<?php
				}
				
				if($annoncepayante == 'yes')
				{
					$total = getConfig("prix_annonce");
				}
				else
				{
					$total = 0;
				}
				
				?>
				<div style="overflow:auto;">
					<div style="float:right;font-size: 22px;" id="totalCmd">
						Total : <?php echo number_format($total,2); ?> <?php echo $currencysigle; ?>
					</div>
				</div>
				<input type="hidden" name="md5" value="<?php echo $md5; ?>">
				<input type="hidden" name="step" value="3">
				<input type="submit" value="Suivant" class="btnConfirm">
				</form>
				<script>
				total = 0;
				function add15day()
				{
					if($('#remonter15day').get(0).checked)
					{
						total = total + <?php echo getConfig("prix_remonter_semaine"); ?>;
					}
					else
					{
						total = total - <?php echo getConfig("prix_remonter_semaine"); ?>;
					}
					updateTotal();
				}
				
				function add30day()
				{
					if($('#remonter30day').get(0).checked)
					{
						total = total + <?php echo getConfig("prix_remonter_mois"); ?>;
					}
					else
					{
						total = total - <?php echo getConfig("prix_remonter_mois"); ?>;
					}
					updateTotal();
				}
				
				function addurgente()
				{
					if($('#urgente').get(0).checked)
					{
						total = total + <?php echo getConfig("prix_urgente"); ?>;
					}
					else
					{
						total = total - <?php echo getConfig("prix_urgente"); ?>;
					}
					updateTotal();
				}
				
				function updateTotal()
				{
					$('#totalCmd').html('Total : '+total.toFixed(2)+' <?php echo $currencysigle; ?>');
					$('#ss').data('amount',total * 1000);
				}
				</script>
				<?php
			}
			else if($step == 3)
			{
				$md5 = $_REQUEST['md5'];
				$urgente = $_REQUEST['urgente'];
				$remonter_15jours = $_REQUEST['remonter_15jours'];
				$remonter_30jours = $_REQUEST['remonter_30jours'];
				
				$total = 0;
				
				if($urgente == 'yes')
				{
					$total = $total + getConfig("prix_urgente");
				}
				
				if($remonter_15jours == 'yes')
				{
					$total = $total + getConfig("prix_remonter_semaine");
				}
				
				if($remonter_30jours == 'yes')
				{	
					$total = $total + getConfig("prix_remonter_mois");
				}
				
				$annoncepayante = getConfig("annonce_classique_payante");
				if($annoncepayante == 'yes')
				{
					$total = $total + getConfig("prix_annonce");
				}
				
				if($total == 0)
				{
					
				/* L'utilisateur n'as pas choisi de paiement on passe le STATUS en TERMINER */
				$SQL = "UPDATE pas_annonce SET status = 'FREE' WHERE md5 = '$md5'";
				$pdo->query($SQL);
					
				?>
				<style>
				.prefooter
				{
					bottom: 0px;
					position: fixed;
					width: 100%;
				}
				</style>
				<H1>Votre annonce est finaliser</H1>
				<p>
				Notre équipe validera ou non votre annonce dans les plus bref délais, vous pourrez retrouver
				l'évolution de la validation de votre annonce dans le menu "Mes Annonces"
				</p>
				<?php
				}
				else
				{
				?>
				<style>
				.prefooter
				{
					bottom: 0px;
					position: fixed;
					width: 100%;
				}
				</style>
				<H1>Finalisation de votre annonce</H1>
				<p>
				Pour finaliser votre annonce vous pouvez dés maintenant régler votre annonce par Carte Bancaire.
				</p>
				<?php
				$paypal_activate = getConfig("paypal_activate");
				if($paypal_activate == 'yes')
				{
				?>
				<a href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=5&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>" class="btnConfirm" style="float: left;margin-right: 5px;">Payer avec Paypal</a>
				<?php
				}
				
				$paydunya_activate = getConfig("paydunya_activate");
				if($paydunya_activate == 'yes')
				{
				?>
				<a href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=7&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>" class="btnConfirm" style="float:left;margin-right:5px;">Payer avec Paydunya</a>
				<?php
				}
				
				$stripe_activate = getConfig("stripe_activate");
				if($stripe_activate == 'yes')
				{
				?>
					<style>
					.stripe-button-el 
					{
						background-image: none;
						box-shadow: none;
						padding: 0px;
					}
					
					.stripe-button-el span
					{
						display: block;
						min-height: 30px;
						background-color: #ff0000 !important;
						background: none;
						font-size: 13px;
						font-family: 'Open Sans', sans-serif;
						padding-left: 50px;
						padding-right: 50px;
						padding-top: 5px;
						padding-bottom: 5px;
					}
					</style>
					<form action="" id="formstripe" method="POST">
					  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						data-key="<?php echo getConfig("stripe_publishable_key"); ?>"
						data-amount="<?php echo ($total * 100); ?>"
						data-name="Paiement de petite annonces"
						data-image="<?php echo $url_script; ?>/images/<?php echo getConfig("logo"); ?>"
						data-locale="fr"
						data-label="Payer par Carte Bancaire via Stripe"
						data-currency="eur">
					  </script>
					  <input type="hidden" name="price" value="<?php echo ($total * 100); ?>">
					  <input type="hidden" name="urgente" value="<?php echo $urgente; ?>">
					  <input type="hidden" name="step" value="4">
					  <input type="hidden" name="remonter_15jours" value="<?php echo $remonter_15jours; ?>">
					  <input type="hidden" name="remonter_30jours" value="<?php echo $remonter_30jours; ?>">
					  <input type="hidden" name="md5" value="<?php echo $md5; ?>">
					</form>
				<?php
				}
				
				$cheque_activate = getConfig("cheque_activate");
				if($cheque_activate == 'yes')
				{
					?>
					<a href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=6&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>" class="btnConfirm" style="float: left;margin-right: 5px;">Payer par Chèque</a>
					<?php
				}
				
				}
			}
			else if($step == 4)
			{
				if(isset($_POST['stripeToken']))
				{
					$token = $_POST['stripeToken'];
					$price = $_POST['price'];
					/* Paiement STRIPE */
					\Stripe\Stripe::setApiKey(getConfig("stripe_secret_key"));
										
					try 
					{
					  $charge = \Stripe\Charge::create(array(
						"amount" => $price, // amount in cents, again
						"currency" => "eur",
						"source" => $token,
						"description" => "Paiement de petite annonces"
						));
						
					  if($charge->paid == true) 
					  {
							// Le paiement c'est effectuer normalement on ajoute les credit
							$urgente = $_POST['urgente'];
							$remonter_15jours = $_POST['remonter_15jours'];
							$remonter_30jours = $_POST['remonter_30jours'];
							$md5 = $_POST['md5'];
							
							/* On passe les differente options si payer en fonction */
							if($urgente == 'yes')
							{
								$SQL = "UPDATE pas_annonce SET urgente = 2 WHERE md5 = '$md5'";
								$pdo->query($SQL);
							}
							if($remonter_15jours == 'yes')
							{
								$SQL = "UPDATE pas_annonce SET quinzejour = 'yes' WHERE md5 = '$md5'";
								$pdo->query($SQL);
								
								/* On ajoute les remonter */
								$date = date('Y-m-d');
								$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
								$pdo->query($SQL);
								$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
								$pdo->query($SQL);
							}
							if($remonter_30jours == 'yes')
							{
								$SQL = "UPDATE pas_annonce SET unmois = 'yes' WHERE md5 = '$md5'";
								$pdo->query($SQL);
								
								/* On ajoute les remonter */
								$date = date('Y-m-d');
								$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
								$pdo->query($SQL);
								$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
								$pdo->query($SQL);
								$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 21 day)";
								$pdo->query($SQL);
								$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 28 day)";
								$pdo->query($SQL);
							}
							
							/* On enregistre le paiement */
							$p = $price / 100;
							$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('stripe','$p','".$_SESSION['email']."','".$charge->id."','$md5',NOW())";
							$pdo->query($SQL);
							
							/* On passe l'annonce en PAYER */
							$SQL = "UPDATE pas_annonce SET status = 'PAID_STRIPE' WHERE md5 = '$md5'";
							$pdo->query($SQL);
							
							?>
							<H1>Votre achat à bien été prise en compte</H1>
							<p>
							Votre annonce va être vérifier et valider par notre équipe sur le site internet,
							nous vous remercions de votre achat.
							</p>
							<a href="index.php" class="btnConfirm">Accueil</a>
							<?php
							exit;
					  }
					}
					catch(\Stripe\Error\Card $e) 
					{
						// The card has been declined
						$errorstripe = 1;
						
						/* On passe l'annonce en ERROR_PAID */
						$SQL = "UPDATE pas_annonce SET status = 'ERROR_PAID_STRIPE' WHERE md5 = '$md5'";
						$pdo->query($SQL);
						
						?>
						<H1>Une erreur c'est produite lors de votre achat</H1>
						<p>
						Une erreur c'est produite lors de votre achat, vérifier que votre carte est bien approvisionner
						ainsi que votre compte bancaire ou essayez avec une autre carte.
						</p>
						<a href="deposer-une-annonce.php?md5=<?php echo $md5; ?>&step=2">Recommencer</a>
						<?php
						exit;
					}
				}
			}
			else if($step == 5)
			{
				/* Reglement par Paypal */
				$total = $_REQUEST['total'];
				$md5 = $_REQUEST['md5'];
				
				/* On recuperer les infos d'option pour les trackers après le paiement */
				$urgente = $_REQUEST['urgente'];
				$r15 = $_REQUEST['r15'];
				$r30 = $_REQUEST['r30'];
				
				if($urgente != 'yes')
				{
					$urgente = 'no';
				}
				if($r15 != 'yes')
				{
					$r15 = 'no';
				}
				if($r30 != 'yes')
				{
					$r30 = 'no';
				}
				
				$paypal_email = getConfig("paypal_email");
				
				?>
				<center>
				<H1>Vous aller être rediriger sur Paypal pour le réglement de votre annonces</H1>
				<img src="<?php echo $url_script; ?>/images/loader.gif">
				</center>
				<form id="formpaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input name="cmd" type="hidden" value="_xclick" />
				<input name="business" type="hidden" value="<?php echo $paypal_email; ?>" />
				<input name="item_name" type="hidden" value="Paiement petite annonce n°<?php echo $md5; ?>" />
				<input name="amount" type="hidden" value="<?php echo $total; ?>" />
				<input name="shipping" type="hidden" value="0.00" />
				<input name="no_shipping" type="hidden" value="0" />
				<input name="currency_code" type="hidden" value="<?php echo $class_monetaire->currency; ?>" />
				<input name="tax" type="hidden" value="0.00" />
				<input name="lc" type="hidden" value="FR" />
				<input name="bn" type="hidden" value="PP-BuyNowBF" />
				<input name="notify_url" type="hidden" value="<?php echo $url_script; ?>/ipn.php" />
				<input name="custom" type="hidden" value="<?php echo "$md5-$total-$urgence-$r15-$r30"; ?>" />
				<input alt="" name="submit" src="" type="image" /><img src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
				</form>
				<script>
				document.forms["formpaypal"].submit(); 
				</script>
				<?php
			}
			else if($step == 6)
			{
				/* Reglement par chèque */
				$md5 = $_REQUEST['md5'];
				
				$SQL = "UPDATE pas_annonce SET status = 'WAIT_CHEQUE' WHERE md5 = '$md5'";
				$pdo->query($SQL);
				
				?>
				<style>
				.prefooter
				{
					bottom: 0px;
					position: fixed;
					width: 100%;
				}
				</style>
				<H1>Votre annonce est en attente de réglements</H1>
				<p>
				Pour activer et régler votre annonce vous avez choisi de régler par Chèque, veuillez envoyez un chèque à l'ordre de : <b>"<?php echo getConfig("cheque_ordre"); ?>"</b> à l'adresse suivante :<br><br>
				<b><?php echo getConfig("expedition_cheque"); ?></b><br><br>
				Notre équipe validera votre annonce à la reception de votre paiement dans les plus bref délais, vous pourrez retrouver
				l'évolution de la validation de votre annonce dans le menu "Mes Annonces"
				</p>
				<?php
			}
			else if($step == 7)
			{
				$md5 = $_REQUEST['md5'];
				$total = $_REQUEST['total'];
				$urgente = $_REQUEST['urgente'];
				$r15 = $_REQUEST['r15'];
				$r30 = $_REQUEST['r30'];
				
				?>
				<H1>Paiement avec Paydunya</H1>
				<p>
				Pour effectuer le paiement avec Paydunya, veuillez indiquer votre numéro de téléphone mobile et cliquez sur le bouton "Payer avec Paydunya", un code vous sera envoyez par SMS
				pour confirmer la transaction.
				</p>
				<form>
					<input type="text" name="phone_number" class="inputbox" placeholder="Votre numéro de téléphone mobile">
					<input type="hidden" name="step" value="8">
					<input type="hidden" name="md5" value="<?php echo $md5; ?>">
					<input type="hidden" name="total" value="<?php echo $total; ?>">
					<input type="hidden" name="urgente" value="<?php echo $urgente; ?>">
					<input type="hidden" name="r15" value="<?php echo $r15; ?>">
					<input type="hidden" name="r30" value="<?php echo $r30; ?>">
					<input type="submit" class="btnConfirm" value="Payer <?php echo $total; ?> F CFA avec Paydunya">
				</form>
				<?php
			}
			else if($step == 8)
			{
				$md5 = $_REQUEST['md5'];
				$total = $_REQUEST['total'];
				$urgente = $_REQUEST['urgente'];
				$r15 = $_REQUEST['r15'];
				$r30 = $_REQUEST['r30'];
				$phone_number = $_REQUEST['phone_number'];
				
				$description = "Reglement de l'annonce '".$md5."' sur ".$_SERVER['HTTP_HOST'];
				$store_name = $_SERVER['HTTP_HOST'];
				
				$token = $class_paydunya->createPaydunyaPaymentPSR(true,$phone_number,$total,$description,$store_name);
				?>
				<H1>Confirmation de votre Paiement Paydunya</H1>
				<p>
				Vous aller recevoir sur votre téléphone mobile un code par SMS, vous devez le rentrer et confirmer votre paiement
				pour qu'il soit valider.
				</p>
				<form>
					<input type="text" name="code" class="inputbox" placeholder="Votre code reçu par SMS">
					<input type="hidden" name="step" value="9">
					<input type="hidden" name="md5" value="<?php echo $md5; ?>">
					<input type="hidden" name="total" value="<?php echo $total; ?>">
					<input type="hidden" name="urgente" value="<?php echo $urgente; ?>">
					<input type="hidden" name="r15" value="<?php echo $r15; ?>">
					<input type="hidden" name="r30" value="<?php echo $r30; ?>">
					<input type="hidden" name="token" value="<?php echo $token; ?>">
					<input type="submit" class="btnConfirm" value="Finaliser mon achat avec Paydunya">
				</form>
				<?php
			}
			else if($step == 9)
			{
				$token = $_REQUEST['token'];
				$code = $_REQUEST['code'];
				$md5 = $_REQUEST['md5'];
				$total = $_REQUEST['total'];
				$urgente = $_REQUEST['urgente'];
				$r15 = $_REQUEST['r15'];
				$r30 = $_REQUEST['r30'];
				
				$reponse = $class_paydunya->finalPaydunyaPaymentPSR(true,$token,$code);
				if($reponse['reponse_code'] == '00')
				{
					/* On passe les differente options si payer en fonction */
					if($urgente == 'yes')
					{
						$SQL = "UPDATE pas_annonce SET urgente = 2 WHERE md5 = '$md5'";
						$pdo->query($SQL);
					}
					if($remonter_15jours == 'yes')
					{
						$SQL = "UPDATE pas_annonce SET quinzejour = 'yes' WHERE md5 = '$md5'";
						$pdo->query($SQL);
						
						/* On ajoute les remonter */
						$date = date('Y-m-d');
						$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
						$pdo->query($SQL);
						$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
						$pdo->query($SQL);
					}
					if($remonter_30jours == 'yes')
					{
						$SQL = "UPDATE pas_annonce SET unmois = 'yes' WHERE md5 = '$md5'";
						$pdo->query($SQL);
						
						/* On ajoute les remonter */
						$date = date('Y-m-d');
						$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
						$pdo->query($SQL);
						$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
						$pdo->query($SQL);
						$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 21 day)";
						$pdo->query($SQL);
						$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 28 day)";
						$pdo->query($SQL);
					}
					
					/* On enregistre le paiement */
					$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('paydunya','$total','".$reponse['email']."','','$md5',NOW())";
					$pdo->query($SQL);
					
					/* On passe l'annonce en PAYER */
					$SQL = "UPDATE pas_annonce SET status = 'PAID_PAYDUNYA' WHERE md5 = '$md5'";
					$pdo->query($SQL);
					
					?>
					<H1>Votre achat à bien été prise en compte</H1>
					<p>
					Votre annonce va être vérifier et valider par notre équipe sur le site internet,
					nous vous remercions de votre achat.
					</p>
					<a href="index.php" class="btnConfirm">Accueil</a>
					<?php
					exit;
				}
				else if($reponse['reponse_code'] == '1002')
				{
					?>
					<H1>Erreur lors de la transaction</H1>
					<p>
					Votre annonce n'as pas pu être regler correctement, vous n'avez pas indiquer un SMS correcte.
					Pour finaliser la transaction. Veuillez réessayez.<br><br>
					</p>
					<a href="deposer-une-annonce.php?md5=<?php echo $md5; ?>&step=7&total=<?php echo $total; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $r15; ?>&r30=<?php echo $r30; ?>" class="btnConfirm">Réessayez de payer avec Paydunya</a>
					<?php
					exit;
				}
				else
				{
					?>
					<H1>Erreur lors de la transaction</H1>
					<p>
					Votre annonce n'as pas pu être regler correctement, une erreur inconnue c'est produite lors du paiement de votre annonces.
					Pour finaliser la transaction. Veuillez réessayez.<br><br>
					</p>
					<a href="deposer-une-annonce.php?md5=<?php echo $md5; ?>&step=7&total=<?php echo $total; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $r15; ?>&r30=<?php echo $r30; ?>" class="btnConfirm">Réessayez de payer avec Paydunya</a>
					<?php
					exit;
				}
			}
		}
		else
		{
			
		?>
		<H1>Déposer une annonce</H1>
		<?php
			if($isConnected)
			{
				?>
				<form method="POST" onsubmit="return checkForm();" enctype="multipart/form-data">
					<label>Categorie :</label>
					<select name="categorie" class="selectbox">
					<?php
					$SQL = "SELECT * FROM pas_categorie";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						if($req['subcategorie'] == 0)
						{
						?>
						<option value="<?php echo $req['id']; ?>" disabled style="background-color:#aaaaaa;color:#ffffff;"><?php echo utf8_encode($req['titre']); ?></option>
						<?php
						}
						else
						{
						?>
						<option value="<?php echo $req['id']; ?>"><?php echo utf8_encode($req['titre']); ?></option>
						<?php
						}
					}
					?>
					</select>
					<label>Type d'annonce :</label><br><br>
					<input type="radio" name="type_annonce" value="offre" checked> Offres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type_annonce" value="demande"> Demandes<br><br>
					<label>Titre de l'annonce :</label>
					<input type="text" id="titre" name="titre" class="inputbox">
					<label>Texte de l'annonce :</label>
					<textarea id="texte" name="texte" class="textbox"></textarea>
					<label>Prix :</label>
					<div class="info">
					Le prix doit contenir un nombre entier pas de virgule, ni de points.
					</div>
					<?php
					
					$currencysigle = $class_monetaire->getSigle();
					
					?>
					<input type="text" id="prix" name="prix" class="inputbox price"> <b><?php echo $currencysigle; ?></b><br>
					<label>Numéro de téléphone :</label>
					<div id="errorphone"></div>
					<input type="text" name="telephone" id="telephone" class="inputbox">
					<b>Photos :</b> Une annonce avec photo est 7 fois plus consultée qu'une annonce sans photo<br><br>
					<input type="file" name="image1" class="inputbox">
					<input type="file" name="image2" class="inputbox">
					<input type="file" name="image3" class="inputbox">
					<input type="file" name="image4" class="inputbox">
					<input type="hidden" name="action" value="1">
					<label>Ville ou code postal</label>
					<input type="text" id="codepostal" onkeyup="changeCodePostal();" name="codepostal" class="inputbox">
					<div id="codepostalerror"></div>
					<div id="codepostalshow" class="codepostalshow">
						<center><img src="images/loader.gif"></center>
					</div>
					<input type="hidden" name="ville" id="ville">
					<input type="hidden" name="idregion" id="idregion">
					<div style="margin-top:10px;margin-bottom:10px;">
					<input type="submit" value="Valider" class="btnConfirm">
					</div>
				</form>
				<script>
				function isNumeric(n) 
				{
				  return !isNaN(parseFloat(n)) && isFinite(n);
				}
				
				function changeCodePostal()
				{
					var code = $('#codepostal').val();
					if(code.length > 1)
					{
						$('#codepostalshow').css('display','block');
						$('#codepostalshow').html('<center><img src="<?php echo $url_script; ?>/images/loader.gif"></center>');
						$('#codepostalshow').load('<?php echo $url_script; ?>/updateCP.php?code='+code);
					}
				}
				
				function updateText(cp,ville,idregion)
				{
					<?php
					$listing_type_recherche = getConfig("listing_type_recherche");
					if($listing_type_recherche == 'ville')
					{
						?>
						$('#codepostal').val(ville);
						<?php
					}
					else if($listing_type_recherche == 'codepostal')
					{
						?>
						$('#codepostal').val(cp);
						<?php
					}
					else if($listing_type_recherche == 'codepostalville')
					{
						?>
						$('#codepostal').val(cp+' - '+ville);
						<?php
					}
					?>
					$('#ville').val(ville);
					$('#idregion').val(idregion);
					$('#codepostalshow').css('display','none');
				}
				
				function checkForm()
				{
					var error = 0;
					var titre = $('#titre').val();
					var texte = $('#texte').val();
					var prix = $('#prix').val();
					var codepostal = $('#tags').val();
					var telephone = $('#telephone').val();
					var ville = $('#ville').val();
					
					$('#titre').css('border','1px solid #dddddd');
					$('#texte').css('border','1px solid #dddddd');
					$('#prix').css('border','1px solid #dddddd');
					$('#tags').css('border','1px solid #dddddd');
					$('#telephone').css('border','1px solid #dddddd');
					$('#errorphone').html('');
					
					if(titre == '')
					{
						error = 1;
						$('#titre').css('border','1px solid #ff0000');
					}
					
					if(texte == '')
					{
						error = 1;
						$('#texte').css('border','1px solid #ff0000');
					}
					
					if(prix == '')
					{
						error = 1;
						$('#prix').css('border','1px solid #ff0000');
					}
					
					if(!isNumeric(prix))
					{
						error = 1;
						$('#prix').css('border','1px solid #ff0000');
					}
					
					if(prix.indexOf(".") !=-1) 
					{
						error = 1;
						$('#prix').css('border','1px solid #ff0000');
					}
					
					if(prix.indexOf(",") !=-1) 
					{
						error = 1;
						$('#prix').css('border','1px solid #ff0000');
					}
					
					if(codepostal == '')
					{
						error = 1;
						$('#tags').css('border','1px solid #ff0000');
					}
					
					if(ville == '')
					{
						error = 1;
						$('#codepostal').css('border','1px solid #ff0000');
						$('#codepostalerror').html('<font color=red>Veuillez renseigner une ville existante dans notre base de données</font>');
					}
					
					<?php
					
					$nbr_chiffre_numero = getConfig("nbr_numero");
					
					?>
					if(telephone != '')
					{
						if(!isNumeric(telephone))
						{
							error = 1;
							$('#telephone').css('border','1px solid #ff0000');
							$('#errorphone').html('<font color=red><b>Le numéro de téléphone ne doit comporter que des chiffres</b></font>');
						}
						
						if(telephone.length < <?php echo $nbr_chiffre_numero; ?>)
						{
							error = 1;
							$('#telephone').css('border','1px solid #ff0000');
							$('#errorphone').html('<font color=red><b>Le numéro doit contenir 10 Chiffres</b></font>');
						}
						
						if(telephone.length > <?php echo $nbr_chiffre_numero; ?>)
						{
							error = 1;
							$('#telephone').css('border','1px solid #ff0000');
							$('#errorphone').html('<font color=red><b>Le numéro doit contenir 10 Chiffres</b></font>');
						}
					}
					
					if(error == 0)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				</script>
				<script>
				  $( function() {
					var availableTags = [
					  <?php
					  
					  $SQL = "SELECT * FROM villes_france_free";
					  $reponse = $pdo->query($SQL);
					  while($req = $reponse->fetch())
					  {
						  ?>
						  "<?php echo $req['ville_code_postal']." - ".$req['ville_nom'];?>",
						  <?php
					  }
					  
					  ?>
					];
					$( "#tags" ).autocomplete({
					  source: availableTags
					});
				  } );
				  </script>
				<?php
			}
		else
		{
			?>
			<div class="msgUserSubscribe">
				<div class="unknow-annonce">
				<H2>connecter vous ou inscrivez vous gratuitement</H2>
				<p>
				Connecter-vous à l'aide de votre adresse e-mail et de votre mot de passe. Si vous n'avez pas encore de compte, créez-en un afin de pouvoir poster votre annonce.<br><br>
				</p>
				<a href="subscribe.php" class="btnConfirm blue">Inscription</a>
				<a href="login.php" class="btnConfirm"> connexion</a>
				</div>
			</div>
			<?php
		}
		}
		?>
	</div>
	<?php
	
	include "footer.php";
	
	?>
</body>
</html>