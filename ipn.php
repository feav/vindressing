<?php

/* IPN Paypal */
/* Permet le retour du Paiement par Paypal */

include "config.php";

$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) 
{
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) 
{
	$get_magic_quotes_exists = true;
}

foreach ($myPost as $key => $value) 
{
  if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) 
  {
	$value = urlencode(stripslashes($value));
  } 
  else 
  {
	$value = urlencode($value);
  }  
  $req .= "&$key=$value";
}

// Step 2: POST IPN data back to PayPal to validate
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if ( !($res = curl_exec($ch)) ) 
{
  // error_log("Got " . curl_error($ch) . " when processing IPN data");
  curl_close($ch);
  exit;
}

if (strcmp ($res, "VERIFIED") == 0) 
{
	// Le paiement est valide on récupere les informations stocker en Custom et quelque information supplémentaire
	$custom = $_POST['custom'];
	
	/* On exploite les information du CUSTOM */
	$custom = explode("-",$custom);
	if($custom[0] == 'PROUNIQUE')
	{
		$md5 = $custom[1];
		$total = $custom[2];
		
		/* On update en compte PRO */
		$SQL = "UPDATE pas_user SET type_compte = 'professionel' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		/* On ajoute la ligne pro */
		$SQL = "INSERT INTO pas_compte_pro (md5,description,logo,adresse,site_internet,categorie,slogan) VALUES ('$md5','','','','',0,'')";
		$pdo->query($SQL);
		
		/* On enregistre le paiement */
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$payer_email = $_POST['payer_email'];
		
		$SQL = "INSERT INTO pas_paiement VALUES ('','paypal','$payment_amount','$payer_email','$txn_id','$md5',NOW())";
		$pdo->query($SQL);
		
		/* On envoie un email au client pour l'informer du bon déroulement du paiement */
		$subject = "Achat de votre forfait Compte Professionel";
		$message = "Bonjour,<br><br>";
		$message .= "Merci de votre achat pour la création de votre compte Professionel sur $url_script<br>";
		$message .= "votre compte est désormais créer, vous pouvez vous connecter et commencer à déposer des annonces sur le site et profitez de la boutique.<br><br>";
		$message .= "Cordialement,<br>";
		$message .= "L'équipe de $url_script";
		$class_email->sendMailTemplate($payer_email,$subject,$message);
		exit;
	}
	if($custom[0] == 'PROMENSUEL')
	{
		$md5 = $custom[1];
		$total = $custom[2];
		
		/* On update en compte PRO */
		$SQL = "UPDATE pas_user SET type_compte = 'professionel' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		/* On ajoute la ligne pro */
		$SQL = "INSERT INTO pas_compte_pro (md5,description,logo,adresse,site_internet,categorie,slogan) VALUES ('$md5','','','','',0,'')";
		$pdo->query($SQL);
		
		/* On enregistre le paiement */
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$payer_email = $_POST['payer_email'];
		
		$SQL = "INSERT INTO pas_paiement VALUES ('','paypal','$payment_amount','$payer_email','$txn_id','$md5',NOW())";
		$pdo->query($SQL);
		
		/* On envoie un email au client pour l'informer du bon déroulement du paiement */
		$subject = "Achat de votre forfait Compte Professionel";
		$message = "Bonjour,<br><br>";
		$message .= "Merci de votre achat pour la création de votre compte Professionel sur $url_script<br>";
		$message .= "votre compte est désormais créer, vous pouvez vous connecter et commencer à déposer des annonces sur le site et profitez de la boutique.<br><br>";
		$message .= "Cordialement,<br>";
		$message .= "L'équipe de $url_script";
		$class_email->sendMailTemplate($payer_email,$subject,$message);
		exit;
	}
	
	$md5 = $custom[0];
	$total = $custom[1];
	$urgent = $custom[2];
	$r15 = $custom[3];
	$r30 = $custom[4];
	
	/* On update le STATUS du paiement en PAID_PAYPAL */
	$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$idannonce = $req['id'];
	
	/* On fini par valider l'annonce */
	$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE id = $idannonce";
	$pdo->query($SQL);
	
	$SQL = "UPDATE pas_annonce SET status = 'PAID_PAYPAL' WHERE id = $idannonce";
	$pdo->query($SQL);
	
	$option = $req['option_annonce'];
	$option = explode(",",$option);
	
	for($x=0;$x<count($option);$x++)
	{
		$idoption = $option[$x];
		$SQL = "SELECT * FROM pas_grille_tarif WHERE id = $idoption";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$emplacement = $req['emplacement'];
		if($emplacement == 'classic')
		{
			/* On paye donc un tarif sans rien de plus */
		}
		else if($emplacement == 'bandeau')
		{
			/* On paye un bandeau URGENT */
			$SQL = "UPDATE pas_annonce SET urgente = 2 WHERE id = $idannonce";
			$pdo->query($SQL);
		}
		else if($emplacement == 'photo')
		{
			/* On paye pour un Pack Photo les photo sont déjà incluse dans l'annonce rien n'as faire */
		}
		else if($emplacement == 'remonter')
		{
			$duree = $req['duree'];
			$remonter_x_jour = $req['remonter_x_jour'];
			
			$date = date('Y-m-d');
			
			$nbr_rotation = ceil($duree / $remonter_x_jour);
			$jour = 0;
			for($x=0;$x<$nbr_rotation;$x++)
			{
				$jour = $jour + $remonter_x_jour;
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval $jour day)";
				$pdo->query($SQL);
			}
		}
	}
	
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$payer_email = $_POST['payer_email'];
	
	$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('paypal','$payment_amount','$payer_email','$txn_id','$md5',NOW())";
	$pdo->query($SQL);
}
else if (strcmp ($res, "INVALID") == 0) 
{
	// Le paiement n'est pas valide et merite qu'on s'y attardent pour verifier
	$custom = explode("-",$custom);
	$md5 = $custom[0];
	
	/* On update le STATUS du paiement en ERROR_IPN */
	$SQL = "UPDATE pas_annonce SET status = 'ERROR_IPN' WHERE md5 = '$md5'";
	$pdo->query($SQL);
}

curl_close($ch);

?>