<?php

include "main.php";

$md5 = AntiInjectionSQL($_REQUEST['md5']);

$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: 404.php");
	exit;
}

$data = NULL;

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$motif = $_REQUEST['motif'];
		$nom = $_REQUEST['nom'];
		$email = $_REQUEST['email'];
		$message = $_REQUEST['message'];
		$md5 = $_REQUEST['md5'];		
		
		$data = $class_user->signalerAnnonce($nom,$email,$message,$motif,$md5);
	}
}

$class_templateloader->showHead('signaler_annonce');
$class_templateloader->openBody();
	
include "header.php";

$errormessage = "";

if(isset($_REQUEST['valid']))
{
	$errormessage = '<div class="validmsg">'."\n";
	$errormessage .= 'Nous avons bien reçu votre demande et nous la traiterons dans les meilleurs délais.'."\n";
	$errormessage .= '</div>'."\n";
}

$class_templateloader->loadTemplate("tpl/signalerannonce.tpl");
$class_templateloader->assign("{errormessage}",$errormessage);
$class_templateloader->assign("{errormotif}",$data['erreurmotif']);
$class_templateloader->assign("{erreurnom}",$data['erreurnom']);
$class_templateloader->assign("{erreurmail}",$data['erreurmail']);
$class_templateloader->assign("{erreurmessage}",$data['erreurmessage']);
$class_templateloader->assign("{nom}",$nom);
$class_templateloader->assign("{message}",$message);
$class_templateloader->assign("{email}",$email);
$class_templateloader->assign("{md5}",$md5);
$class_templateloader->show();

include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
?>