<?php

include "main.php";
//include "stripe/init.php";

$email = AntiInjectionSQL($_REQUEST['email']);
$pseudo = AntiInjectionSQL($_REQUEST['pseudo']);
$password = AntiInjectionSQL($_REQUEST['password']);
$optin = AntiInjectionSQL($_REQUEST['optin']);
$type_account = AntiInjectionSQL($_REQUEST['type_account']);

if(isset($_REQUEST['step']))
{
	$step = $_REQUEST['step'];
	if($step == 2)
	{
		/* Paiement Stripe */
		if(isset($_POST['stripeToken']))
		{
			$token = $_POST['stripeToken'];
			$price = $_POST['price'];
			$md5 = $_POST['md5'];
			/* Paiement STRIPE */
			\Stripe\Stripe::setApiKey(getConfig("stripe_secret_key"));
							
			try 
			{
			  $charge = \Stripe\Charge::create(array(
				"amount" => $price, // amount in cents, again
				"currency" => "eur",
				"source" => $token,
				"description" => "Paiement compte professionel"
				));
			
				if($charge->paid == true) 
				{
					/* On update en compte PRO */
					$SQL = "UPDATE pas_user SET type_compte = 'professionel' WHERE md5 = '$md5'";
					$pdo->query($SQL);
					
					/* On ajoute la ligne pro */
					$SQL = "INSERT INTO pas_compte_pro (md5,description,logo,adresse,site_internet,categorie,slogan) VALUES ('$md5','','','','',0,'')";
					$pdo->query($SQL);
					
					/* On enregistre le paiement */
					$p = $price / 100;
					$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('stripe','$p','".$_SESSION['email']."','".$charge->id."','$md5',NOW())";
					$pdo->query($SQL);
					
					/* On envoie un email au client pour l'informer du bon déroulement du paiement */
					$subject = "Achat de votre forfait Compte Professionel";
					$message = "Bonjour,<br><br>";
					$message .= "Merci de votre achat pour la création de votre compte Professionel sur $url_script<br>";
					$message .= "votre compte est désormais créer, vous pouvez vous connecter et commencer à déposer des annonces sur le site et profitez de la boutique.<br><br>";
					$message .= "Cordialement,<br>";
					$message .= "L'équipe de $url_script";
					$class_email->sendMailTemplate($charge->source->name,$subject,$message);
					
					header("Location: paid-professionel.php?step=3");
					exit;
				}
			}
			catch(\Stripe\Error\Card $e) 
			{
				// The card has been declined
				$errorstripe = 1;
				
				$SQL = "DELETE FROM pas_user WHERE md5 = '$md5'";
				$pdo->query($SQL);
				
				$class_templateloader->showHead('');
				$class_templateloader->openBody();
			
				include "header.php";
			
				$class_templateloader->loadTemplate("tpl/paiement_professionel_unique.tpl");
				$class_templateloader->show();
				
				include "footer.php";

				$class_templateloader->closeBody();
				$class_templateloader->closeHTML();
				
				exit;
			}
		}
	}
	if($step == 3)
	{
		/* Paiement ok */
		$class_templateloader->showHead('');
		$class_templateloader->openBody();
	
		include "header.php";
	
		$class_templateloader->loadTemplate("tpl/paiement_professionel_finaliser.tpl");
		$class_templateloader->show();
		
		include "footer.php";

		$class_templateloader->closeBody();
		$class_templateloader->closeHTML();
	}
	/* Paiement Unique*/
	if($step == 4)
	{
		/* Paiement Paypal */
		$md5 = $_REQUEST['md5'];
		$total = $_REQUEST['price'];
		$paypal_email = getConfig("paypal_email");
		?>
		<center>
		<H1>Vous aller être rediriger sur Paypal pour le réglement de votre Compte Professionel</H1>
		<img src="<?php echo $url_script; ?>/images/loader.gif">
		</center>
		<form id="formpaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input name="cmd" type="hidden" value="_xclick" />
		<input name="business" type="hidden" value="<?php echo $paypal_email; ?>" />
		<input name="item_name" type="hidden" value="Paiement Compte Professionel n°<?php echo $md5; ?>" />
		<input name="amount" type="hidden" value="<?php echo $total; ?>" />
		<input name="shipping" type="hidden" value="0.00" />
		<input name="no_shipping" type="hidden" value="0" />
		<input name="currency_code" type="hidden" value="<?php echo $class_monetaire->currency; ?>" />
		<input name="tax" type="hidden" value="0.00" />
		<input name="lc" type="hidden" value="FR" />
		<input name="bn" type="hidden" value="PP-BuyNowBF" />
		<input name="notify_url" type="hidden" value="<?php echo $url_script; ?>/ipn.php" />
		<input name="custom" type="hidden" value="<?php echo "PROUNIQUE-$md5-$total"; ?>" />
		<input alt="" name="submit" src="" type="image" /><img src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
		</form>
		<script>
		document.forms["formpaypal"].submit(); 
		</script>
		<?php
		exit;
	}
	/* Paiement Mois */
	if($step == 5)
	{
		/* Paiement Paypal */
		$md5 = $_REQUEST['md5'];
		$total = $_REQUEST['price'];
		$paypal_email = getConfig("paypal_email");
		?>
		
		<center>
		<H1>Vous aller être rediriger sur Paypal pour le réglement de votre Compte Professionel</H1>
		<img src="<?php echo $url_script; ?>/images/loader.gif">
		</center>
		<form id="formpaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick-subscriptions">
		<input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
		<input type="hidden" name="lc" value="FR">
		<input type="hidden" name="item_name" value="Paiement Compte Professionel n°<?php echo $md5; ?>">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="no_shipping" value="2">
		<input type="hidden" name="src" value="1">
		<input type="hidden" name="a3" value="<?php echo $total; ?>">
		<input type="hidden" name="p3" value="1">
		<input type="hidden" name="t3" value="M">
		<input type="hidden" name="currency_code" value="<?php echo $class_monetaire->currency; ?>">
		<input name="notify_url" type="hidden" value="<?php echo $url_script; ?>/ipn.php" />
		<input name="custom" type="hidden" value="<?php echo "PROMENSUEL-$md5-$total"; ?>" />
		<input alt="" name="submit" src="" type="image" /><img src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
		</form>
		<script>
		document.forms["formpaypal"].submit(); 
		</script>
		<?php
		exit;
	}
}

if($type_account == 'professionel')
{
	$compte_pro_payant = getConfig("compte_pro_payant");
	if($compte_pro_payant != 'yes')
	{
		/* Les compte sont gratuit donc on finalise l'inscription */
		// Insertion in database
		$md5 = md5(microtime());
		
		$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','yes','no','$type_account')";
		$pdo->query($SQL);
		
		/* On ajoute la ligne pro */
		$SQL = "INSERT INTO pas_compte_pro (md5,description,logo,adresse,site_internet,categorie,slogan,visible) VALUES ('$md5','','','','',0,'','yes')";
		$pdo->query($SQL);
		
		// On envoie un email pour confirmer
		$sujet = getConfig("sujet_mail_inscription_pro");
		$messageHTML = getConfig("email_inscription_html_pro");
		$messageTEXTE = getConfig("email_inscription_text_pro");
		
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
		
		$destinataire = $email;
		mail($destinataire,$sujet,$message,$header);
		
		header("Location: $url_script/subscribe.php?valid=2");
		exit;
	}
	else
	{
		// Compte Payant
		$compte_pro_type_paiement = getConfig("compte_pro_type_paiement");
		if($compte_pro_type_paiement == 'paiement_unique')
		{
			/* Il s'agit d'un paiement unique */
			$md5 = md5(microtime());
		
			$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','yes','no','professionel')";
			$pdo->query($SQL);
			
			$class_templateloader->showHead('signaler_annonce');
			$class_templateloader->openBody();
			
			include "header.php";
			
			$class_templateloader->loadTemplate("tpl/paiement_professionel_unique.tpl");
			$class_templateloader->assign("{total}","Total : ".$class_monetaire->getReturnPrice(getConfig("pro_paiement_price_unique")));
			
			$data = NULL;
			
			/* Stripe */
			$stripe_activate = getConfig("stripe_activate");
			if($stripe_activate == 'yes')
			{
				$data = "<style>";
				$data .= ".stripe-button-el";
				$data .= "{";
				$data .= "display:none;";
				$data .= "}";
				$data .= '</style><div class="btn-paid-pro-sep">';
				$data .= '<form action="" id="formstripe" method="POST">';
				$data .= '<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"';
				$data .= 'data-key="'.getConfig("stripe_publishable_key").'"';
				$data .= 'data-amount="'.(getConfig("pro_paiement_price_unique") * 100).'"';
				$data .= 'data-name="Paiement compte professionel"';
				$data .= 'data-image="'.$url_script.'/images/'.getConfig("logo").'"';
				$data .= 'data-locale="fr"';
				$data .= 'data-label="Payer par Carte Bancaire via Stripe"';
				$data .= 'data-currency="eur">';
				$data .= '</script>';
				$data .= '<input type="hidden" name="price" value="'.(getConfig("pro_paiement_price_unique") * 100).'">';
				$data .= '<input type="hidden" name="step" value="2">';
				$data .= '<input type="hidden" name="md5" value="'.$md5.'">';
				$data .= '<button type="submit" class="btnConfirm">Payer par carte bancaire</button>';
				$data .= '</form></div>';
			}
			
			/* Paypal */
			$paypal_activate = getConfig("paypal_activate");
			if($paypal_activate == 'yes')
			{
				$price = getConfig("pro_paiement_price_unique");
				$data .= '<div class="btn-paid-pro-sep"><a href="'.$url_script.'/paid-professionel.php?step=4&md5='.$md5.'&price='.$price.'" class="btnConfirm">Payer avec Paypal</a></div>';
			}
			
			if($data == NULL)
			{
				$data = '<b><font color=red>Aucun système de paiement configurer</font></b>';
			}
			
			$class_templateloader->assign("{btn_paid}",$data);			
			$class_templateloader->show();

			include "footer.php";

			$class_templateloader->closeBody();
			$class_templateloader->closeHTML();
		}
		else if($compte_pro_type_paiement == 'paiement_mensuel')
		{
			/* Il s'agit d'un paiement mensuelle */
			$md5 = md5(microtime());
			
			$account_activation = getConfig("account_activation");			
			if($account_activation == 'yes')
			{
				$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','no','no','professionel')";
				$pdo->query($SQL);
			}
			else
			{
				$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','no','no','professionel')";
				$pdo->query($SQL);
			}
			
			$class_templateloader->showHead('signaler_annonce');
			$class_templateloader->openBody();
			
			include "header.php";
			
			$class_templateloader->loadTemplate("tpl/paiement_professionel_mensuel.tpl");
			$class_templateloader->assign("{total}",$class_monetaire->getReturnPrice(getConfig("pro_paiement_price_mois")));
			
			$paypal_activate = getConfig("paypal_activate");
			if($paypal_activate == 'yes')
			{
				$price = getConfig("pro_paiement_price_mois");
				$data .= '<div class="btn-paid-pro-sep"><a href="'.$url_script.'/paid-professionel.php?step=5&md5='.$md5.'&price='.$price.'" class="btnConfirm">Payer avec Paypal</a></div>';
			}
			
			if($data == NULL)
			{
				$data = '<b><font color=red>Aucun système de paiement configurer</font></b>';
			}
			
			$class_templateloader->assign("{btn_paid}",$data);
			
			$class_templateloader->show();

			include "footer.php";

			$class_templateloader->closeBody();
			$class_templateloader->closeHTML();
		}
		else if($compte_pro_type_paiement == 'paiement_annonce')
		{
			/* Gratuit mais payant sur les annonces */			
			$md5 = md5(microtime());
		
			$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','yes','no','$type_account')";
			$pdo->query($SQL);
			
			/* On ajoute la ligne pro */
			$SQL = "INSERT INTO pas_compte_pro (md5,description,logo,adresse,site_internet,categorie,slogan,visible) VALUES ('$md5','','','','',0,'','yes')";
			$pdo->query($SQL);
			
			// On envoie un email pour confirmer
			$sujet = getConfig("sujet_mail_inscription_pro");
			$messageHTML = getConfig("email_inscription_html_pro");
			$messageTEXTE = getConfig("email_inscription_text_pro");
			
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
			
			$destinataire = $email;
			mail($destinataire,$sujet,$message,$header);
			
			header("Location: $url_script/subscribe.php?valid=2");
			exit;
		}	
	}
}
else
{
	header("Location: $url_script");
	exit;
}

?>