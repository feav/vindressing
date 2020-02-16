<?php

include "main.php";

$errorRecaptcha = NULL;

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$email = AntiInjectionSQL($_REQUEST['email']);
		$pseudo = AntiInjectionSQL($_REQUEST['pseudo']);
		$password = AntiInjectionSQL($_REQUEST['password']);
		$optin = AntiInjectionSQL($_REQUEST['optin']);
		$type_account = AntiInjectionSQL($_REQUEST['type_account']);
		
		$return = $class_user->subscribeUser($email,$pseudo,$password,$optin,$type_account);
		if($return == 0)
		{
			header("Location: subscribe.php?valid=1");
		}
		else if($return == 3)
		{
			header("Location: subscribe.php?valid=3");
		}
		else
		{
			$errorRecaptcha = 1;
		}
	}
}

$class_templateloader->showHead('inscription');
$class_templateloader->openBody();
	
include "header.php";

if(isset($_REQUEST['valid']))
{
	$valid = $_REQUEST['valid'];
	if($valid == 1)
	{
		$errormessage = '<div class="validmsg">'."\n";
		$errormessage .= $message_inscription_particulier_avec_validation_email."\n";
		$errormessage .= '</div>'."\n";
	}
	if($valid == 2)
	{
		$errormessage = '<div class="validmsg">'."\n";
		$errormessage .= $message_inscription_pro."\n";
		$errormessage .= '</div>'."\n";
	}
	if($valid == 3)
	{
		$errormessage = '<div class="validmsg">'."\n";
		$errormessage .= $message_inscription_particulier_sans_validation_email."\n";
		$errormessage .= '</div>'."\n";
	}
}
else
{
	$errormessage = '';
}

if($errorRecaptcha == 1)
{
	$errormessage = '<div class="errormsg">'."\n";
	$errormessage .= getLangue("message_captcha_error",$language);
	$errormessage .= '</div>'."\n";
}

$class_templateloader->loadTemplate("tpl/subscribe.tpl");
$class_templateloader->assign("{errormessage}",$errormessage);
$class_templateloader->assign("{url_script}",$url_script);
$class_templateloader->assign("{recaptcha}",$class_user->getRecaptcha());
$class_templateloader->show();
	
include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>