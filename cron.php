<?php

/* Crontab du script permettant la mise à jour et remonter des annonces */
/* En fonction de votre hebergement faite executer ce script toute les heures pour un bon fonctionnement */
/* De celui-ci en CRONTAB */

include "config.php";

echo "*** DEBUT DE REMONTER DES ANNONCES ***<br><br>";

$SQL = "SELECT * FROM pas_remonter";
$reponse = $pdo->query($SQL);
while($req = $reponse->fetch())
{
	$md5 = $req['md5'];
	$date_remonter  = $req['date_remonter'];
	$idremonter = $req['id'];
	
	$date_remonter = explode("-",$date_remonter);
	
	/* On verifie si la date est identique */
	if(date('d') == $date_remonter[2])
	{
		/* Jour identique */
		if(date('m') == $date_remonter[1])
		{
			/* Mois identique */
			if(date('Y') == $date_remonter[0])
			{
				/* Date identique on récupere l'annonce en mémoire */
				$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
				echo "[RECUPERATION DE L'ANNONCE] : $md5<br>";
				$r = $pdo->query($SQL);
				$rr = $r->fetch();
				
				$iduser = $rr['iduser'];
				$slug = $rr['slug'];
				$idregion = $rr['idregion'];
				$titre = $rr['titre'];
				$texte = $rr['texte'];
				$offre_demande = $rr['offre_demande'];
				$idcategorie = $rr['idcategorie'];
				$prix = $rr['prix'];
				$codepostal = $rr['codepostal'];
				$valider = $rr['valider'];
				$date_soumission = $rr['date_soumission'];
				$nbr_vue = $rr['nbr_vue'];
				$telephone = $rr['telephone'];
				$ville = $rr['ville'];
				$urgente = $rr['urgente'];
				$quinzejour = $rr['quinzejour'];
				$unmois = $rr['unmois'];
				$status = $rr['status'];
				$pro = $rr['pro'];
				$option_annonce = $rr['option_annonce'];
				
				/* On supprimer l'annonce */
				$SQL = "DELETE FROM pas_annonce WHERE md5 = '$md5'";
				echo "[SUPPRESSION DE L'ANNONCE] : $md5<br>";
				$pdo->query($SQL);
				
				/* On la recréer à l'identique à la date du jour */
				$SQL = "INSERT INTO pas_annonce (iduser,slug,idregion,md5,titre,texte,offre_demande,idcategorie,prix,codepostal,valider,date_soumission,nbr_vue,telephone,ville,urgente,quinzejour,unmois,status,pro,option_annonce) VALUES ('$iduser','$slug',$idregion,'$md5','$titre','$texte','$offre_demande',$idcategorie,'$prix','$codepostal','$valider',NOW(),$nbr_vue,'$telephone','$ville',$urgente,'$quinzejour','$unmois','$status','$pro','$option_annonce')";
				echo "[REINSERTION DE L'ANNONCE A LA DATE DU JOUR] : $md5<br>";
				$pdo->query($SQL);
				
				/* On supprime la tache à remonter */
				$SQL = "DELETE FROM pas_remonter WHERE id = $idremonter";
				echo "[SUPPRESION DE LA TACHE DE REMONTER] : $md5<br>";
				$pdo->query($SQL);
			}
		}
	}
}

$duree = getConfig("duree_validite_annonce");

if($duree != 0)
{
	/* On check la validité des annonces */
	$SQL = "SELECT * FROM pas_annonce WHERE valider = 'yes'";
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$now   = time();
		$date2 = strtotime($req['date_soumission']);
		$date = dateDiff($now, $date2);
		$id = $req['id'];
		$md5 = $req['md5'];
		$titre = $req['titre'];
		$iduser = $req['iduser'];

		$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
		$r = $pdo->query($SQL);
		$rr = $r->fetch();
		
		$email = $rr['email'];
		$nom = $rr['username'];
	 
		if($date['day'] > $duree)
		{
			echo "L'annonce à depasser les $duree jours on la désactive / ".$date['day']." j<br>";
			$SQL = "UPDATE pas_annonce SET valider = 'expired' WHERE id = $id";
			$pdo->query($SQL);
			
			/* On envoie un email à l'utilisateur pour lui signaler que l'annonce à expirer */
			$sujet = getConfig("sujet_fin_validite_email");
			$messageHTML = getConfig("email_html_fin_validite_email");
			$messageTEXTE = getConfig("email_text_fin_validite_email");
			
			$sujet = str_replace("[titre]",$titre,$sujet);
			$sujet = str_replace("[nom]",$nom,$sujet);
			$sujet = str_replace("[email]",$email,$sujet);
			
			$messageHTML = str_replace("[titre]",$titre,$messageHTML);
			$messageHTML = str_replace("[email]",$email,$messageHTML);
			$messageHTML = str_replace("[nom]",$nom,$messageHTML);
			$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
			$messageHTML = str_replace("[logo]",'<img src="'.$url_script.'/images/'.getConfig("logo").'">',$messageHTML);
			$messageHTML = str_replace("[link_renew]",'<a href="'.$url_script.'/addoptionannonce.php?md5='.$md5.'">Renouveller votre annonce</a>',$messageHTML);
			$messageHTML = '<html><body>'.$messageHTML.'</body></html>';
			
			$messageTEXTE = str_replace("[titre]",$titre,$messageTEXTE);
			$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
			$messageTEXTE = str_replace("[nom]",$nom,$messageTEXTE);
			$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
			$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
			$messageTEXTE = str_replace("[link_renew]",$url_script.'/addoptionannonce.php?md5='.$md5,$messageTEXTE);
			
			$boundary = "-----=".md5(rand());
			$passage_ligne = "\r\n";
			
			$expediteurmail = getConfig("nom_expediteur_mail");
			$adresse_expediteur_mail = getConfig("adresse_expediteur_mail");
			$header = "From: \"$expediteurmail\"<$adresse_expediteur_mail>".$passage_ligne;
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
			
			mail($email,$sujet,$message,$header);
		}
		else
		{
			echo "L'annonce est encore valable ($duree jours) / ".$date['day']." j<br>";
		}

	}
}

echo "*** FIN DE REMONTER DES ANNONCES ***";

?>