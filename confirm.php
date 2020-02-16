<?php

include "main.php";

$class_templateloader->showHead('connexion');
$class_templateloader->openBody();

include "header.php";

$class_templateloader->loadTemplate("tpl/confirm.tpl");

$md5 = AntiInjectionSQL($_REQUEST['md5']);
$SQL = "SELECT COUNT(*) FROM pas_user WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	$msg = '<div class="errormsg">';
	$msg .= $message_confirmation_compte_non_valide;
	$msg .= '</div>';
}
else
{
	$SQL = "UPDATE pas_user SET validate_account = 'yes' WHERE md5 = '$md5'";
	$pdo->query($SQL);
	
	$msg = '<div class="validmsg">';
	$msg .= $message_confirmation_compte_valider;
	$msg .= '</div>';
}

$class_templateloader->assign("{validate_message}",$msg);
$class_templateloader->show();

include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>