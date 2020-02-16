<?php

include "main.php";

$validmsg = NULL;
$errormail = NULL;
$errormail_msg = NULL;
$pseudoerror = NULL;
$pseudoerror_msg = NULL;
$errorpassword = NULL;
$errorpassword_msg = NULL;
$errorpassword_confirm_msg = NULL;

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	/* Compte normal */
	if($action == 1)
	{
		$email = $_REQUEST['email'];
		$email = AntiInjectionSQL($email);
		$pseudo = $_REQUEST['pseudo'];
		$pseudo = AntiInjectionSQL($pseudo);
		$password = $_REQUEST['password'];
		$password = AntiInjectionSQL($password);
		$password2 = $_REQUEST['password2'];
		$password2 = AntiInjectionSQL($password2);
		
		$error = 0;
		
		if($password != $password2)
		{
			$errorpassword = 1;
			$error = 1;
		}
		
		if($password == '')
		{
			$errorpassword = 2;
			$error = 1;
		}
		
		if($email == '')
		{
			$errormail = 1;
			$error = 1;
		}
		
		if($pseudo == '')
		{
			$pseudoerror = 1;
			$error = 1;
		}
		
		if($email != $_SESSION['email'])
		{
			$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] != 0)
			{
				$errormail = 2;
				$error = 1;
			}
		}
		
		if($error == 0)
		{
			$SQL = "UPDATE pas_user SET username = '$pseudo' WHERE email = '".$_SESSION['email']."'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_user SET password = '$password' WHERE email = '".$_SESSION['email']."'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_user SET email = '$email' WHERE email = '".$_SESSION['email']."'";
			$pdo->query($SQL);
			
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $password;
			$_SESSION['pseudo'] = $pseudo;
			
			header("Location: moncompte.php?valid=1");
			exit;
		}
		
	}
	
	/* Compte professionel */
	if($action == 2)
	{
		$email = $_REQUEST['email'];
		$email = AntiInjectionSQL($email);
		$pseudo = $_REQUEST['pseudo'];
		$pseudo = AntiInjectionSQL($pseudo);
		$password = $_REQUEST['password'];
		$password = AntiInjectionSQL($password);
		$password2 = $_REQUEST['password2'];
		$password2 = AntiInjectionSQL($password2);
		$md5 = $_REQUEST['md5'];
		$md5 = AntiInjectionSQL($md5);
		
		$adresse = $_REQUEST['adresse'];
		$adresse = AntiInjectionSQL($adresse);
		$description = $_REQUEST['description'];
		$description = AntiInjectionSQL($description);
		$site_internet = $_REQUEST['site_internet'];
		$site_internet = AntiInjectionSQL($site_internet);
		
		$categorie = $_REQUEST['categorie'];
		$categorie = AntiInjectionSQL($categorie);
		
		$slogan = $_REQUEST['slogan'];
		$slogan = AntiInjectionSQL($slogan);
		
		$error = 0;
		
		if($password != $password2)
		{
			$errorpassword = 1;
			$error = 1;
		}
		
		if($password == '')
		{
			$errorpassword = 2;
			$error = 1;
		}
		
		if($email == '')
		{
			$errormail = 1;
			$error = 1;
		}
		
		if($pseudo == '')
		{
			$pseudoerror = 1;
			$error = 1;
		}
		
		if($site_internet != '')
		{
			$s = strtolower($site_internet);
			$check1 = stripos($s,"http://");
			$check2 = stripos($s,"https://");
			
			$url_website_valid = false;
			
			if($check1 !== FALSE)
			{
				$url_website_valid = true;
			}
			if($check2 !== FALSE)
			{
				$url_website_valid = true;
			}
			
			if($url_website_valid == false)
			{
				$errorwebsite = 1;
				$error = 1;
			}
		}
		
		if($email != $_SESSION['email'])
		{
			$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] != 0)
			{
				$errormail = 2;
				$error = 1;
			}
		}
		
		if($error == 0)
		{
			if($_FILES['logo']['tmp_name'] != '')
			{
				$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/images/logo/";
				$tmp_file = $_FILES['logo']['tmp_name'];
				if( !is_uploaded_file($tmp_file) )
				{
					exit("Le fichier est introuvable");
				}

				// on vérifie maintenant l'extension
				$type_file = $_FILES['logo']['type'];
				// on copie le fichier dans le dossier de destination
				$name_file = $_FILES['logo']['name'];
				$extension = explode(".",$name_file);
				$extension = $extension[count($extension)-1];
				$extension = strtolower($extension);
				
				$name_file = md5(microtime()).".".$extension;
				
				
				if( !move_uploaded_file($tmp_file, $chemin.$name_file))
				{
					exit("Impossible de copier le fichier dans $chemin$name_file");
				}
				
				/* Fix Faille Upload */
				if(!getimagesize($chemin.$name_file))
				{
					unlink($chemin.$name_file);
					exit("Le fichier n'est pas un fichier image");
				}
				
				$class_imagehelper->generateThumb($chemin.$name_file,110,110);
				$name_file = explode(".",$name_file);
				$name_file = $name_file[0]."-thumb.".$name_file[1];
				
				$SQL = "UPDATE pas_compte_pro SET logo = '$name_file' WHERE md5 = '$md5'";
				$pdo->query($SQL);
			}
			
			$SQL = "UPDATE pas_user SET username = '$pseudo' WHERE email = '".$_SESSION['email']."'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_user SET password = '$password' WHERE email = '".$_SESSION['email']."'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_user SET email = '$email' WHERE email = '".$_SESSION['email']."'";
			$pdo->query($SQL);
			
			$SQL = "UPDATE pas_compte_pro SET categorie = $categorie WHERE md5 = '$md5'";
			$pdo->query($SQL);			
			$SQL = "UPDATE pas_compte_pro SET adresse = '$adresse' WHERE md5 = '$md5'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_compte_pro SET description = '$description' WHERE md5 = '$md5'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_compte_pro SET site_internet = '$site_internet' WHERE md5 = '$md5'";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_compte_pro SET slogan = '$slogan' WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $password;
			$_SESSION['pseudo'] = $pseudo;
			
			header("Location: moncompte.php?valid=1");
			exit;
		}
	}
}

$template = getConfig("template");

$class_templateloader->showHead('mon_compte');
$class_templateloader->openBody();

include "header.php";

if($_SESSION['type_compte'] == 'professionel')
{
	$class_templateloader->loadTemplate("tpl/moncompte_pro.tpl");
	if(isset($_REQUEST['valid']))
	{
		$validmsg = '<div class="validmsg">';
		$validmsg .= 'La modification de votre compte à bien été effectuer.';
		$validmsg .= '</div>';
	}

	$class_templateloader->assign("{validmsg}",$validmsg);
	
	$form = new TemplateLoader();
	
	if($isConnected)
	{
		$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$md5 = $req['md5'];

		$form->loadTemplate("tpl/formulaire_moncompte_pro.tpl");

		$class_templateloader->assign("{form_moncompte_pro}",$form->getData());
		$class_templateloader->assign("{email}",$req['email']);
		$class_templateloader->assign("{pseudo}",$req['username']);
		
		$SQL = "SELECT * FROM pas_compte_pro WHERE md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$class_templateloader->assign("{adresse}",$req['adresse']);
		$class_templateloader->assign("{site_internet}",$req['site_internet']);
		$class_templateloader->assign("{description}",$req['description']);
		$class_templateloader->assign("{md5}",$md5);
		$class_templateloader->assign("{categorie}",$class_map->getAllCategorieOptionSelected($req['categorie']));
		$class_templateloader->assign("{slogan}",$req['slogan']);
		
		if($req['logo'] != '')
		{
			$class_templateloader->assign("{logo}",'<br><br><img src="'.$url_script.'/images/logo/'.$req['logo'].'"><br>');
		}
		else
		{
			$class_templateloader->assign("{logo}",'');
		}
		
		if($errormail == 1)
		{
			$errormail_msg = "<font color=red><b>L'adresse email ne peux pas être vide</b></font>";
		}
		if($errormail == 2)
		{
			$errormail_msg = "<font color=red><b>Cette adresse email est déjà utiliser par un autre compte</b></font>";
		}
		
		$class_templateloader->assign("{errormail}",$errormail_msg);

		if($pseudoerror == 1)
		{
			$pseudoerror_msg = "<font color=red><b>Le pseudo ne peux pas être vide</b></font>";
		}		
		$class_templateloader->assign("{errorpseudo}",$pseudoerror_msg);
		
		if($errorwebsite == 1)
		{
			$errorwebsite_msg = "<font color=red><b>L'URL de votre site internet n'est pas valide il doit contenir HTTP:// ou HTTPS://</b></font><br>";
		}
		$class_templateloader->assign("{errorwebsite}",$errorwebsite_msg);
		
		if($errorpassword == 2)
		{
			$errorpassword_msg = "<font color=red><b>Le mot de passe ne peux pas être vide</b></font><br>";
		}
		
		$class_templateloader->assign("{errorpassword}",$errorpassword_msg);
		
		if($errorpassword == 1)
		{
			$errorpassword_confirm_msg = "<font color=red><b>Le mot de passe est différent</b></font>";
		}
		
		$class_templateloader->assign("{errorconfirm}",$errorpassword_confirm_msg);
	}
	else
	{
		$form->loadTemplate("tpl/blockconnexion.tpl");
		$class_templateloader->assign("{form_moncompte_pro}",$form->getData());
	}
	
	$class_templateloader->show();

	include "footer.php";

	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
}
else
{
	$class_templateloader->loadTemplate("tpl/moncompte.tpl");
	if(isset($_REQUEST['valid']))
	{
		$validmsg = '<div class="validmsg">';
		$validmsg .= 'La modification de votre compte à bien été effectuer.';
		$validmsg .= '</div>';
	}

	$class_templateloader->assign("{validmsg}",$validmsg);

	$form = new TemplateLoader();

	if($isConnected)
	{
		$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();

		$form->loadTemplate("tpl/formulaire_moncompte.tpl");

		$class_templateloader->assign("{form_moncompte}",$form->getData());
		$class_templateloader->assign("{email}",$req['email']);
		$class_templateloader->assign("{pseudo}",$req['username']);
		
		if($errormail == 1)
		{
			$errormail_msg = "<font color=red><b>L'adresse email ne peux pas être vide</b></font>";
		}
		if($errormail == 2)
		{
			$errormail_msg = "<font color=red><b>Cette adresse email est déjà utiliser par un autre compte</b></font>";
		}
		
		$class_templateloader->assign("{errormail}",$errormail_msg);

		if($pseudoerror == 1)
		{
			$pseudoerror_msg = "<font color=red><b>Le pseudo ne peux pas être vide</b></font>";
		}
		
		$class_templateloader->assign("{errorpseudo}",$pseudoerror_msg);
		
		if($errorpassword == 2)
		{
			$errorpassword_msg = "<font color=red><b>Le mot de passe ne peux pas être vide</b></font><br>";
		}
		
		$class_templateloader->assign("{errorpassword}",$errorpassword_msg);
		
		if($errorpassword == 1)
		{
			$errorpassword_confirm_msg = "<font color=red><b>Le mot de passe est différent</b></font>";
		}
		
		$class_templateloader->assign("{errorconfirm}",$errorpassword_confirm_msg);
	}
	else
	{
		$form->loadTemplate("tpl/blockconnexion.tpl");
		$class_templateloader->assign("{form_moncompte}",$form->getData());
	}

	$class_templateloader->show();

	include "footer.php";

	$class_templateloader->closeBody();
	$class_templateloader->closeHTML();
}
	
?>