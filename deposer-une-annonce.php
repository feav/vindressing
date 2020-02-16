<?php

include "main.php";

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
		
		$categorie = AntiInjectionSQL($_REQUEST['categorie']);
		$type_annonce = AntiInjectionSQL($_REQUEST['type_annonce']);
		$titre = AntiInjectionSQL($_REQUEST['titre']);
		$texte = AntiInjectionSQL($_REQUEST['texte']);
		$prix = AntiInjectionSQL($_REQUEST['prix']);
		$codepostal = AntiInjectionSQL($_REQUEST['codepostal']);
		$cp = AntiInjectionSQL($_REQUEST['cp']);
		$telephone = AntiInjectionSQL($_REQUEST['telephone']);
		$telephone = str_replace(" ","",$telephone);
		$telephone = str_replace(".","",$telephone);
		
		$texte = nl2br($texte);

		$codepostal = explode("-",$codepostal);
		$codepostal = trim($codepostal[0]);
		
		$ville = AntiInjectionSQL($_REQUEST['ville']);
		$ville = str_replace("'","''",$ville);
		
		$md5annonce = AntiInjectionSQL($_REQUEST['md5']);
		
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
		
		$idregion = AntiInjectionSQL($_REQUEST['idregion']);
		$slug = slugify($titre);
		
		if($_SESSION['type_compte'] == 'professionel')
		{
			$pro = 'yes';
		}
		else
		{
			$pro = '';
		}
		
		$SQL = "INSERT INTO pas_annonce (iduser,slug,idregion,md5,titre,texte,offre_demande,idcategorie,prix,codepostal,valider,date_soumission,nbr_vue,telephone,ville,urgente,quinzejour,unmois,status,pro,option_annonce) VALUES ($iduser,'$slug',$idregion,'$md5annonce','$titre','$texte','$type_annonce',$categorie,'$prix','$cp','$moderation_activer',NOW(),0,'$telephone','$ville',1,'no','no','NOVISIBLE','$pro','')";
		$pdo->query($SQL);
		
		/* Si le filtre auto est activé */
		$module_filtre_auto = getConfig("module_filtre_auto");
		if($module_filtre_auto == 'true')
		{
			$marque = AntiInjectionSQL($_REQUEST['marque']);
			$modele = AntiInjectionSQL($_REQUEST['modele']);
			$annee_modele = AntiInjectionSQL($_REQUEST['annee_modele']);
			$kilometrage = AntiInjectionSQL($_REQUEST['kilometrage']);
			$carburant = AntiInjectionSQL($_REQUEST['carburant']);
			$boite_de_vitesse = AntiInjectionSQL($_REQUEST['boite_de_vitesse']);
			
			$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			if($rr['meta_title'] == 'module_filtre_auto')
			{
				include "module/class.module_filtre_auto.php";
				$mdo = new Module_filtre_auto();
				$mdo->saveFiltre($md5annonce,$marque,$modele,$annee_modele,$kilometrage,$carburant,$boite_de_vitesse);
			}
		}
		
		/* Si le filtre immo est activé */
		$module_filtre_immo = getConfig("module_filtre_immo");
		if($module_filtre_immo == 'true')
		{
			$type_de_bien = AntiInjectionSQL($_REQUEST['type_de_bien']);
			$surface = AntiInjectionSQL($_REQUEST['surface']);
			$pieces = AntiInjectionSQL($_REQUEST['pieces']);
			$classe_energie = AntiInjectionSQL($_REQUEST['classe_energie']);
			$ges = AntiInjectionSQL($_REQUEST['ges']);
			
			$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			if($rr['meta_title'] == 'module_filtre_immo')
			{
				include "module/class.module_filtre_immo.php";
				$mdo = new Module_filtre_immo();
				$mdo->saveFiltre($md5annonce,$type_de_bien,$surface,$pieces,$classe_energie,$ges);
			}
		}
		
		/* On check si les annonces payante sont active */
		$packphoto = AntiInjectionSQL($_REQUEST['packphoto']);
		if($packphoto == 'yes')
		{
			header("Location: $url_script/addoptionannonce.php?md5=$md5annonce&packphoto=yes");
			exit;
		}
		else
		{
			header("Location: $url_script/addoptionannonce.php?md5=$md5annonce");
			exit;
		}
		
		$packphoto = AntiInjectionSQL($_REQUEST['packphoto']);
		$urgente = getConfig("annonce_urgente_activer");
		if($urgente == 'yes')
		{			
			header("Location: $url_script/deposer-une-annonce.php?step=2&md5=$md5annonce&packphoto=$packphoto");
			exit;
		}
		$prix_remonter_semaine = getConfig("prix_remonter_semaine");
		if($prix_remonter_semaine == 'yes')
		{
			header("Location: $url_script/deposer-une-annonce.php?step=2&md5=$md5annonce&packphoto=$packphoto");
			exit;
		}
		$prix_remonter_mois = getConfig("prix_remonter_mois");
		if($prix_remonter_mois == 'yes')
		{
			header("Location: $url_script/deposer-une-annonce.php?step=2&md5=$md5annonce&packphoto=$packphoto");
			exit;
		}
		$prix_annonce = getConfig("prix_annonce");
		if($prix_annonce != '')
		{
			if($prix_annonce == 0)
			{
				/* Pas de paiement disponible donc l'annonce passe en STATUS "TERMINER" */
				$SQL = "UPDATE pas_annonce SET status = 'FREE' WHERE md5 = '$md5annonce'";
				$pdo->query($SQL);

				header("Location: $url_script/deposer-une-annonce.php?step=3&md5=$md5annonce");
				exit;
			}
			else
			{
				header("Location: $url_script/deposer-une-annonce.php?step=2&md5=$md5annonce&packphoto=$packphoto");
				exit;
			}
		}
		
		/* Pas de paiement disponible donc l'annonce passe en STATUS "TERMINER" */
		$SQL = "UPDATE pas_annonce SET status = 'FREE' WHERE md5 = '$md5annonce'";
		$pdo->query($SQL);

		header("Location: $url_script/deposer-une-annonce.php?step=3&md5=$md5annonce");
		exit;
	}
}

$titleSEO = getTitleSEO('deposer_une_annonce');
$descriptionSEO = getDescriptionSEO('deposer_une_annonce');

$template = getConfig("template");

$class_templateloader->showHead('deposer_une_annonce');
$class_templateloader->openBody();
	
	include "header.php";
	
	?>
	<div class="container topMargin" style="margin-bottom: 90px;">
		<?php
		if(isset($_REQUEST['step']))
		{
			$step = $_REQUEST['step'];
			if($step == 2)
			{
				$currencysigle = $class_monetaire->getSigle();
				$annoncepayante = getConfig("annonce_classique_payante");
				$md5 = $_REQUEST['md5'];
				$packphoto = $_REQUEST['packphoto'];
				
				$class_templateloader->loadTemplate("tpl/add_option_annonces.tpl");
				if($packphoto == 'yes')
				{
					$template_urgente = new TemplateLoader();
					$template_urgente->loadTemplate("tpl/row_annonces_option_photo.tpl");
					
					$template_urgente->assign("{nbr_max_photo_payant}",getConfig("nbr_max_photo_payant"));
					$template_urgente->assign("{prix_photo_supplementaire}",getConfig("prix_photo_supplementaire")." ".$currencysigle);
					
					$data = $template_urgente->getData();	
					$class_templateloader->assign("{photo_pack}",$data);
				}
				else
				{
					$class_templateloader->assign("{photo_pack}","");
				}
				
				/* Annonces urgente */
				if(getConfig("annonce_urgente_activer") == 'yes')
				{
					$template_urgente = new TemplateLoader();
					$template_urgente->loadTemplate("tpl/row_annonces_option_urgente.tpl");
					
					$template_urgente->assign("{prix_annonce_urgente}",getConfig("prix_urgente")." ".$currencysigle);
					
					$data = $template_urgente->getData();
					$class_templateloader->assign("{urgence_pack}",$data);
					
				}
				else
				{
					$class_templateloader->assign("{urgence_pack}","");
				}
				
				/* Annonces remonter 15 jours */
				if(getConfig("annonce_remonter_semaine") == 'yes')
				{
					$template_urgente = new TemplateLoader();
					$template_urgente->loadTemplate("tpl/row_annonces_option_semaine.tpl");
					
					$template_urgente->assign("{prix_remonter_semaine}",getConfig("prix_remonter_semaine")." ".$currencysigle);
					
					$data = $template_urgente->getData();
					$class_templateloader->assign("{remonter_semaine_pack}",$data);
					
				}
				else
				{
					$class_templateloader->assign("{remonter_semaine_pack}","");
				}
				
				/* Annonces remonter 1 mois */
				if(getConfig("annonce_remonter_mois") == 'yes')
				{
					$template_urgente = new TemplateLoader();
					$template_urgente->loadTemplate("tpl/row_annonces_option_mois.tpl");
					
					$template_urgente->assign("{prix_remonter_mois}",getConfig("prix_remonter_mois")." ".$currencysigle);
					
					$data = $template_urgente->getData();
					$class_templateloader->assign("{remonter_mois_pack}",$data);
					
				}
				else
				{
					$class_templateloader->assign("{remonter_mois_pack}","");
				}
				
				$class_templateloader->assign("{md5}",$md5);
				
				if($annoncepayante == 'yes')
				{
					$total = getConfig("prix_annonce");
					if($total == '')
					{
						$total = 0;
					}
				}
				else
				{
					$total = 0;
				}
				
				if($packphoto == 'yes')
				{
					$total = $total + getConfig("prix_photo_supplementaire");
				}
				
				$class_templateloader->assign("{total}",number_format($total,2)." ".$currencysigle);
				$class_templateloader->show();
				
				?>
				<script>
				<?php
				if($packphoto == 'yes')
				{
					?>
					total = <?php echo getConfig("prix_photo_supplementaire"); ?>;
					<?php
				}
				else
				{
					?>
					total = 0;
					<?php
				}
				?>
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
				$packphoto = $_REQUEST['packphoto'];
				
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
				
				if($packphoto == 'yes')
				{
					$total = $total + getConfig("prix_photo_supplementaire");
				}
				
				if($total == 0)
				{					
					/* L'utilisateur n'as pas choisi de paiement on passe le STATUS en TERMINER soit ANNONCE GRATUITE */
					$SQL = "UPDATE pas_annonce SET status = 'FREE' WHERE md5 = '$md5'";
					$pdo->query($SQL);
					
					/* Si il a été configurer en Modération ou non */
					if(getConfig("moderation_activer") == 'yes')
					{
						$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
						$pdo->query($SQL);
					}
					else
					{
						$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE md5 = '$md5'";
						$pdo->query($SQL);
					}
					
					$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
					$reponse = $pdo->query($SQL);
					$req = $reponse->fetch();
					
					$titre = $req['titre'];
					
					/* A Partir de la nous pouvons envoyez un email à l'utilisateur */
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
					$messageHTML = str_replace("[logo]",'',$messageHTML);	
					$messageHTML = '<html><body>'.$messageHTML.'</body></html>';

					/* On va chercher le template de mail */
					$mail_template = getConfig("mail_template");

					$data = file_get_contents("mail/".$mail_template.".tpl");
					$data = str_replace("{logo}",'<img src="'.$url_script.'/images/'.getConfig("logo").'" alt="Petites annonces gratuites en France">',$data);
					$data = str_replace("{message}",$messageHTML,$data);
					$data = str_replace("{website}",$url_script,$data);

					$messageHTML = $data;

					$messageTEXTE = str_replace("[titre]",$titre,$messageTEXTE);
					$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
					$messageTEXTE = str_replace("[nom]",$nom,$messageTEXTE);
					$messageTEXTE = str_replace("[message]",$msg,$messageTEXTE);
					$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
					$messageTEXTE = str_replace("[logo]","",$messageTEXTE);

					$boundary = "-----=".md5(rand());
					$passage_ligne = "\r\n";
					
					$emailuser = $_SESSION['email'];

					$expediteurmail = getConfig("nom_expediteur_mail");
					$adresse_expediteur_mail = getConfig("adresse_expediteur_mail");
					$header = "From: \"$expediteurmail\"<$adresse_expediteur_mail>".$passage_ligne;
					$header.= "Reply-to: \"$nom\" <$emailuser>".$passage_ligne;
					$header.= "MIME-Version: 1.0".$passage_ligne;
					$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

					//=====Création du message.
					$message = $passage_ligne."--".$boundary.$passage_ligne;
					//=====Ajout du message au format texte.
					$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
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
					
					/* On envoie un email à l'admin pour le prévenir également */
					$email_reception = getConfig("email_reception");
					$message = 'Bonjour,<br><br>Une nouvelle annonce à été déposer sur le site '.$url_script.', rendez vous<br>dans votre administration pour valider cette annonce après l\'avoir contrôler.<br><br>Ce message à été envoyez automatiquement depuis PAS Script';
					$class_email->sendMailTemplate($email_reception,'Une nouvelle annonce à été déposer sur le site '.$url_script,$message);
					
					$class_templateloader->loadTemplate("tpl/validation_message_depot_annonce.tpl");
					$class_templateloader->show();	
				}
				else
				{
					$SQL = "UPDATE pas_annonce SET status = 'WAITING_PAID' WHERE md5 = '$md5'";
					$pdo->query($SQL);
					?>
					<H1>Finalisation de votre annonce</H1>
					<p>
					Pour finaliser votre annonce vous pouvez dés maintenant régler votre annonce, par l'un des moyens de paiement disponible ci-dessous :
					</p>
					<?php
					$paypal_activate = getConfig("paypal_activate");
					if($paypal_activate == 'yes')
					{
					?>
					<a class="btnPaymentsLink" href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=5&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>">
						<div class="btnPayments">
						 <img src="<?php echo $url_script; ?>/images/paypal_mini_icon.png" width=17> Payer avec Paypal
						</div>
					</a>
					<?php
					}
					
					$paydunya_activate = getConfig("paydunya_activate");
					if($paydunya_activate == 'yes')
					{
					?>
						<a class="btnPaymentsLink" href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=7&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>">
							<div class="btnPayments">
								<img src="<?php echo $url_script; ?>/images/paydunya_mini_icon.png" width=17> Payer avec Paydunya
							</div>
						</a>
					<?php
					}
					
					$stripe_activate = getConfig("stripe_activate");
					if($stripe_activate == 'yes')
					{
					?>
						<style>
						.stripe-button-el 
						{
							display:none;
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
						  <button class="btnPayments">
							<img src="<?php echo $url_script; ?>/images/carte-bancaire-mini-icon.png" width=17> Payer par Carte bancaire
						  </button>
						</form>
					<?php
					}
					
					$cheque_activate = getConfig("cheque_activate");
					if($cheque_activate == 'yes')
					{
						?>
						<a class="btnPaymentsLink" href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=6&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>">
							<div class="btnPayments">
								<img src="<?php echo $url_script; ?>/images/cheque_mini_icon.png" width=17> Payer par Chèque
							</div>
						</a>
						<?php
					}

					$virement_activate = getConfig("virement_activate");
					if($virement_activate == 'yes')
					{
						?>
						<a class="btnPaymentsLink" href="<?php echo $url_script; ?>/deposer-une-annonce.php?step=10&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>">
							<div class="btnPayments">
								<img src="<?php echo $url_script; ?>/images/virement_mini_icon.png" width=17> Payer par Virement Bancaire
							</div>
						</a>
						<?php
					}
					
					$mobicash_activate = getConfig("mobicash_activate");
					if($mobicash_activate == 'yes')
					{
						?>
						<a class="btnPaymentsLink" href="deposer-une-annonce.php?step=11&total=<?php echo $total; ?>&md5=<?php echo $md5; ?>&urgente=<?php echo $urgente; ?>&r15=<?php echo $remonter_15jours; ?>&r30=<?php echo $remonter_30jours; ?>">
							<div class="btnPayments">
								<img src="<?php echo $url_script; ?>/images/emoney-mini-icon.png" width=17> Payer par Mobicash
							</div>
						</a>
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
							
							/* Si il a été configurer en Modération ou non */
							if(getConfig("moderation_activer") == 'yes')
							{
								$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
								$pdo->query($SQL);
							}
							else
							{
								$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE md5 = '$md5'";
								$pdo->query($SQL);
							}
							
							$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
							$r = $pdo->query($SQL);
							$rr = $r->fetch();
							
							$titreannonce = $rr['titre'];
							
							/* Envoyez un email à l'utilisateur après le paiement */
							$currencysigle = $class_monetaire->getSigle();
							
							$sujet = 'Merci de votre achat pour l\'annonces '.$titreannonce.' sur '.$url_script;
							//$sujet = 'Confirmer votre inscription au site '.$_REQUEST['HTTP_HOST'];
							$messageHTML = 'Bonjour,<br><br>Merci pour votre achat pour l\'annonces "'.$titreannonce.'" d\'un montant de '.number_format($p,2,",","").' '.$currencysigle.' sur le site '.$url_script.'<br>Votre annonce devrait être valider rapidement par notre équipe.<br><br>Cordialement,<br>'.$url_script;
							$messageTEXTE = 'Bonjour'.'\n\n'.'Merci pour votre achat pour l\'annonces "'.$titreannonce.'" d\'un montant de '.number_format($p,2,",","").' '.$currencysigle.' sur le site '.$url_script.'\n'.'Votre annonce devrait être valider rapidement par notre équipe.'.'\n\n'.'Cordialement'.'\n'.$url_script;
							
							$messageHTML = str_replace("[pseudo]",$pseudo,$messageHTML);
							$messageHTML = str_replace("[email]",$email,$messageHTML);
							$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
							$messageHTML = str_replace("[logo]",'',$messageHTML);		
							
							/* On va chercher le template de mail */
							$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
							$reponse = $pdo->query($SQL);
							$req = $reponse->fetch();
							
							$mail_template = $req['code'];
							
							$data = file_get_contents("mail/".$mail_template.".tpl");
							$data = str_replace("{logo}",'<img src="'.$url_script.'/images/'.getConfig("logo").'" alt="Petites annonces gratuites en France">',$data);
							$data = str_replace("{message}",$messageHTML,$data);
							$data = str_replace("{website}",$url_script,$data);
							$messageHTML = $data;
							
							$messageTEXTE = str_replace("[pseudo]",$pseudo,$messageTEXTE);
							$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
							$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
							$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
							
							$boundary = "-----=".md5(rand());
							$passage_ligne = "\r\n";
							
							$expediteurmail = getConfig("nom_expediteur_mail");
							$adresse_expediteur_mail = getConfig("adresse_expediteur_mail");
							$reply_expediteur_mail = getConfig("reply_expediteur_mail");
							
							$header = "From: \"$expediteurmail\"<$adresse_expediteur_mail>".$passage_ligne;
							if($reply_expediteur_mail != '')
							{
								$header.= "Reply-to: \"$expediteurmail\" <$reply_expediteur_mail>".$passage_ligne;
							}
							$header.= "MIME-Version: 1.0".$passage_ligne;
							$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
							
							//=====Création du message.
							$message = $passage_ligne."--".$boundary.$passage_ligne;
							//=====Ajout du message au format texte.
							$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
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
							
							$destinataire = $_SESSION['email'];
							mail($destinataire,$sujet,$message,$header);
							
							/* Envoie un email à l'admin */
							$sujet = 'Un achat de '.number_format($p,2,",","").' pour l\'annonce '.$titreannonce.' vient d\'être effectuer sur '.$url_script;
							//$sujet = 'Confirmer votre inscription au site '.$_REQUEST['HTTP_HOST'];
							$messageHTML = 'Bonjour,<br><br>Un achat vient d\'être effectuer pour un montant de '.number_format($p,2,",","").' '.$currencysigle.' pour l\'annonce "'.$titreannonce.'" sur le site '.$url_script.'<br>Veuillez vous connectez à votre administration pour contrôler et valider l\'annonce.<br><br>Cordialement,<br>'.$url_script;
							$messageTEXTE = 'Bonjour'.'\n\n'.'Un achat vient d\'être effectuer pour un montant de '.number_format($p,2,",","").' '.$currencysigle.' pour l\'annonces "'.$titreannonce.'" sur le site '.$url_script.'\n'.'Veuillez vous connectez à votre administration pour contrôler et valider l\'annonce.'.'\n\n'.'Cordialement'.'\n'.$url_script;
							
							$messageHTML = str_replace("[pseudo]",$pseudo,$messageHTML);
							$messageHTML = str_replace("[email]",$email,$messageHTML);
							$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
							$messageHTML = str_replace("[logo]",'',$messageHTML);		
							
							/* On va chercher le template de mail */
							$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
							$reponse = $pdo->query($SQL);
							$req = $reponse->fetch();
							
							$mail_template = $req['code'];
							
							$data = file_get_contents("mail/".$mail_template.".tpl");
							$data = str_replace("{logo}",'<img src="'.$url_script.'/images/'.getConfig("logo").'" alt="Petites annonces gratuites en France">',$data);
							$data = str_replace("{message}",$messageHTML,$data);
							$data = str_replace("{website}",$url_script,$data);
							$messageHTML = $data;
							
							$messageTEXTE = str_replace("[pseudo]",$pseudo,$messageTEXTE);
							$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
							$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
							$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
							
							$boundary = "-----=".md5(rand());
							$passage_ligne = "\r\n";
							
							$expediteurmail = getConfig("nom_expediteur_mail");
							$adresse_expediteur_mail = getConfig("adresse_expediteur_mail");
							$reply_expediteur_mail = getConfig("reply_expediteur_mail");
							
							$header = "From: \"$expediteurmail\"<$adresse_expediteur_mail>".$passage_ligne;
							if($reply_expediteur_mail != '')
							{
								$header.= "Reply-to: \"$expediteurmail\" <$reply_expediteur_mail>".$passage_ligne;
							}
							$header.= "MIME-Version: 1.0".$passage_ligne;
							$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
							
							//=====Création du message.
							$message = $passage_ligne."--".$boundary.$passage_ligne;
							//=====Ajout du message au format texte.
							$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
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

							$email_reception = getConfig("email_reception");
							mail($email_reception,$sujet,$message,$header);
							
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
				$total = $_REQUEST['total'];
				
				$urgente = $_REQUEST['urgente'];
				$r15 = $_REQUEST['r15'];
				$r30 = $_REQUEST['r30'];
				
				if($urgente == 'yes')
				{
					$SQL = "UPDATE pas_annonce SET urgente = 2 WHERE md5 = '$md5'";
					$pdo->query($SQL);
				}
				
				if($r15 == 'yes')
				{
					$SQL = "UPDATE pas_annonce SET quinzejour = 'yes' WHERE md5 = '$md5'";
					$pdo->query($SQL);
				}
				
				if($r30 == 'yes')
				{
					$SQL = "UPDATE pas_annonce SET unmois = 'yes' WHERE md5 = '$md5'";
					$pdo->query($SQL);
				}
				
				$SQL = "UPDATE pas_annonce SET status = 'WAIT_CHEQUE' WHERE md5 = '$md5'";
				$pdo->query($SQL);
				
				$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				$titreannonce = $req['titre'];
				
				/* On envoie un email de rappel à l'utilisateur */
				$message = 'Bonjour,<br><br>Merci d\'avoir déposer votre annonce "'.$titreannonce.'" sur le site '.$url_script.', pour finaliser le dépôt de votre annonce vous avez selectionné le mode de réglement par chèque. Merci de nous envoyez votre chèque d\'un montant de '.number_format($total,2,',','').' € à l\'ordre de <b>'.getConfig("cheque_ordre").'</b> à l\'adresse suivante :<br><br>';
				$message .= getConfig("expedition_cheque").'<br><br>';
				$message .= 'A la reception de votre chèque nous validerons votre annonce sur le site.<br><br>';
				$message .= 'Cordialement,<br>';
				$message .= $url_script;
				$class_email->sendMailTemplate($_SESSION['email'],'Nous attendons le réglement de votre annonces "'.$titreannonce.'" sur '.$url_script,$message);
				
				?>
				<H1>Votre annonce est en attente de réglements</H1>
				<p>
				Pour activer et régler votre annonce vous avez choisi de régler par Chèque, veuillez envoyez un chèque à l'ordre de : <b>"<?php echo getConfig("cheque_ordre"); ?>"</b> avec le montant à régler de "<?php echo number_format($total,2,',',''); ?>" à l'adresse suivante :<br><br>
				<div style="border: 1px dashed #000000;padding: 20px;margin-bottom: 20px;background-color: #fdffd2;">
				<b><?php echo getConfig("expedition_cheque"); ?></b>
				</div>
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
			else if($step == 10)
			{
				/* Reglement par virement bancaire */
				$md5 = $_REQUEST['md5'];
				$total = $_REQUEST['total'];
				
				$urgente = $_REQUEST['urgente'];
				$r15 = $_REQUEST['r15'];
				$r30 = $_REQUEST['r30'];
				
				if($urgente == 'yes')
				{
					$SQL = "UPDATE pas_annonce SET urgente = 2 WHERE md5 = '$md5'";
					$pdo->query($SQL);
				}
				
				if($r15 == 'yes')
				{
					$SQL = "UPDATE pas_annonce SET quinzejour = 'yes' WHERE md5 = '$md5'";
					$pdo->query($SQL);
				}
				
				if($r30 == 'yes')
				{
					$SQL = "UPDATE pas_annonce SET unmois = 'yes' WHERE md5 = '$md5'";
					$pdo->query($SQL);
				}
				
				$SQL = "UPDATE pas_annonce SET status = 'WAIT_VIREMENT' WHERE md5 = '$md5'";
				$pdo->query($SQL);
				
				$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				$titreannonce = $req['titre'];
				
				/* On envoie un email de rappel à l'utilisateur */
				$message = getConfig("email_reglement_bancaire_html");
				$message = str_replace("[logo]","",$message);
				$message = str_replace("[saut_ligne]","<br>",$message);
				$message = str_replace("[title]",$titreannonce,$message);
				$message = str_replace("[montant]",number_format($total,2,',','').' '.$class_monetaire->getSigle(),$message);
				$message = str_replace("[information_bancaire]",getConfig("virement_bancaire_instruction"),$message);
				
				$sujet = getConfig("sujet_reglement_bancaire_email");
				$sujet = str_replace("[title]",$titreannonce,$sujet);
				
				$class_email->sendMailTemplate($_SESSION['email'],$sujet,$message);
				
				?>
				<H1>Votre annonce est en attente de réglements</H1>
				<p>
				Pour activer et régler votre annonce vous avez choisi de régler par Virement bancaire, veuillez effectuer un virement d'un montant de "<?php echo number_format($total,2,',',''); ?>" <?php echo $class_monetaire->getSigle(); ?> sur le compte bancaire suivant :<br><br>
				<div style="border: 1px dashed #000000;padding: 20px;margin-bottom: 20px;background-color: #fdffd2;">
				<?php
				echo getConfig("virement_bancaire_instruction");
				?>
				</div>
				Notre équipe validera votre annonce à la reception de votre paiement dans les plus bref délais, vous pourrez retrouver
				l'évolution de la validation de votre annonce dans le menu "Mes Annonces"
				</p>
				<?php
			}
			else if($step == 11)
			{
				/* Mobicash */
				$total = AntiInjectionSQL($_REQUEST['total']);
				$urgente = AntiInjectionSQL($_REQUEST['urgente']);
				
				$r15 = AntiInjectionSQL($_REQUEST['r15']);
				$r30 = AntiInjectionSQL($_REQUEST['r30']);
				
				$md5 = AntiInjectionSQL($_REQUEST['md5']);
				
				$class_mobicash->paidStep(getConfig("mobicash_pays"),$total,getConfig("mobicash_phone"),"deposer-une-annonce.php?md5=$md5&total=$total&urgente=$urgente&r15=$r15&r30=$r30&step=12");
			}
			else if($step == 12)
			{
				/* Mobicash Final*/
				$md5 = AntiInjectionSQL($_REQUEST['md5']);
				$total = AntiInjectionSQL($_REQUEST['total']);
				$urgente = AntiInjectionSQL($_REQUEST['urgente']);
				$mobicash_phone = AntiInjectionSQL($_REQUEST['mobicash_phone']);
				
				$r15 = AntiInjectionSQL($_REQUEST['r15']);
				$r30 = AntiInjectionSQL($_REQUEST['r30']);

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
				
				$SQL = "UPDATE pas_annonce SET status = 'WAIT_MOBICASH' WHERE md5 = '$md5'";
				$pdo->query($SQL);
				
				$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				$iduser = $req['iduser'];
				
				$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				$email = $req['email'];
				
				/* On ajoute le paiement mais non valider */
				$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('mobicash','$total','$email','Téléphone $mobicash_phone','$md5',NOW())";
				$pdo->query($SQL);
				
				?>
				<H1>Votre achat à bien été prise en compte</H1>
				<p>
				Votre annonce ainsi que votre paiement va être vérifier et valider par notre équipe sur le site internet,
				nous vous remercions de votre achat. Une fois la validation effectuer votre annonce apparaitra sur le site internet.
				</p>
				<a href="index.php" class="btnConfirm">Accueil</a>
				<?php
				exit;
			}
		}
		else
		{
			
		?>
		<H1>Déposer une annonce</H1>
		<?php
			if($isConnected)
			{
				$module_filtre_auto = getConfig("module_filtre_auto");
				$module_filtre_immo = getConfig("module_filtre_immo");
				?>
				<form method="POST" onsubmit="return checkForm();" enctype="multipart/form-data">
					<label>Categorie :</label>
					<?php
					if($module_filtre_auto == 'true')
					{
						?>
						<select name="categorie" id="categorie" class="selectbox" onchange="updateFiltre();">
						<?php
					}
					else
					{
						?>
						<select name="categorie" class="selectbox">
						<?php
					}
					$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0 ORDER BY titre ASC";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						echo '<option value="'.$req['id'].'" disabled style="background-color:#aaaaaa;color:#ffffff;">'.$req['titre'].'</option>';
						$subcategorie = $req['id'];
						$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = $subcategorie ORDER BY titre ASC";
						$r = $pdo->query($SQL);
						while($rr = $r->fetch())
						{
							if($categorie == $rr['id'])
							{
								echo '<option value="'.$rr['id'].'" selected>'.$rr['titre'].'</option>';
							}
							else
							{
								echo '<option value="'.$rr['id'].'">'.$rr['titre'].'</option>';
							}
						}
					}
					?>
					</select>
					<label>Type d'annonce :</label><br><br>
					<input type="radio" name="type_annonce" value="offre" checked> Offres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type_annonce" value="demande"> Demandes<br><br>
					<?php
					if($module_filtre_auto == 'true' && $module_filtre_immo == 'true')
					{
						?>
						<div id="filtre">
						</div>
						<script>
						function updateFiltre()
						{
							var categorie = $('#categorie').val();
							$('#filtre').load('module/checkFiltre.php?categorie='+categorie);
						}
						</script>
						<?php
					}
					?>
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
					<div style="overflow:auto;margin-bottom:10px;overflow-x: hidden;overflow-y: hidden;">
					<?php
					
					$md5 = md5(microtime());
					$class_image_uploader->showUploader(getConfig("nbr_max_photo"),192,149,$md5);
					
					if(getConfig("pack_photo_activer") == 'yes')
					{
						?>
						<style>
						.pack-photo
						{
							border:2px dashed #f00;
							cursor:pointer;
						}
						
						.pack-photo-title
						{
							font-weight:bold;
							color:#f00;
						}
						</style>
						<div class="image-uploader pack-photo" id="btn-pack-photo" onclick="addPackphoto();">
							<div class="image-uploader-text" id="image-uploader-text-0"><img src="https://www.shua-creation.com/clone/pas/images/photo-icon.png"><br><span class="pack-photo-title"><?php echo getConfig("nbr_max_photo_payant"); ?> Photos supplémentaire</span></div>
						</div>
						<div id="pack-photo" style="display:none;">
						<?php
						
						$class_image_uploader->addPhoto(getConfig("nbr_max_photo_payant"),getConfig("nbr_max_photo"));
						
						?>
						</div>
						<script>
						function addPackphoto()
						{
							$('#pack-photo').css('display','block');
							$('#btn-pack-photo').css('display','none');
							$('#packphoto').val('yes');
						}
						</script>
						<?php
					}
					?>
					</div>
					<input type="hidden" name="md5" value="<?php echo $md5; ?>">
					<input type="hidden" name="action" value="1">
					<input type="hidden" name="packphoto" id="packphoto" value="no">
					<label>Ville ou code postal</label>
					<input type="text" id="codepostal" autocomplete="off" onkeyup="changeCodePostal();" name="codepostal" class="inputbox">
					<div id="codepostalerror"></div>
					<div id="codepostalshow" class="codepostalshow">
						<center><img src="images/loader.gif"></center>
					</div>
					<input type="hidden" name="ville" id="ville">
					<input type="hidden" name="cp" id="cp">
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
					$('#cp').val(cp);
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
						
						<?php
						if(getConfig("activate_check_telephone") == 'yes')
						{
						?>
						if(telephone.length < <?php echo $nbr_chiffre_numero; ?>)
						{
							error = 1;
							$('#telephone').css('border','1px solid #ff0000');
							$('#errorphone').html('<font color=red><b><?php echo str_replace("'","\'",$class_menu->getLangue("erreur_nombre_numero",$language)); ?></b></font>');
						}
						
						if(telephone.length > <?php echo $nbr_chiffre_numero; ?>)
						{
							error = 1;
							$('#telephone').css('border','1px solid #ff0000');
							$('#errorphone').html('<font color=red><b><?php echo str_replace("'","\'",$class_menu->getLangue("erreur_nombre_numero",$language)); ?></b></font>');
						}
						<?php
						}
						?>
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
				<?php
			}
		else
		{
			$class_templateloader->loadTemplate("tpl/blockconnexion.tpl");
			$class_templateloader->show();
		}
		}
		?>
	</div>
	<?php
	
	include "footer.php";
	
	?>
</body>
</html>