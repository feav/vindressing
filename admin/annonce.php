<?php
include "../config.php";
include "../engine/class.monetaire.php";
include "../engine/class.mail.php";
include "../engine/class.fuseau.horaire.php";
include "../engine/class.map.php";
include "version.php";

$monetaire = new Monetaire();
$sigle = $monetaire->getSigle();

$class_email = new Email();
$class_fuseau_horaire = new FuseauHoraire();

$class_map = new Map();

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
		$id = $_REQUEST['id'];
		$SQL = "DELETE FROM pas_annonce WHERE id = $id";
		$pdo->query($SQL);

		header("Location: annonce.php");
		exit;
	}
	
	if($action == 2)
	{
		$id = $_REQUEST['id'];
		$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE id = $id";
		$pdo->query($SQL);
		header("Location: annonce.php");
		exit;
	}
	/* Valider une annonces Manuellement elle est visible */
	if($action == 3)
	{
		$id = $_REQUEST['id'];
		$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET date_soumission = NOW() WHERE id = $id";
		$pdo->query($SQL);
		
		$SQL = "SELECT * FROM pas_annonce WHERE id = $id";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$titreannonce = $req['titre'];
		
		$iduser = $req['iduser'];
		$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$email = $req['email'];
		$username = $req['username'];
		
		/* Envoyez un email à l'utilisateur pour lui indiquer que l'annonce est valider */
		$sujet = getConfig("sujet_validation_annonce");
		$sujet = str_replace("[titre]",$titreannonce,$sujet);
		$sujet = str_replace("[pseudo]",$username,$sujet);
		
		$message = getConfig("email_validation_annonce_html");
		$message = str_replace("[titre]",$titreannonce,$message);
		$message = str_replace("[pseudo]",$username,$message);
		$message = str_replace("[email]",$email,$message);
		$message = str_replace("[saut_ligne]","<br>",$message);
		
		$class_email->sendMailTemplate($email,$sujet,$message);
		
		header("Location: annonce.php");
		exit;
	}
	/* Modification d'une annonce */
	if($action == 6)
	{
		$md5 = $_REQUEST['md5'];
		$offre = $_REQUEST['offre'];
		$titre = $_REQUEST['titre'];
		$texte = $_REQUEST['texte'];
		
		$titre = str_replace("'","''",$titre);
		$texte = str_replace("'","''",$texte);
		
		$codepostal = $_REQUEST['codepostal'];
		$ville = $_REQUEST['ville'];
		$telephone = $_REQUEST['telephone'];
		$prix = $_REQUEST['prix'];
		$categorie = $_REQUEST['categorie'];
		$region = $_REQUEST['region'];
		
		$urgente = $_REQUEST['urgente'];
		if($urgente == '')
		{
			$urgente = 1;
		}
		
		$quinzejour = $_REQUEST['quinzejour'];
		if($quinzejour == '')
		{
			$quinzejour = 'no';
		}
		
		$unmois = $_REQUEST['unmois'];
		if($unmois == '')
		{
			$unmois = 'no';
		}
		
		$SQL = "UPDATE pas_annonce SET unmois = '$unmois' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET quinzejour = '$quinzejour' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET urgente = $urgente WHERE md5 = '$md5'";
		$pdo->query($SQL);		
		$SQL = "UPDATE pas_annonce SET titre = '$titre' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET texte = '$texte' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET offre_demande  = '$offre' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET idcategorie = $categorie WHERE md5 = '$md5'";
		$pdo->query($SQL);		
		$SQL = "UPDATE pas_annonce SET prix = '$prix' WHERE md5 = '$md5'";
		$pdo->query($SQL);	
		$SQL = "UPDATE pas_annonce SET codepostal = '$codepostal' WHERE md5 = '$md5'";
		$pdo->query($SQL);	
		$SQL = "UPDATE pas_annonce SET ville = '$ville' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET telephone = '$telephone' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET idregion = $region WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		$status = $_REQUEST['status'];
		$SQL = "UPDATE pas_annonce SET status = '$status' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		/* Annonce gratuite */
		if($status == 'FREE')
		{
			$SQL = "DELETE FROM pas_paiement WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "DELETE FROM pas_remonter WHERE md5 = '$md5'";
			$pdo->query($SQL);
		}
		
		/* En attente de paiement */
		if($status == 'WAITING_PAID')
		{
			$SQL = "DELETE FROM pas_paiement WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "DELETE FROM pas_remonter WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
			$pdo->query($SQL);
		}
		
		/* En attente de paiement cheque */
		if($status == 'WAIT_CHEQUE')
		{
			$SQL = "DELETE FROM pas_paiement WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "DELETE FROM pas_remonter WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
			$pdo->query($SQL);
		}
		
		/* En attente de paiement cheque */
		if($status == 'WAIT_VIREMENT')
		{
			$SQL = "DELETE FROM pas_paiement WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "DELETE FROM pas_remonter WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
			$pdo->query($SQL);
		}
		
		/* Si payer par Paypal */
		if($status == 'PAID_PAYPAL')
		{
			$total = 0;
			
			$prix_annonce_normal = getConfig("prix_annonce");
			$total = $total + $prix_annonce_normal;
			
			if($urgente == 2)
			{
				$prix_annonce_urgente = getConfig("prix_urgente");
				$total = $total + $prix_annonce_urgente;
			}
			
			if($unmois == 'yes')
			{
				$prix_annonce_mois = getConfig("prix_remonter_mois");
				$total = $total + $prix_annonce_urgente;
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
			
			if($quinzejour == 'yes')
			{
				$prix_annonce_quinze = getConfig("prix_remonter_semaine");
				$total = $total + $prix_annonce_quinze;
				
				/* On ajoute les remonter */
				$date = date('Y-m-d');
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
				$pdo->query($SQL);
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
				$pdo->query($SQL);
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$email = $req['email'];
			
			/* On ajoute au paiement */
			$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('paypal','$total','$email','','$md5',NOW())";
			$pdo->query($SQL);
		}
		
		/* Si payer par Paypal */
		if($status == 'PAID_PAYDUNYA')
		{
			$total = 0;
			
			$prix_annonce_normal = getConfig("prix_annonce");
			$total = $total + $prix_annonce_normal;
			
			if($urgente == 2)
			{
				$prix_annonce_urgente = getConfig("prix_urgente");
				$total = $total + $prix_annonce_urgente;
			}
			
			if($unmois == 'yes')
			{
				$prix_annonce_mois = getConfig("prix_remonter_mois");
				$total = $total + $prix_annonce_urgente;
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
			
			if($quinzejour == 'yes')
			{
				$prix_annonce_quinze = getConfig("prix_remonter_semaine");
				$total = $total + $prix_annonce_quinze;
				
				/* On ajoute les remonter */
				$date = date('Y-m-d');
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
				$pdo->query($SQL);
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
				$pdo->query($SQL);
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$email = $req['email'];
			
			/* On ajoute au paiement */
			$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('paydunya','$total','$email','','$md5',NOW())";
			$pdo->query($SQL);
		}
		
		/* Si payer par Mobicash */
		if($status == 'PAID_MOBICASH')
		{
			$total = 0;
			
			$prix_annonce_normal = getConfig("prix_annonce");
			$total = $total + $prix_annonce_normal;
			
			if($urgente == 2)
			{
				$prix_annonce_urgente = getConfig("prix_urgente");
				$total = $total + $prix_annonce_urgente;
			}
			
			if($unmois == 'yes')
			{
				$prix_annonce_mois = getConfig("prix_remonter_mois");
				$total = $total + $prix_annonce_urgente;
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
			
			if($quinzejour == 'yes')
			{
				$prix_annonce_quinze = getConfig("prix_remonter_semaine");
				$total = $total + $prix_annonce_quinze;
				
				/* On ajoute les remonter */
				$date = date('Y-m-d');
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
				$pdo->query($SQL);
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
				$pdo->query($SQL);
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$email = $req['email'];
			
			/* On ajoute au paiement */
			$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('mobicash','$total','$email','','$md5',NOW())";
			$pdo->query($SQL);
		}
		
		/* Si payer par Virement bancaire*/
		if($status == 'PAID_VIREMENT')
		{
			$total = 0;
			
			$prix_annonce_normal = getConfig("prix_annonce");
			$total = $total + $prix_annonce_normal;
			
			if($urgente == 2)
			{
				$prix_annonce_urgente = getConfig("prix_urgente");
				$total = $total + $prix_annonce_urgente;
			}
			
			if($unmois == 'yes')
			{
				$prix_annonce_mois = getConfig("prix_remonter_mois");
				$total = $total + $prix_annonce_urgente;
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
			
			if($quinzejour == 'yes')
			{
				$prix_annonce_quinze = getConfig("prix_remonter_semaine");
				$total = $total + $prix_annonce_quinze;
				
				/* On ajoute les remonter */
				$date = date('Y-m-d');
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
				$pdo->query($SQL);
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
				$pdo->query($SQL);
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$email = $req['email'];
			
			/* On ajoute au paiement */
			$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('virement','$total','$email','','$md5',NOW())";
			$pdo->query($SQL);
		}
		
		/* Si payer par Chèque */
		if($status == 'PAID_CHECK')
		{
			$total = 0;
			
			$prix_annonce_normal = getConfig("prix_annonce");
			$total = $total + $prix_annonce_normal;
			
			if($urgente == 2)
			{
				$prix_annonce_urgente = getConfig("prix_urgente");
				$total = $total + $prix_annonce_urgente;
			}
			
			if($unmois == 'yes')
			{
				$prix_annonce_mois = getConfig("prix_remonter_mois");
				$total = $total + $prix_annonce_urgente;
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
			
			if($quinzejour == 'yes')
			{
				$prix_annonce_quinze = getConfig("prix_remonter_semaine");
				$total = $total + $prix_annonce_quinze;
				
				/* On ajoute les remonter */
				$date = date('Y-m-d');
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
				$pdo->query($SQL);
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
				$pdo->query($SQL);
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$email = $req['email'];
			
			/* On ajoute au paiement */
			$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('cheque','$total','$email','','$md5',NOW())";
			$pdo->query($SQL);
		}
		
		/* Si payer par Paypal */
		if($status == 'PAID_STRIPE')
		{
			$total = 0;
			
			$prix_annonce_normal = getConfig("prix_annonce");
			$total = $total + $prix_annonce_normal;
			
			if($urgente == 2)
			{
				$prix_annonce_urgente = getConfig("prix_urgente");
				$total = $total + $prix_annonce_urgente;
			}
			
			if($unmois == 'yes')
			{
				$prix_annonce_mois = getConfig("prix_remonter_mois");
				$total = $total + $prix_annonce_urgente;
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
			
			if($quinzejour == 'yes')
			{
				$prix_annonce_quinze = getConfig("prix_remonter_semaine");
				$total = $total + $prix_annonce_quinze;
				
				/* On ajoute les remonter */
				$date = date('Y-m-d');
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 7 day)";
				$pdo->query($SQL);
				$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval 14 day)";
				$pdo->query($SQL);
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$email = $req['email'];
			
			/* On ajoute au paiement */
			$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('stripe','$total','$email','','$md5',NOW())";
			$pdo->query($SQL);
		}
		
		$valider = $_REQUEST['valider'];
		
		$SQL = "UPDATE pas_annonce SET valider = '$valider' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		header("Location: annonce.php");
		exit;
	}
	
	/* Marquer un paiement comme Payer (Et activer les options manuellement) */
	if($action == 7)
	{
		$md5 = $_REQUEST['md5'];	
		$type = $_REQUEST['type'];
		
		$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$idtransaction = $req['id'];
		
		$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$idannonce = $req['id'];
		
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
		
		/* On fini par valider l'annonce */
		$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE id = $idannonce";
		$pdo->query($SQL);
		
		if($type == 'emoney')
		{
			$SQL = "UPDATE pas_annonce SET status = 'PAID_MOBICASH' WHERE id = $idannonce";
			$pdo->query($SQL);
		
			/* On update le paiement */
			$SQL = "UPDATE pas_paiement SET type_paiement = 'mobicash_payer' WHERE id = $idtransaction";
			$pdo->query($SQL);
		}
		if($type == 'virement')
		{
			$SQL = "UPDATE pas_annonce SET status = 'PAID_VIREMENT' WHERE id = $idannonce";
			$pdo->query($SQL);
		
			/* On update le paiement */
			$SQL = "UPDATE pas_paiement SET type_paiement = 'virement_payer' WHERE id = $idtransaction";
			$pdo->query($SQL);
		}
		if($type == 'cheque')
		{
			$SQL = "UPDATE pas_annonce SET status = 'PAID_CHECK' WHERE id = $idannonce";
			$pdo->query($SQL);
		
			/* On update le paiement */
			$SQL = "UPDATE pas_paiement SET type_paiement = 'cheque_payer' WHERE id = $idtransaction";
			$pdo->query($SQL);
		}
		
		header("Location: annonce.php");
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
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<?php
		if($action == 4)
		{			
			$md5 = $_REQUEST['md5'];
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			$titre = $req['titre'];
			$date_soumission = $req['date_soumission'];
			$texte = $req['texte'];
			$codepostal = $req['codepostal'];
			$ville = $req['ville'];
			$telephone = $req['telephone'];
			$offre_demande = $req['offre_demande'];
			$status = $req['status'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$username = $req['username'];
			
			$arrayImage = NULL;
			$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$img = $req['image'];
				$img = explode(".",$img);
				$img = $img[0]."-thumb.".$img[1];
				$arrayImage[count($arrayImage)] = $img;
			}
			
			?>
			<div style="margin-top:20px;margin-bottom:20px;">
				<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn blue">Retour</a>
			</div>
			<H1>Controler l'annonce "<?php echo $titre; ?>"</H1>
			Annonce soumisse le <i>"<?php echo $date_soumission; ?>"</i> par <b><?php echo $username; ?></b><br><br>
			<label><b>Type d'offre :</b></label>
			<input type="text" name="type_offre" value="<?php echo $offre_demande; ?>" class="inputbox">
			<label><b>Titre :</b></label>
			<input type="text" name="titre" placeholder="Pas de titre" value="<?php echo $titre; ?>" class="inputbox">
			<label><b>Description :</b></label>
			<textarea class="textareabox" name="texte" placeholder="Pas de texte"><?php echo $texte; ?></textarea>
			<label><b>Code postal :</b></label>
			<input type="text" name="codepostal" placeholder="Pas de code postal" value="<?php echo $codepostal; ?>" class="inputbox">
			<label><b>Ville :</b></label>
			<input type="text" name="ville" placeholder="Pas de ville" value="<?php echo $ville; ?>" class="inputbox">
			<label><b>Téléphone :</b></label>
			<input type="text" name="telephone" placeholder="Pas de numéro" value="<?php echo $telephone; ?>" class="inputbox">
			<label><b>Image :</b></label><br><br>
			<div style="overflow:auto;">
			<?php
			for($x=0;$x<count($arrayImage);$x++)
			{
				?>
				<div style="float:left;margin-right:10px;">
				<img src="<?php echo $url_script; ?>/images/annonce/<?php echo $arrayImage[$x]; ?>">
				</div>
				<?php
			}
			?>
			</div>
			<?php
		}
		else if($action == 5)
		{
			$md5 = $_REQUEST['md5'];
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] == 0)
			{
				echo '<b>Cette annonce à été supprimer et n\'existe plus</b>';
			}
			
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$titre = $req['titre'];
			$offre_demande = $req['offre_demande'];
			$texte = $req['texte'];
			$texte = str_replace('<br />', "\n", $texte);
			
			$codepostal = $req['codepostal'];
			$ville = $req['ville'];
			$telephone = $req['telephone'];
			$prix = $req['prix'];
			$iduser = $req['iduser'];
			$date_soumission = $req['date_soumission'];
			$date_soumission = $class_fuseau_horaire->convertTimezone($req['date_soumission'],getConfig("format_date"),getConfig("fuseau"));
			$status = $req['status'];
			$idcategorie = $req['idcategorie'];
			$urgente = $req['urgente'];
			$quinzejour = $req['quinzejour'];
			$unmois = $req['unmois'];
			$idregion = $req['idregion'];
			$valider = $req['valider'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$username = $req['username'];
			
			$arrayImage = NULL;
			$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$arrayImage[count($arrayImage)] = $req['image'];
			}
			?>
			<div style="margin-top:20px;margin-bottom:20px;">
				<a href="annonce.php" class="btn blue">Retour</a>
			</div>
			<H1>Modifier l'annonce "<?php echo $titre; ?>"</H1>
			<b>Annonce soumisse le :</b> <?php echo $date_soumission; ?><br>
			<b>Annonce déposer par :</b> <?php echo $username; ?><br>
			<b>Status de l'annonce :</b> 
			<?php
			if($status == 'FREE')
			{
				?>
				Annonce gratuite
				<?php
			}
			else if($status == 'WAITING_PAID')
			{
				?>
				En attente de réglement
				<?php
			}
			else if($status == 'WAIT_CHEQUE')
			{
				?>
				En attente de réglement par chèque
				<?php
			}
			else if($status == 'WAIT_VIREMENT')
			{
				?>
				En attente de réglement par Virement bancaire
				<?php
			}
			else if($status == 'WAIT_MOBICASH')
			{
				?>
				En attente de validation de paiement Mobicash
				<?php
			}
			else if($status == 'PAID_MOBICASH')
			{
				?>
				Payer avec Mobicash
				<?php
			}
			else if($status == 'PAID_PAYPAL')
			{
				?>
				Payer avec Paypal<br>
				<?php
				
				$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				?>
				<b>Montant :</b> <?php echo number_format($req['montant'],2)." €"; ?><br>
				<b>Date du paiement :</b> <?php echo $req['date_paiement']; ?><br>
				<b>Identifiant de transaction :</b> <?php echo $req['id_transaction']; ?><br>
				<?php
			}
			else if($status == 'PAID_STRIPE')
			{
				?>
				Payer avec Stripe<br>
				<?php
				
				$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				?>
				<b>Montant :</b> <?php echo number_format($req['montant'],2)." ".$sigle; ?><br>
				<b>Date du paiement :</b> <?php echo $req['date_paiement']; ?><br>
				<b>Identifiant de transaction :</b> <?php echo $req['id_transaction']; ?><br>
				<?php
			}
			else if($status == 'PAID_PAYDUNYA')
			{
				?>
				Payer avec Paydunya<br>
				<?php
				
				$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				?>
				<b>Montant :</b> <?php echo number_format($req['montant'],2)." ".$sigle; ?><br>
				<b>Date du paiement :</b> <?php echo $req['date_paiement']; ?><br>
				<?php
			}
			else if($status == 'PAID_CHECK')
			{
				?>
				Payer par chèque<br>
				<?php
				
				$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				?>
				<b>Montant :</b> <?php echo number_format($req['montant'],2)." ".$sigle; ?><br>
				<b>Date du paiement :</b> <?php echo $req['date_paiement']; ?><br>
				<?php
			}
			else if($status == 'PAID_VIREMENT')
			{
				?>
				Payer par virement bancaire<br>
				<?php
				
				$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				?>
				<b>Montant :</b> <?php echo number_format($req['montant'],2)." ".$sigle; ?><br>
				<b>Date du paiement :</b> <?php echo $req['date_paiement']; ?><br>
				<?php
			}
			
			if($quinzejour == 'yes' || $unmois)
			{
				?>
				<br><b>Remonter de l'annonce prévu le :</b><br><br>
				<?php
				
				$SQL = "SELECT COUNT(*) FROM pas_remonter WHERE md5 = '$md5'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				if($req[0] == 0)
				{
					echo "<i>Aucune remonter prévu pour cette annonce.</i><br>";
				}
				else
				{				
					$SQL = "SELECT * FROM pas_remonter WHERE md5 = '$md5'";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						echo "Remonter de l'annonce prévu le <i>".$req['date_remonter']."</i><br>";
					}
				}
			}
			?>
			<br><br>
			<form>
				<label><b>Type d'offre :</b></label>
				<select name="offre" class="inputbox">
				<?php
				if($offre_demande == 'offre')
				{
					?>
					<option value="offre" selected>Offre</option>
					<?php
				}
				else
				{
					?>
					<option value="offre">Offre</option>
					<?php
				}
				
				if($offre_demande == 'demande')
				{
					?>
					<option value="demande" selected>Demande</option>
					<?php
				}
				else
				{
					?>
					<option value="demande">Demande</option>
					<?php
				}
				?>
				</select>
				<label><b>Titre de l'annonce :</b></label>
				<input type="text" class="inputbox" name="titre" value="<?php echo $titre; ?>">
				<label><b>Description :</b></label>
				<textarea class="textareabox" name="texte" placeholder="Pas de texte"><?php echo $texte; ?></textarea>
				<label><b>Code postal :</b></label>
				<input type="text" name="codepostal" placeholder="Pas de code postal" value="<?php echo $codepostal; ?>" class="inputbox">
				<label><b>Ville :</b></label>
				<input type="text" name="ville" placeholder="Pas de ville" value="<?php echo $ville; ?>" class="inputbox">
				<label><b>Région :</b></label>
				<select name="region" class="inputbox">
					<?php
					
					$SQL = "SELECT * FROM pas_region";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						if($idregion == $req['id'])
						{
						?>
						<option value="<?php echo $req['id']; ?>" selected><?php echo str_replace("'","&apos;",$req['titre']); ?></option>
						<?php	
						}
						else
						{
						?>
						<option value="<?php echo $req['id']; ?>"><?php echo str_replace("'","&apos;",$req['titre']); ?></option>
						<?php
						}
					}
					
					?>
				</select>
				<label><b>Téléphone :</b></label>
				<input type="text" name="telephone" placeholder="Pas de numéro" value="<?php echo $telephone; ?>" class="inputbox">
				<label><b>Prix :</b></label>
				<input type="text" name="prix" placeholder="Pas de prix" value="<?php echo $prix; ?>" class="inputbox">
				<label><b>Catégorie :</b></label>
				<select name="categorie" class="inputbox">
				<?php
				
				echo $class_map->getAllCategorieOptionSelected($idcategorie);
				
				?>
				</select>
				<label><b>Photos :</b></label><br><br>
				<style>
				.photo
				{
					float:left;
					margin-right:5px;
				}
				</style>
				<div id="previewLoadingLoader">
					<div class="addMedia">
						Ajouter ou glisser une image
						<input type="file" name="image" id="imageloader" onchange="previewChange();">
					</div>
					<?php
					
					$x = 0;
		
					$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
					$u = $pdo->query($SQL);
					while($uu = $u->fetch())
					{
						$i = $uu['image'];
						$i = explode(".",$i);
						$img = $i[0]."-thumb.".$i[1];
						?>
						<div class="newImage" id="image-<?php echo $x; ?>">
							<div class="deleteimage" onclick="deleteImage('<?php echo $x; ?>','<?php echo $img; ?>');">X</div>
							<img src="<?php echo $url_script; ?>/images/annonce/<?php echo $img; ?>" style="width:100%;height:auto;">
						</div>
						<?php
						$x++;
					}
					
					?>
				</div>
				<script>
				var actual_media = '';
				var actual_media_type = '';
				var idimage = <?php echo $x; ?>;
				var md5 = '<?php echo $md5; ?>';
				
				function finalizeUpload(id)
				{
					$('#addButton').css('display','none');
					$.ajax({
					method: "POST",
					url: "<?php echo $url_script; ?>/admin/finalUpload.php",
					data: {	 
								media: actual_media,
								media_type: actual_media_type,
								idimage: id,
								md5: md5
						  }
					})
					.done(function( msg ) 
					{
						var myarray = msg.split("#");
						$('#image-'+myarray[0]).html('<div class="deleteimage" onclick="deleteImage(\''+id+'\',\''+myarray[2]+'\');">X</div><img src="'+myarray[1]+'" style="width:100%;height:auto;">');
						$('#addButton').css('display','block');
					});
				}
				
				function deleteImage(id,file)
				{
					<?php
					if(!$demo)
					{
					?>
					$.post("deleteImage.php?md5="+file, function( data ) 
					{
						$('#image-'+id).css('display','none');
					});
					<?php
					}
					else
					{
						?>
						alert('Cette fonctionnalité n\'est pas disponible dans la version de démonstration');
						<?php
					}
					?>
				}
				
				function previewChange()
				{
					var oFReader = new FileReader();
					var typeFile = document.getElementById("imageloader").files[0].type;
					
					console.log('Type : '+typeFile);
					
					if(typeFile == 'image/jpeg')
					{
						oFReader.readAsDataURL(document.getElementById("imageloader").files[0]);
				
						oFReader.onload = function (oFREvent) 
						{
							$('#previewLoadingLoader').append('<div class="newImage" id="image-'+idimage+'"><div class="loading"><img src="<?php echo $url_script; ?>/admin/images/default.gif"></div><img src="'+oFREvent.target.result+'" style="width:100%;height:auto;"></div>');
							actual_media = oFREvent.target.result;
							actual_media_type = typeFile;
							finalizeUpload(idimage);
							idimage = idimage + 1;
						};
					}
					if(typeFile == 'image/png')
					{
						oFReader.readAsDataURL(document.getElementById("imageloader").files[0]);
				
						oFReader.onload = function (oFREvent) 
						{
							$('#previewLoadingLoader').append('<div class="newImage" id="image-'+idimage+'"><div class="loading"><img src="<?php echo $url_script; ?>/admin/images/default.gif"></div><img src="'+oFREvent.target.result+'" style="width:100%;height:auto;"></div>');
							actual_media = oFREvent.target.result;
							actual_media_type = typeFile;
							finalizeUpload(idimage);
							idimage = idimage + 1;
						};
					}
				}
				</script>
				<label><b>Options :</b></label><br><br>
				<?php
				if($urgente == 2)
				{
					?>
					<input type="checkbox" name="urgente" value="2" checked> Urgente<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="urgente" value="2"> Urgente<br>
					<?php
				}
				
				if($quinzejour == 'yes')
				{
					?>
					<input type="checkbox" name="quinzejour" value="yes" checked> Remonter 15 Jours<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="quinzejour" value="yes"> Remonter 15 Jours<br>
					<?php
				}
				
				if($unmois == 'yes')
				{
					?>
					<input type="checkbox" name="unmois" value="yes" checked> Remonter 30 Jours<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="unmois" value="yes"> Remonter 30 Jours<br>
					<?php
				}
				?>
				<br><label><b>Status :</b></label><br>
				<select name="status" class="inputbox">
				<?php
				if($status == 'WAITING_PAID')
				{
					?>
					<option value="WAITING_PAID" selected>En attente de réglement</option>
					<?php
				}
				else
				{
					?>
					<option value="WAITING_PAID">En attente de réglement</option>
					<?php
				}
				if($status == 'WAIT_CHEQUE')
				{
					?>
					<option value="WAIT_CHEQUE" selected>En attente de réglement par chèque</option>
					<?php
				}
				else
				{
					?>
					<option value="WAIT_CHEQUE">En attente de réglement par chèque</option>
					<?php
				}
				if($status == 'WAIT_VIREMENT')
				{
					?>
					<option value="WAIT_VIREMENT" selected>En attente de réglement par Virement bancaire</option>
					<?php
				}
				else
				{
					?>
					<option value="WAIT_VIREMENT">En attente de réglement par Virement bancaire</option>
					<?php
				}
				if($status == 'WAIT_MOBICASH')
				{
					?>
					<option value="WAIT_MOBICASH" selected>En attente de validation de paiement Mobicash</option>
					<?php
				}
				else
				{
					?>
					<option value="WAIT_MOBICASH">En attente de validation de paiement Mobicash</option>
					<?php
				}
				if($status == 'FREE')
				{
					?>
					<option value="FREE" selected>Gratuite</option>
					<?php
				}
				else
				{
					?>
					<option value="FREE">Gratuite</option>
					<?php
				}
				if($status == 'PAID_STRIPE')
				{
					?>
					<option value="PAID_STRIPE" selected>Payer par Stripe</option>
					<?php
				}
				else
				{
					?>
					<option value="PAID_STRIPE">Payer par Stripe</option>
					<?php
				}
				if($status == 'PAID_PAYPAL')
				{
					?>
					<option value="PAID_PAYPAL" selected>Payer par Paypal</option>
					<?php
				}
				else
				{
					?>
					<option value="PAID_PAYPAL">Payer par Paypal</option>
					<?php
				}
				if($status == 'PAID_PAYDUNYA')
				{
					?>
					<option value="PAID_PAYDUNYA" selected>Payer par Paydunya</option>
					<?php
				}
				else
				{
					?>
					<option value="PAID_PAYDUNYA">Payer par Paydunya</option>
					<?php
				}
				if($status == 'PAID_CHECK')
				{
					?>
					<option value="PAID_CHECK" selected>Payer par Chèque</option>
					<?php
				}
				else
				{
					?>
					<option value="PAID_CHECK">Payer par Chèque</option>
					<?php
				}
				if($status == 'PAID_VIREMENT')
				{
					?>
					<option value="PAID_VIREMENT" selected>Payer par Virement Bancaire</option>
					<?php
				}
				else
				{
					?>
					<option value="PAID_VIREMENT">Payer par Virement Bancaire</option>
					<?php
				}
				if($status == 'PAID_MOBICASH')
				{
					?>
					<option value="PAID_MOBICASH" selected>Payer par Mobicash</option>
					<?php
				}
				else
				{
					?>
					<option value="PAID_MOBICASH">Payer par Mobicash</option>
					<?php
				}
				?>
				</select><br>
				<label><b>Etat :</b></label>
				<select name="valider" class="inputbox">
				<?php
				if($valider == 'yes')
				{
					?>
					<option value="yes" selected>Valider (visible sur le site internet)</option>
					<?php
				}
				else
				{
					?>
					<option value="yes">Valider (visible sur le site internet)</option>
					<?php
				}
				if($valider == 'no')
				{
					?>
					<option value="no" selected>Non valider</option>
					<?php
				}
				else
				{
					?>
					<option value="no">Non valider</option>
					<?php
				}
				if($valider == 'expired')
				{
					?>
					<option value="expired" selected>Expirer</option>
					<?php
				}
				else
				{
					?>
					<option value="expired">Expirer</option>
					<?php
				}
				?>
				</select>
				<input type="hidden" name="md5" value="<?php echo $md5; ?>">
				<input type="hidden" name="action" value="6">
				<input type="submit" value="Modifier" class="btn blue">
			</form>
			<?php
		}
		else
		{
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count = $req[0];
			
			?>
			<H1><div class="round-count"><?php echo $count; ?></div> Gestion des annonces</H1>
			<style>
			.englobehelp
			{
				display:initial;
			}
			
			.help-video
			{
				float: right;
				margin-top: -65px;
				font-size: 25px;
				text-align: center;
			}
			
			.littletext
			{
				font-size: 12px;
				font-weight: bold;
			}
			
			.help-video-container
			{
				padding: 10px;
				background-color: #323131;
				width: 400px;
				border-radius: 5px;
				margin-bottom: 10px;
				float: right;
			}
			
			.tabs-annonce
			{
				float: left;
				border-top-left-radius: 5px;
				border-top-right-radius: 5px;
				background-color: #96d2ff;
				font-weight: bold;
				font-size: 12px;
				padding-top: 10px;
				padding-bottom: 10px;
				padding-left: 20px;
				padding-right: 20px;
				margin-right: 3px;
			}
			
			.tabs-annonce:hover
			{
				background-color:#82b6dd;
			}
			
			.tabs-selected
			{
				background-color:#82b6dd;
			}
			
			.roundsticky
			{
				background-color: #ff9300;
				border-radius: 13px;
				padding-left: 5px;
				padding-right: 6px;
				color: #fff;
			}
			
			th
			{
				background-color:#78A9CE;
			}
			</style>
			<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
			</div>
			<div style="overflow:auto;">
				<div class="help-video-container" id="help-video" style="display:none;">
					<video width="400" height="222" controls="controls">
					  <source src="https://www.shua-creation.com/video/pas_script_gestion_annonces.mp4.filepart" type="video/mp4" />
					</video>
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
			Gérer les annonces de votre site internet. Valider les annonces, supprimer les et bien plus encore.
			</div>
			<?php
			
			if(isset($_REQUEST['search']))
			{
				$search = $_REQUEST['search'];
				$addSearch = "WHERE titre LIKE '%$search%' AND status != 'NOVISIBLE'";
			}
			else
			{
				$addSearch = "WHERE status != 'NOVISIBLE'";
			}
			
			?>
			<div class="search-bar-user">
				<form>
					<input type="text" name="search" placeholder="Titre de l'annonce rechercher ?" value="<?php echo $search; ?>" class="input-search-user">
					<input type="submit" value="Rechercher" class="btn blue">
				</form>
			</div>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'no' AND status = 'FREE'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_a_valider = $req[0];
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'expired'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_expired = $req[0];
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE status = 'WAITING_PAID' OR status = 'WAIT_CHEQUE' OR status = 'WAIT_VIREMENT' OR status = 'WAIT_MOBICASH'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_waiting_paid = $req[0];
			
			?>
			<a href="annonce-a-valider.php">
				<div class="tabs-annonce">
					<span class="roundsticky"><?php echo $count_a_valider; ?></span> Annonce à valider
				</div>
			</a>
			<a href="annonce-expired.php">
				<div class="tabs-annonce">
					<span class="roundsticky"><?php echo $count_expired; ?></span> Annonce expirer
				</div>
			</a>
			<a href="annonce-waiting-paid.php">
				<div class="tabs-annonce">
					<span class="roundsticky"><?php echo $count_waiting_paid; ?></span> Annonce en attente de paiement
				</div>
			</a>
			<a href="annonce.php">
				<div class="tabs-annonce tabs-selected">
					<span class="roundsticky"><?php echo $count; ?></span> Toutes les annonces
				</div>
			</a>
			<table>
				<tr>
					<th>Date de soumission</th>
					<th>Valider</th>
					<th>Type d'Offre</th>
					<th>Titre</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				<?php
				
				if($count == 0)
				{
					?>
					</table>
					<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
					<h1>Aucune annonce pour le moment</h1>
					</div>
					<?php
				}
				else
				{				
					if(isset($_REQUEST['page']))
					{
						$page = $_REQUEST['page'];
					}
					else
					{
						$page = 0;
					}
					
					$nombre_annonce = 20;
					$totalp = $page * $nombre_annonce;
					
					$SQL = "SELECT COUNT(*) FROM pas_annonce $addSearch";
					$reponse = $pdo->query($SQL);
					$req = $reponse->fetch();
					
					$count = $req[0];
					
					$nombre_page = ceil($count / $nombre_annonce);
					
					$SQL = "SELECT * FROM pas_annonce $addSearch ORDER BY date_soumission DESC LIMIT $totalp,$nombre_annonce";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						?>
						<tr>
							<td><?php echo $class_fuseau_horaire->convertTimezone($req['date_soumission'],getConfig("format_date"),getConfig("fuseau")); ?></td>
							<td>
							<?php 
							if($req['valider'] == 'yes')
							{
								?>
								<img src="images/valid-icon.png" width=20 title="Annonce valider">
								<?php
							}
							else if($req['valider'] == 'no')
							{
								?>
								<img src="images/invalid-icon.png" width=20 title="Annonce non valider">
								<?php
							}
							else
							{
								?>
								<img src="images/icon-expirer.png" width=20 title="Annonce expirer">
								<?php
							}
							?></td>
							<td><?php echo $req['offre_demande']; ?></td>
							<td><?php echo substr($req['titre'],0,110); ?></td>
							<td>
							<?php 
							if($req['status'] == 'PAID_PAYPAL')
							{
								echo 'Payer avec Paypal';
							}
							else if($req['status'] == 'WAIT_CHEQUE')
							{
								echo 'En attente de réglement par chèque';
							}
							else if($req['status'] == 'PAID_STRIPE')
							{
								echo 'Payer avec Stripe';
							}
							else if($req['status'] == 'ERROR_PAID_STRIPE')
							{
								echo 'Erreur de paiement Stripe';
							}
							else if($req['status'] == 'ERROR_IPN')
							{
								echo 'Erreur de reception IPN Paypal';
							}
							else if($req['status'] == 'WAITING_PAID')
							{
								echo 'En attente de réglement';
							}
							else if($req['status'] == 'WAIT_VIREMENT')
							{
								echo 'En attente de réglement par Virement bancaire';
							}
							else if($req['status'] == 'WAIT_MOBICASH')
							{
								echo 'En attente confirmation de paiement Emoney';
							}
							else if($req['status'] == 'FREE')
							{
								echo 'Annonce gratuite';
							}
							else if($req['status'] == 'PAID_PAYDUNYA')
							{
								echo 'Payer avec Paydunya';
							}
							else if($req['status'] == 'PAID_CHECK')
							{
								echo 'Payer par chèque';
							}
							else if($req['status'] == 'PAID_VIREMENT')
							{
								echo 'Payer par virement bancaire';
							}
							else if($req['status'] == 'PAID_MOBICASH')
							{
								echo 'Payer par Emoney';
							}
							?>
							</td>
							<td>
								<?php
								if($req['status'] != 'WAIT_MOBICASH' && $req['status'] != 'WAIT_VIREMENT' && $req['status'] != 'WAITING_PAID' && $req['status'] != 'WAIT_CHEQUE')
								{
									if($req['valider'] == 'yes')
									{
									?>
									<a href="annonce.php?id=<?php echo $req['id']; ?>&action=2" class="btn orange">Dévalider</a>
									<?php
									}
									else
									{
									?>
									<a href="annonce.php?id=<?php echo $req['id']; ?>&action=3" class="btn red">Valider</a>
									<?php
									}
								}
								if($req['status'] == 'WAIT_CHEQUE')
								{
									?>
									<a href="annonce.php?action=7&md5=<?php echo $req['md5']; ?>&type=cheque" class="btn green">Paiement reçu</a>
									<?php
								}
								if($req['status'] == 'WAIT_VIREMENT')
								{
									?>
									<a href="annonce.php?action=7&md5=<?php echo $req['md5']; ?>&type=virement" class="btn green">Paiement reçu</a>
									<?php
								}
								if($req['status'] == 'WAIT_MOBICASH')
								{
									$md5 = $req['md5'];
									$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
									$r = $pdo->query($SQL);
									$rr = $r->fetch();
									
									$id_transaction = $rr['id_transaction'];
									?>
									<a href="annonce.php?action=7&md5=<?php echo $req['md5']; ?>&type=emoney" class="btn green">Paiement reçu (SMS <?php echo $id_transaction; ?>)</a>
									<?php
								}
								?>
								<a href="annonce.php?id=<?php echo $req['id']; ?>&action=1" class="btn red">Supprimer</a>
								<a href="annonce.php?md5=<?php echo $req['md5']; ?>&action=5" class="btn blue">Modifier</a>
								<?php
								if($req['status'] != 'WAIT_MOBICASH' && $req['status'] != 'WAIT_VIREMENT' && $req['status'] != 'WAITING_PAID' && $req['status'] != 'WAIT_CHEQUE')
								{
									if($req['valider'] == 'yes')
									{
									?>
									<a href="../showannonce.php?md5=<?php echo $req['md5']; ?>" target="newpage" class="btn blue">Voir l'annonce</a>
									<?php
									}
									else
									{
									?>
									<a href="annonce.php?action=4&md5=<?php echo $req['md5']; ?>" class="btn blue">Controler l'annonce</a>
									<?php
									}
								}
								else
								{
									?>
									<a href="annonce.php?action=4&md5=<?php echo $req['md5']; ?>" class="btn blue">Controler l'annonce</a>
									<?php
								}
								?>
							</td>
						</tr>
						<?php
					}
					?>
					</table>
					<?php
				}
		}
			
		?>
		<style>
		.paging
		{
			border-radius: 5px;
			background-color: #28a2fe;
			color: #ffffff;
			margin-right: 5px;
			text-decoration: none;
			box-sizing: border-box;
			padding: 10px;
		}
		
		.paging-box
		{
			margin-top: 25px;
			text-align: center;
		}
		</style>
		<div class="paging-box">
		<?php
		if($page==0)
		{
			?>
			<a href="javascript:void(0);" class="paging disabled">Précedent</a>
			<?php
		}
		else
		{
			?>
			<a href="annonce.php?page=<?php echo ($page-1); ?>" class="paging">Précedent</a>
			<?php
		}
		?>
		<b>Page n°<?php echo ($page+1); ?> sur <?php echo $nombre_page; ?></b>
		<?php
		if($page >= $nombre_page)
		{
			?>
			<a href="javascript:void(0);" class="paging disabled">Suivant</a>
			<?php
		}
		else
		{
			?>
			<a href="annonce.php?page=<?php echo ($page+1); ?>" class="paging">Suivant</a>
			<?php
		}
		?>
		</div>
	</div>
</body>
</html>