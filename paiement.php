<?php

include "main.php";

$action = 0;
$prix = 0;
$data = "";

$titleSEO = getTitleSEO('deposer_une_annonce');
$descriptionSEO = getDescriptionSEO('deposer_une_annonce');

$template = getConfig("template");

$class_templateloader->showHead('deposer_une_annonce');
$class_templateloader->openBody();

include "header.php";

$md5 = AntiInjectionSQL($_REQUEST['md5']);

if(isset($_REQUEST['option']))
{
	$option = $_REQUEST['option'];
	if($option != '')
	{
		/* Update des options de l'annonce */
		$o = $option;
		$o = AntiInjectionSQL($o);
		$SQL = "UPDATE pas_annonce SET option_annonce = '$o' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		$option = explode(",",$option);
	}
}
else
{
	/* On check si les options sont pas dans la base */
	$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE option_annonce != '' AND md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if($req[0] != 0)
	{
		$SQL = "SELECT * FROM pas_annonce WHERE option_annonce != '' AND md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$option = $req['option_annonce'];
		$option = explode(",",$option);
	}
}

$prix = 0;

/* On check la categorie d'annonce */
$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$idcategorie = $req['idcategorie'];

/* On check si un prix spécifique à été donner pour une annonce par categorie */
$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = $idcategorie";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] != 0)
{
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = $idcategorie";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$prix = $prix + $req['prix'];
	
	$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
	$data .= '<div style="float:left;font-size:18px;">';
	$data .= '<input type="checkbox" value="" checked disabled> Prix pour une annonce de la categorie "CAT"';
	$data .= '</div>';
	$data .= '<div style="float:right;font-size:18px;">';
	$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
	$data .= '</div>';
	$data .= '</div>';
}
else
{
	/* On check si un prix spécifique à été donner pour une annonce */
	$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = 0";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req[0] != 0)
	{
		$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = 0";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$prix = $prix + $req['prix'];
		
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" value="" checked disabled> Prix pour une annonce';
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
		$data .= '</div>';
		$data .= '</div>';
	}
	else
	{
		$prix = 0;
		
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" value="" checked disabled> Prix pour une annonce';
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : Gratuite';
		$data .= '</div>';
		$data .= '</div>';
	}
}

/* On check le pack photo */
$nbr_photo_gratuite = getConfig("nbr_max_photo");

$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$nbr_photo_annonce = $req[0];

if($nbr_photo_annonce > $nbr_photo_gratuite)
{
	/* On va determinée si il existe un pack photo pour la catégorie de l'annonce */
	$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE emplacement = 'photo' AND categorie = $idcategorie";
	$r = $pdo->query($SQL);
	$rr = $r->fetch();
	
	if($rr[0] == 0)
	{
		/* Il n'existe pas de pack photo pour cette catégorie, donc nous allons chercher plus globalement le prix d'un pack photo */
		$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'photo'";
		$r = $pdo->query($SQL);
		$rr = $r->fetch();
		
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" value="" checked disabled> '.$rr['nom'];
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($rr['prix']);
		$data .= '</div>';
		$data .= '</div>';
		
		$prix = $prix + $rr['prix'];
	}
	else
	{
		/* Il existe un prix pour un pack photo dans la categorie */
		$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'photo' AND categorie = $idcategorie";
		$r = $pdo->query($SQL);
		$rr = $r->fetch();
		
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" value="" checked disabled> '.$rr['nom'];
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($rr['prix']);
		$data .= '</div>';
		$data .= '</div>';
		
		$prix = $prix + $rr['prix'];
	}
}

/* S'il y'a des options on l'ajoute au prix */
if($option != '')
{
	if(count($option) != 0)
	{
		for($x=0;$x<count($option);$x++)
		{
			$o = $option[$x];
			$SQL = "SELECT * FROM pas_grille_tarif WHERE id = $o";
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$prix = $prix + $rr['prix'];
		}
	}
}

/* On verifie si l'utilisateur doit payer ou non */
if($prix == 0)
{
	/* On update le status de l'annonce */
	$SQL = "UPDATE pas_annonce SET status = 'FREE' WHERE md5 = '$md5'";
	$pdo->query($SQL);
	
	$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$iduser = $req['iduser'];
	$titreannonce = $req['titre'];
	
	$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$email_user = $req['email'];
	$username = $req['username'];
	
	/* On check si la modération d'annonce est activer ou non */
	$moderation_activer = getConfig("moderation_activer");
	if($moderation_activer == 'yes')
	{
		/* On est en moderation valider = no */
		$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		/* Si les annonces sont en modération nous envoyons un email à l'admin */
		$email = getConfig("email_reception");
		$sujet = "Une nouvelle annonce en attente de validation vient d'être déposez sur votre site ".$url_script;
		$message = "Bonjour,<br><br>";
		$message .= "Une nouvelle annonce est en attente de validation sur votre site internet ".$url_script." vous pouvez la valider depuis votre administration<br>";
		$message .= "et vérifier qu'elle correspond bien à la charte de votre site internet.<br><br>Ce message à été envoyer automatiquement depuis PAS Script";
		
		$class_email->sendMailTemplate($email,$sujet,$message);
		
		/* On envoie un email au client */
		$email = $email_user;
		$sujet = getConfig("sujet_mail_depot_annonce");
		$sujet = str_replace("[logo]","",$sujet);
		$sujet = str_replace("[email]",$email,$sujet);
		$sujet = str_replace("[pseudo]",$username,$sujet);
		$sujet = str_replace("[saut_ligne]","",$sujet);
		$sujet = str_replace("[titre]",$titreannonce,$sujet);
		
		$message = getConfig("email_depot_annonce_html");
		$message = str_replace("[logo]","",$message);
		$message = str_replace("[email]",$email,$message);
		$message = str_replace("[pseudo]",$username,$message);
		$message = str_replace("[saut_ligne]","<br>",$message);
		$message = str_replace("[titre]",$titreannonce,$message);
		
		$class_email->sendMailTemplate($email,$sujet,$message);
	}
	else
	{
		/* Si aucune modération donc l'annonce est valider immédiatement */
		$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE md5 = '$md5'";
		$pdo->query($SQL);		
		
		$email = $email_user;
		$sujet = getConfig("sujet_validation_annonce");
		$sujet = str_replace("[logo]","",$sujet);
		$sujet = str_replace("[email]",$email,$sujet);
		$sujet = str_replace("[pseudo]",$username,$sujet);
		$sujet = str_replace("[saut_ligne]","",$sujet);
		$sujet = str_replace("[titre]",$titreannonce,$sujet);
		
		$message = getConfig("email_validation_annonce_html");
		$message = str_replace("[logo]","",$message);
		$message = str_replace("[email]",$email,$message);
		$message = str_replace("[pseudo]",$username,$message);
		$message = str_replace("[saut_ligne]","<br>",$message);
		$message = str_replace("[titre]",$titreannonce,$message);
		
		$class_email->sendMailTemplate($email,$sujet,$message);
	}
	
	$class_templateloader->loadTemplate("tpl/validation_message_depot_annonce.tpl");
	$class_templateloader->show();
	
	include "footer.php";
	
	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
	exit;
}
else
{
	/* Le prix n'est pas à zéro en attente de paiement donc */
	$SQL = "UPDATE pas_annonce SET status = 'WAITING_PAID' WHERE md5 = '$md5'";
	$pdo->query($SQL);
	
	/* Update en valider = no il y'a un paiement selectionner donc pas de validation */
	$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
	$pdo->query($SQL);
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}

/* Paiement par Paypal */
if($action == 1)
{
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
	<input name="amount" type="hidden" value="<?php echo $prix; ?>" />
	<input name="shipping" type="hidden" value="0.00" />
	<input name="no_shipping" type="hidden" value="0" />
	<input name="currency_code" type="hidden" value="<?php echo $class_monetaire->currency; ?>" />
	<input name="tax" type="hidden" value="0.00" />
	<input name="lc" type="hidden" value="FR" />
	<input name="bn" type="hidden" value="PP-BuyNowBF" />
	<input name="notify_url" type="hidden" value="<?php echo $url_script; ?>/ipn.php" />
	<input name="custom" type="hidden" value="<?php echo "$md5"; ?>" />
	<input alt="" name="submit" src="" type="image" /><img src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
	</form>
	<script>
	document.forms["formpaypal"].submit(); 
	</script>
	<?php
	
	include "footer.php";
	
	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
	exit;
}

/* Paiement par Paydunya */
if($action == 2)
{
	
}

/* Paiement par Stripe */
if($action == 3)
{
	$class_stripe->setApiSecretAndPublisherKey(getConfig("stripe_secret_key"),getConfig("stripe_publishable_key"));
	$titre = 'Paiement de petite annonce n°'.$md5;
	$description = 'Paiement de petite annonce';
	$icon = getConfig("logo");
	$currency = $class_monetaire->getCurrencyCode();
	$qty = 1;
	
	$cancelurl = $url_script."/cancel.php?md5=".$md5;
	$successurl = $url_script."/success.php?md5=".$md5;
	$ref = $md5;
	
	$class_stripe->stripePaidSimple($titre,$description,$icon,$prix,$currency,$qty,$cancelurl,$successurl,$ref);
	
	?>
	<center>
	<H1>Vous aller être rediriger pour le réglement de votre annonces</H1>
	<img src="<?php echo $url_script; ?>/images/loader.gif">
	</center>
	<?php
	exit;
}

/* Paiement par Chèque */
if($action == 4)
{
	$SQL = "UPDATE pas_annonce SET status = 'WAIT_CHEQUE' WHERE md5 = '$md5'";
	$pdo->query($SQL);
	
	$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titreannonce = $req['titre'];
	$iduser = $req['iduser'];
	
	$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$username = $req['username'];
	$email = $req['email'];
	
	/* On ajoute le paiement mais non valider */
	$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('cheque','$prix','".$email."','','$md5',NOW())";
	$pdo->query($SQL);
	
	/* On envoie un email de rappel à l'utilisateur */
	$message = getConfig("email_reglement_cheque_html");
	$message = str_replace("[logo]","",$message);
	$message = str_replace("[pseudo]",$username,$message);
	$message = str_replace("[saut_ligne]","<br>",$message);
	$message = str_replace("[titre]",$titreannonce,$message);
	$message = str_replace("[montant]",$class_monetaire->getReturnPrice($prix),$message);
	$message = str_replace("[ordre_cheque]",getConfig("cheque_ordre"),$message);
	$message = str_replace("[expedition_cheque]",getConfig("expedition_cheque"),$message);
	
	$sujet = getConfig("sujet_reglement_cheque_email");
	$sujet = str_replace("[titre]",$titreannonce,$sujet);
	
	$class_email->sendMailTemplate($email,$sujet,$message);
	
	/* On envoie un email à l'admin pour le prévenir qu'une annonce est en attente de paiement par Chèque */
	$email = getConfig("email_reception");
	$sujet = "Une annonce en attente de réglement par cheque vient d'être déposez sur votre site ".$url_script;
	$message = "Bonjour,<br><br>";
	$message .= "Une nouvelle annonce vient d'être deposer sur votre site internet ".$url_script.", le client à choisi de régler par chèque<br>";
	$message .= "pour un montant de ".$class_monetaire->getReturnPrice($prix).", le client à été informer du mode de réglement. Une fois la reception du paiement du client receptionner vous pourrez valider l'annonce depuis votre administration.<br><br>Ce message à été envoyer automatiquement depuis PAS Script";
	
	$class_email->sendMailTemplate($email,$sujet,$message);
	
	$class_templateloader->loadTemplate("tpl/paiement_cheque.tpl");
	
	$class_templateloader->assign("{ordre}",getConfig("cheque_ordre"));
	$class_templateloader->assign("{total}",$class_monetaire->getReturnPrice($prix));
	$class_templateloader->assign("{cheque_expedition}",getConfig("expedition_cheque"));
	
	$class_templateloader->show();
	
	include "footer.php";
	
	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
	exit;
}

/* Paiement par Virement */
if($action == 5)
{
	$SQL = "UPDATE pas_annonce SET status = 'WAIT_VIREMENT' WHERE md5 = '$md5'";
	$pdo->query($SQL);
	
	$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titreannonce = $req['titre'];
	
	/* On ajoute le paiement mais non valider */
	$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('virement','$prix','".$_SESSION['email']."','','$md5',NOW())";
	$pdo->query($SQL);
	
	/* On envoie un email de rappel à l'utilisateur */
	$message = getConfig("email_reglement_bancaire_html");
	$message = str_replace("[logo]","",$message);
	$message = str_replace("[saut_ligne]","<br>",$message);
	$message = str_replace("[title]",$titreannonce,$message);
	$message = str_replace("[montant]",$class_monetaire->getReturnPrice($prix),$message);
	$message = str_replace("[information_bancaire]",getConfig("virement_bancaire_instruction"),$message);
	
	$sujet = getConfig("sujet_reglement_bancaire_email");
	$sujet = str_replace("[title]",$titreannonce,$sujet);
	
	$class_email->sendMailTemplate($_SESSION['email'],$sujet,$message);
	
	/* On envoie un email à l'admin pour le prévenir qu'une annonce est en attente de paiement par Chèque */
	$email = getConfig("email_reception");
	$sujet = "Une annonce en attente de réglement par virement vient d'être déposez sur votre site ".$url_script;
	$message = "Bonjour,<br><br>";
	$message .= "Une nouvelle annonce vient d'être deposer sur votre site internet ".$url_script.", le client à choisi de régler par virement bancaire<br>";
	$message .= "pour un montant de ".$class_monetaire->getReturnPrice($prix).", le client à été informer du mode de réglement. Une fois la reception du paiement du client receptionner vous pourrez valider l'annonce depuis votre administration.<br><br>Ce message à été envoyer automatiquement depuis PAS Script";
	
	$class_email->sendMailTemplate($email,$sujet,$message);
	
	$class_templateloader->loadTemplate("tpl/paiement_virement_bancaire.tpl");
	
	$class_templateloader->assign("{total}",$class_monetaire->getReturnPrice($prix));
	$class_templateloader->assign("{virement_instruction}",getConfig("virement_bancaire_instruction"));
	
	$class_templateloader->show();
	
	include "footer.php";
	
	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
	exit;
}

/* Paiement par Emoney Mobicash */
if($action == 6)
{
	$class_mobicash->paidStep(getConfig("mobicash_pays"),$prix,getConfig("mobicash_phone"),"paiement.php?md5=$md5&action=7&option=".implode(",",$option));
}

/* Final paiement Mobicash */
if($action == 7)
{
	$mobicash_phone = AntiInjectionSQL($_REQUEST['mobicash_phone']);
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
	$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('mobicash','$prix','$email','Téléphone $mobicash_phone','$md5',NOW())";
	$pdo->query($SQL);
	
	/* On envoie un email à l'admin pour le prévenir qu'une annonce est en attente de paiement par Chèque */
	$email = getConfig("email_reception");
	$sujet = "Une annonce en attente de réglement par Emoney vient d'être déposez sur votre site ".$url_script;
	$message = "Bonjour,<br><br>";
	$message .= "Une nouvelle annonce vient d'être deposer sur votre site internet ".$url_script.", le client à choisi de régler par Emoney<br>";
	$message .= "pour un montant de ".$class_monetaire->getReturnPrice($prix).", Vous devriez recevoir un SMS de confirmation de paiement du numero ".$mobicash_phone." une fois la transaction confirmer vous pourrez la valider depuis votre administration.<br><br>Ce message à été envoyer automatiquement depuis PAS Script";
	
	$class_email->sendMailTemplate($email,$sujet,$message);
	
	$class_templateloader->loadTemplate("tpl/paiement_emoney.tpl");
	
	$class_templateloader->assign("{total}",$class_monetaire->getReturnPrice($prix));
	$class_templateloader->assign("{virement_instruction}",getConfig("virement_bancaire_instruction"));
	
	$class_templateloader->show();
	
	include "footer.php";
	
	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
	exit;
}
	

/* Si aucun paiement selectionner */
if($action == 0)
{
	$class_templateloader->loadTemplate("tpl/paiement.tpl");
	
	/* On check les options selectionner par l'utilisateur */
	if($option != '')
	{
		for($x=0;$x<count($option);$x++)
		{
			$option_selected = $option[$x];
			$SQL = "SELECT * FROM pas_grille_tarif WHERE id = $option_selected";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
			$data .= '<div style="float:left;font-size:18px;">';
			$data .= '<input type="checkbox" value="'.$req['id'].'" checked disabled> '.$req['nom'];
			$data .= '</div>';
			$data .= '<div style="float:right;font-size:18px;">';
			$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
			$data .= '</div>';
			$data .= '</div>';
		}
	}
	
	$class_templateloader->assign("{option}",$data);
	
	$paidbtn = "";

	/* Paypal */
	$paypal_activate = getConfig("paypal_activate");
	if($paypal_activate == 'yes')
	{
		$paidbtn .= '<a class="btnPaymentsLink" href="'.$url_script.'/paiement.php?action=1&md5='.$md5.'&option='.implode(",",$option).'">';
		$paidbtn .= '<div class="btnPayments">';
		$paidbtn .= '<img src="'.$url_script.'/images/paypal_mini_icon.png" width=17> Payer avec Paypal';
		$paidbtn .= '</div>';
		$paidbtn .= '</a>';
	}
	
	/* Paydunya */
	$paydunya_activate = getConfig("paydunya_activate");
	if($paydunya_activate == 'yes')
	{
		$paidbtn .= '<a class="btnPaymentsLink" href="'.$url_script.'/paiement.php?action=1&md5='.$md5.'&option='.implode(",",$option).'">';
		$paidbtn .= '<div class="btnPayments">';
		$paidbtn .= '<img src="'.$url_script.'/images/paydunya_mini_icon.png" width=17> Payer avec Paydunya';
		$paidbtn .= '</div>';
		$paidbtn .= '</a>';
	}
	
	/* Stripe */
	$stripe_activate = getConfig("stripe_activate");
	if($stripe_activate == 'yes')
	{
		$paidbtn .= '<a class="btnPaymentsLink" href="'.$url_script.'/paiement.php?action=3&md5='.$md5.'&option='.implode(",",$option).'">';
		$paidbtn .= '<div class="btnPayments">';
		$paidbtn .= '<img src="'.$url_script.'/images/carte-bancaire-mini-icon.png" width=17> Payer par Carte Bancaire';
		$paidbtn .= '</div>';
		$paidbtn .= '</a>';
	}
	
	/* Cheque */
	$cheque_activate = getConfig("cheque_activate");
	if($cheque_activate == 'yes')
	{
		$paidbtn .= '<a class="btnPaymentsLink" href="'.$url_script.'/paiement.php?action=4&md5='.$md5.'&option='.implode(",",$option).'">';
		$paidbtn .= '<div class="btnPayments">';
		$paidbtn .= '<img src="'.$url_script.'/images/cheque_mini_icon.png" width=17> Payer par Chèque';
		$paidbtn .= '</div>';
		$paidbtn .= '</a>';
	}
	
	/* Virement Bancaire */
	$virement_activate = getConfig("virement_activate");
	if($virement_activate == 'yes')
	{
		$paidbtn .= '<a class="btnPaymentsLink" href="'.$url_script.'/paiement.php?action=5&md5='.$md5.'&option='.implode(",",$option).'">';
		$paidbtn .= '<div class="btnPayments">';
		$paidbtn .= '<img src="'.$url_script.'/images/virement_mini_icon.png" width=17> Payer par Virement Bancaire';
		$paidbtn .= '</div>';
		$paidbtn .= '</a>';
	}
	
	/* Mobicash */
	$mobicash_activate = getConfig("mobicash_activate");
	if($mobicash_activate == 'yes')
	{
		$paidbtn .= '<a class="btnPaymentsLink" href="'.$url_script.'/paiement.php?action=6&md5='.$md5.'&option='.implode(",",$option).'">';
		$paidbtn .= '<div class="btnPayments">';
		$paidbtn .= '<img src="'.$url_script.'/images/emoney-mini-icon.png" width=17> Payer par Téléphone Mobile (SMS)';
		$paidbtn .= '</div>';
		$paidbtn .= '</a>';
	}

	$class_templateloader->assign("{paidbtn}",$paidbtn);
	$class_templateloader->assign("{total}",$class_monetaire->getReturnPrice($prix));
	$class_templateloader->assign("{md5}",$md5);
	$class_templateloader->show();
	
	include "footer.php";
	
	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
}

?>