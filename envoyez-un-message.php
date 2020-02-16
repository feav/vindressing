<?php

include "main.php";

$md5 = AntiInjectionSQL($_REQUEST['md5']);

$error = NULL;
$msg_valid = NULL;

$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: 404.php");
	exit;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$md5 = AntiInjectionSQL($_REQUEST['md5']);
		$nom = AntiInjectionSQL($_REQUEST['nom']);
		$email = AntiInjectionSQL($_REQUEST['email']);
		$msg = $_REQUEST['message'];
		$error = -1;
		
		if($nom == '')
		{
			$error = 1;
		}
		if($email == '')
		{
			$error = 1;
		}
		if($msg == '')
		{
			$error = 1;
		}
		
		if($error == 1)
		{
			//
		}
		else
		{
		
			// On recupere l'email de l'utilisateur de l'annonce
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['iduser'];
			$titre = $req['titre'];
			
			$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$emailuser = $req['email'];
			
			/* Envoie du mail */
			
			$sujet = getConfig("sujet_mail_reponse_annonce");
			$sujet = str_replace("[titre]",utf8_encode($titre),$sujet);
			$sujet = str_replace("[nom]",$nom,$sujet);
			$sujet = str_replace("[email]",$email,$sujet);
			$sujet = utf8_decode($sujet);
			
			$messageHTML = getConfig("email_reponse_annonce_html");
			$messageHTML = str_replace("[titre]",$titre,$messageHTML);
			$messageHTML = str_replace("[email]",$email,$messageHTML);
			$messageHTML = str_replace("[nom]",$nom,$messageHTML);
			$messageHTML = str_replace("[message]",$msg,$messageHTML);
			$messageHTML = str_replace("[saut_ligne]","<br>",$messageHTML);
			$messageHTML = str_replace("[logo]","",$messageHTML);
			
			$class_email->sendMailTemplateWithReply($emailuser,$sujet,$messageHTML,$email);
			
			header("Location: envoyez-un-message.php?md5=".$md5."&valid=1");
			exit;
		}
	}
}

$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$titre = $req['titre'];
$date_soumission = $req['date_soumission'];
$prix = $req['prix'];
$codepostal = $req['codepostal'];
$description = $req['texte'];
$idcategorie = $req['idcategorie'];
$iduser = $req['iduser'];
$nbr_vue = $req['nbr_vue'];
$telephone = $req['telephone'];

// On recupere le nom de la categorie
$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$namecategorie = $req['titre'];

// Recupere le pseudo de l'utilisateur
$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$username = $req['username'];	

// On recupere les images
$arrayImage = NULL;
$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
$reponse = $pdo->query($SQL);
while($req = $reponse->fetch())
{
	$arrayImage[count($arrayImage)] = $req['image'];
}

if(count($arrayImage) == 0)
{
	$bigimg = 'noimage.jpg';
}
else
{
	$bigimg = "annonce/".$arrayImage[0];
}

$template = getConfig("template");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Envoyez un message à l'annonce <?php echo $titre; ?> - PAS Script</title>
	<meta name="description" content="<?php echo strip_tags(str_replace(CHR(13).CHR(10),"",$description)); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo $template; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo $template; ?>/css/responsive.css">
	<link href="css/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<?php

$class_templateloader->openBody();
include "header.php";

$class_templateloader->loadTemplate("tpl/message.tpl");
$class_templateloader->assign("{md5}",$md5);
$class_templateloader->assign("{username}",$username);

if($error == 1)
{
	$msg_valid = '<div class="errormsg">';
	$msg_valid .= $message_error_all_champs;
	$msg_valid .= '</div>';
}

if(isset($_REQUEST['valid']))
{
	$msg_valid = '<div class="validmsg">';
	$msg_valid .= $message_send_successfull;
	$msg_valid .= '</div>';
}

$class_templateloader->assign("{msg_valid}",$msg_valid);	
$class_templateloader->show();

include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
	
?>