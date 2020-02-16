<?php

/* Classe email Shua-Creation.com 2018 */

class Email
{
	function __construct()
	{
		global $pdo;
		
		$SQL = "SELECT COUNT(*) FROM pas_configuration WHERE identifiant = 'mail_template'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		if($req[0] == 0)
		{
			$SQL = "INSERT INTO pas_configuration (identifiant,code) VALUES ('mail_template','classic')";
			$pdo->query($SQL);
		}
	}
	
	/* Fonction d'envoi d'email avec le Template préselectionner dans la base et le logo */
	function sendMailTemplate($to,$subject,$message)
	{
		global $pdo;
		global $url_script;
		
		/* On va chercher le template de mail */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$template = $req['code'];
		
		/* On va chercher le logo */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'logo'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$logo = $req['code'];
		$logo = '<img src="'.$url_script.'/images/'.$logo.'">';
		
		/* Nom de l'expediteur */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'nom_expediteur_mail'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$emailsenderText = $req['code'];
		
		/* Email de l'expediteur */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'adresse_expediteur_mail'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$emailsender = $req['code'];
		
		$data = file_get_contents($url_script."/mail/$template.tpl");
		$data = str_replace("{logo}",$logo,$data);
		$data = str_replace("{message}",$message,$data);
		$data = str_replace("{website}",$url_script,$data);
		$headers = 'From: "'.$emailsenderText.'"<'.$emailsender.'>'."\n";
		$headers .= 'Content-Type: text/html; charset="UTF-8"';
		mail($to,$subject,$data,$headers);
	}
	
	/* Fonction d'envoi d'email avec le Template préselectionner dans la base et le logo et un email de reponse */
	function sendMailTemplateWithReply($to,$subject,$message,$reply)
	{
		global $pdo;
		global $url_script;
		
		/* On va chercher le template de mail */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$template = $req['code'];
		
		/* On va chercher le logo */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'logo'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$logo = $req['code'];
		$logo = '<img src="'.$url_script.'/images/'.$logo.'">';
		
		/* Nom de l'expediteur */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'nom_expediteur_mail'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$emailsenderText = $req['code'];
		
		/* Email de l'expediteur */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'adresse_expediteur_mail'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$emailsender = $req['code'];
		
		$data = file_get_contents($url_script."/mail/$template.tpl");
		$data = str_replace("{logo}",$logo,$data);
		$data = str_replace("{message}",$message,$data);
		$data = str_replace("{website}",$url_script,$data);
		$headers = 'From: "'.$emailsenderText.'"<'.$emailsender.'>'."\n";
		$headers .= 'Content-Type: text/html; charset="UTF-8"'."\n";
		$headers .= 'Reply-To: '.$reply;
		mail($to,$subject,$data,$headers);
	}
	
	function sendMail($sujet,$messageHTML,$messageTEXTE,$expediteurmail,$adresse_expediteur_mail,$emailuser)
	{
		global $pdo;
		global $url_script;
		
		/* On va chercher le template de mail */
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$mail_template = $req['code'];
		
		$data = file_get_contents("mail/".$mail_template."/".$mail_template.".tpl");
		$data = str_replace("{message}",$messageHTML);
		$data = str_replace("{website}",$url_script);
	
		$boundary = "-----=".md5(rand());
		$passage_ligne = "\r\n";
		
		$header = "From: \"$expediteurmail\"<$adresse_expediteur_mail>".$passage_ligne;
		//$header.= "Reply-to: \"$nom\" <$emailuser>".$passage_ligne;
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
	}
}

?>