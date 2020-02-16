<?php

/* Class user Shua-Creation 2018 */

class User
{
	var $isConnected = false;
	
	function __construct()
	{
		global $pdo;
		
		if(isset($_SESSION['email']))
		{
			if(isset($_SESSION['password']))
			{
				$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();

				if($req[0] == 0)
				{
					$this->isConnected = false;
				}
				else
				{
					$this->isConnected = true;
				}
			}
		}
	}
	
	function getLangue($identifiant,$language)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_langue WHERE identifiant = '$identifiant' AND language = '$language'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['texte'];
	}
	
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	function getRecaptcha()
	{
		$data = "";
		
		if($this->getConfig("recaptcha_active") == 'yes')
		{
			$data = '<br>'."\n";
			$data .= "<script src='https://www.google.com/recaptcha/api.js?hl=fr'></script>"."\n";
			$data .= '<div class="g-recaptcha" data-sitekey="'.$this->getConfig("site_key_recaptcha_google").'"></div>'."\n";
		}
		
		return $data;		
	}
	
	function signalerAnnonce($nom,$email,$message,$motif,$md5)
	{
		global $pdo;
		
		$erreur = 0;
		
		if($motif == '')
		{
			$erreur = 1;
			$data['erreurmotif'] = "<font color=red><b>Vous devez indiquer un motif</b></font><br>";
		}
		
		if($nom == '')
		{
			$erreur = 1;
			$data['erreurnom'] = "<br><font color=red><b>Vous devez indiquez votre nom</b></font><br>";
		}
		
		if($email == '')
		{
			$erreur = 1;
			$data['erreuremail'] = "<br><font color=red><b>Vous devez indiquez une adresse email</b></font><br>";
		}
		
		if($message == '')
		{
			$erreur = 1;
			$data['erreurmessage'] = "<br><font color=red><b>Vous devez indiquez un message pour votre demande</b></font><br>";
		}
		
		/* Check si l'email est valide */
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
			$erreur = 1;
			$data['erreuremail'] = "<br><font color=red><b>Vous devez indiquez une adresse email valide</b></font><br>";
		}
		
		$nom = str_replace("'","''",$nom);
		$email = str_replace("'","''",$email);
		$message = str_replace("'","''",$message);
		
		if($erreur == 0)
		{
			$SQL = "INSERT INTO pas_signaler (nom,email,message,motif,date_post,annonce_md5) VALUES ('$nom','$email','$message','$motif',NOW(),'$md5')";
			$pdo->query($SQL);
			
			header("Location: signaler-l-annonce.php?md5=".$md5."&valid=1");
			exit;
		}
		else
		{
			return $data;
		}
	}
	
	/* Inscription d'un nouvelle utilisateur */
	function subscribeUser($email,$pseudo,$password,$optin,$type_account)
	{
		global $pdo;
		global $url_script;
		
		$md5 = md5(microtime());
		
		if($this->getConfig("recaptcha_active") == 'yes')
		{
			if(isset($_REQUEST['g-recaptcha-response']))
			{
				$secret_key = $this->getConfig("secret_key_recaptcha_google");
				$ip = $_SERVER['REMOTE_ADDR'];
				$captcha = $_REQUEST['g-recaptcha-response'];
				$rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha&remoteip=$ip");
				$arr = json_decode($rsp,TRUE);
				if($arr['success'])
				{
					// Le recaptcha est bien passé
					if($optin)
					{
						$optin = 'oui';
					}
					else
					{
						$optin = 'non';
					}
					
					if($type_account == 'professionel')
					{
						header("Location: $url_script/paid-professionel.php?email=$email&pseudo=$pseudo&password=$password&optin=$optin&type_account=$type_account");
						exit;
					}
					
					// Insertion in database
					if($this->getConfig("account_activation") == 'yes')
					{
						$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','no','no','$type_account')";
						$pdo->query($SQL);
						
						// On envoie un email pour confirmer
						$sujet = $this->getConfig("sujet_mail_inscription");
						//$sujet = 'Confirmer votre inscription au site '.$_REQUEST['HTTP_HOST'];
						$messageHTML = $this->getConfig("email_inscription_html");
						$messageTEXTE = $this->getConfig("email_inscription_text");
						
						$messageHTML = str_replace("[pseudo]",$pseudo,$messageHTML);
						$messageHTML = str_replace("[email]",$email,$messageHTML);
						$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
						$messageHTML = str_replace("[logo]",'',$messageHTML);
						$messageHTML = str_replace("[lienconfirmation]",'<a href="'.$url_script.'/confirm.php?md5='.$md5.'">Confirmer votre adresse email</a>',$messageHTML);		
						
						/* On va chercher le template de mail */
						$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
						$reponse = $pdo->query($SQL);
						$req = $reponse->fetch();
						
						$mail_template = $req['code'];
						
						$data = file_get_contents("mail/".$mail_template.".tpl");
						$data = str_replace("{logo}",'<img src="'.$url_script.'/images/'.$this->getConfig("logo").'" alt="Petites annonces gratuites en France">',$data);
						$data = str_replace("{message}",$messageHTML,$data);
						$data = str_replace("{website}",$url_script,$data);
						$messageHTML = $data;
						
						$messageTEXTE = str_replace("[pseudo]",$pseudo,$messageTEXTE);
						$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
						$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
						$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
						$messageTEXTE = str_replace("[lienconfirmation]",$url_script.'/confirm.php?md5='.$md5,$messageTEXTE);
						
						$boundary = "-----=".md5(rand());
						$passage_ligne = "\r\n";
						
						$expediteurmail = $this->getConfig("nom_expediteur_mail");
						$adresse_expediteur_mail = $this->getConfig("adresse_expediteur_mail");
						$reply_expediteur_mail = $this->getConfig("reply_expediteur_mail");
						
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
						
						return 0;
					}
					else
					{
						$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','yes','no','$type_account')";
						$pdo->query($SQL);
						
						return 3;
					}
				}
				else
				{
					/* Erreur captcha*/
					return 1;
				}
			}
			else
			{
				/* Erreur captcha*/
				return 1;
			}
		}
		else
		{		
			if($optin)
			{
				$optin = 'oui';
			}
			else
			{
				$optin = 'non';
			}
			
			if($type_account == 'professionel')
			{
				header("Location: $url_script/paid-professionel.php?email=$email&pseudo=$pseudo&password=$password&optin=$optin&type_account=$type_account");
				exit;
			}
			
			if($this->getConfig("account_activation") == 'yes')
			{
				// Insertion in database
				$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','no','no','$type_account')";
				$pdo->query($SQL);
				
				// On envoie un email pour confirmer
				$sujet = $this->getConfig("sujet_mail_inscription");
				//$sujet = 'Confirmer votre inscription au site '.$_REQUEST['HTTP_HOST'];
				$messageHTML = $this->getConfig("email_inscription_html");
				$messageTEXTE = $this->getConfig("email_inscription_text");
				
				$messageHTML = str_replace("[pseudo]",$pseudo,$messageHTML);
				$messageHTML = str_replace("[email]",$email,$messageHTML);
				$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
				$messageHTML = str_replace("[logo]",'',$messageHTML);
				$messageHTML = str_replace("[lienconfirmation]",'<a href="'.$url_script.'/confirm.php?md5='.$md5.'">Confirmer votre adresse email</a>',$messageHTML);		
				
				/* On va chercher le template de mail */
				$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'mail_template'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				$mail_template = $req['code'];
				
				$data = file_get_contents("mail/".$mail_template.".tpl");
				$data = str_replace("{logo}",'<img src="'.$url_script.'/images/'.$this->getConfig("logo").'" alt="Petites annonces gratuites en France">',$data);
				$data = str_replace("{message}",$messageHTML,$data);
				$data = str_replace("{website}",$url_script,$data);
				$messageHTML = $data;
				
				$messageTEXTE = str_replace("[pseudo]",$pseudo,$messageTEXTE);
				$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
				$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
				$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
				$messageTEXTE = str_replace("[lienconfirmation]",$url_script.'/confirm.php?md5='.$md5,$messageTEXTE);
				
				$boundary = "-----=".md5(rand());
				$passage_ligne = "\r\n";
				
				$expediteurmail = $this->getConfig("nom_expediteur_mail");
				$adresse_expediteur_mail = $this->getConfig("adresse_expediteur_mail");
				$reply_expediteur_mail = $this->getConfig("reply_expediteur_mail");
				
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
				
				// On redirige
				return 0;
			}
			else
			{
				// Insertion in database
				$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$pseudo','$password',NOW(),'$optin','$md5','yes','no','$type_account')";
				$pdo->query($SQL);
				
				return 3;
			}
		}
	}
	
	/* Methode permettant l'envoie d'email pour la réinitialisation de compte */
	/* Renvoie : 1 (Si une erreur est produite l'email n'existe pas dans la base) */
	/* Renvoie : 2 (Si l'operation c'est dérouler avec succes */
	function sendLostMessage($email)
	{
		global $pdo;
		global $url_script;
		
		$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		if($req[0] == 0)
		{
			return 1;
		}
		else
		{
			/* On envoie un email du coup */
			// On envoie un email pour confirmer
			$sujet = $this->getConfig("sujet_mail_oubliee");
			$messageHTML = $this->getConfig("email_mail_oubliee_html");
			$messageTEXTE = $this->getConfig("email_mail_oubliee_text");
			
			$SQL = "SELECT * FROM pas_user WHERE email = '$email'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$pseudo = $req['username'];
			$md5 = $req['md5'];
			
			$messageHTML = str_replace("[pseudo]",$pseudo,$messageHTML);
			$messageHTML = str_replace("[email]",$email,$messageHTML);
			$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
			$messageHTML = str_replace("[logo]",'',$messageHTML);
			$messageHTML = str_replace("[link_reinit]",'<a href="'.$url_script.'/reinit.php?md5='.$md5.'">Réinitialiser votre mot de passe</a>',$messageHTML);		
			$messageHTML = '<html><body>'.$messageHTML.'</body></html>';
			
			/* On va chercher le template de mail */
			$mail_template = $this->getConfig("mail_template");
			
			$data = file_get_contents("mail/".$mail_template.".tpl");
			$data = str_replace("{logo}",'<img src="'.$url_script.'/images/'.$this->getConfig("logo").'" alt="Petites annonces gratuites en France">',$data);
			$data = str_replace("{message}",$messageHTML,$data);
			$data = str_replace("{website}",$url_script,$data);
			
			$messageHTML = $data;
			
			$messageTEXTE = str_replace("[pseudo]",$pseudo,$messageTEXTE);
			$messageTEXTE = str_replace("[email]",$email,$messageTEXTE);
			$messageTEXTE = str_replace("[saut_ligne]","",$messageTEXTE);
			$messageTEXTE = str_replace("[logo]","",$messageTEXTE);
			$messageTEXTE = str_replace("[link_reinit]",$url_script.'/reinit.php?md5='.$md5,$messageTEXTE);
			
			$boundary = "-----=".md5(rand());
			$passage_ligne = "\r\n";
			
			$expediteurmail = $this->getConfig("nom_expediteur_mail");
			$adresse_expediteur_mail = $this->getConfig("adresse_expediteur_mail");
			$reply_expediteur_mail = $this->getConfig("reply_expediteur_mail");
			
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
			
			return 2;
		}
	}

	function getMenuUser()
	{
		global $pdo;
		global $url_script;
		global $language;
		
		$data = "";
		
		if($this->isConnected)
		{
			$url_rewriting = $this->getConfig("url_rewriting");
			if($url_rewriting == 'yes')
			{
				$data .= '<a href="'.$url_script.'/mon-compte.html" class="btnConnect">'.$this->getLangue("button_moncompte",$language).'</a>'."\n";
				$data .= '<a href="'.$url_script.'/se-deconnecter.html" class="btnConnect">'.$this->getLangue("button_deconnexion",$language).'</a>'."\n";
			}
			else
			{
				$data .= '<a href="'.$url_script.'/moncompte.php" class="btnConnect">'.$this->getLangue("button_moncompte",$language).'</a>'."\n";
				$data .= '<a href="'.$url_script.'/logout.php" class="btnConnect">'.$this->getLangue("button_deconnexion",$language).'</a>'."\n";
			}
		}
		else
		{
			$url_rewriting = getConfig("url_rewriting");
			if($url_rewriting == 'yes')
			{
				$data .= '<a href="'.$url_script.'/connexion.html" class="btnConnect user-icon">&nbsp;&nbsp;&nbsp;&nbsp; '.$this->getLangue("button_connexion",$language).'</a>'."\n";
				$data .= '<a href="'.$url_script.'/inscription.html" class="btnConnect subscribe-icon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$this->getLangue("button_subscribe",$language).'</a>'."\n";
			}
			else
			{
				$data .= '<a href="'.$url_script.'/login.php" class="btnConnect user-icon">&nbsp;&nbsp;&nbsp;&nbsp; '.$this->getLangue("button_connexion",$language).'</a>'."\n";
				$data .= '<a href="'.$url_script.'/subscribe.php" class="btnConnect subscribe-icon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$this->getLangue("button_subscribe",$language).'</a>'."\n";
			}
		}
		
		return $data;
	}
}

?>