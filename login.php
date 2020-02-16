<?php

include "main.php";

$class_templateloader->showHead('connexion');
$class_templateloader->openBody();

include "header.php";
	
if(isset($_REQUEST['error']))
{
	$error = $_REQUEST['error'];
	if($error == 1)
	{
		$errormessage = '<div class="errormsg">'."\n";
		$errormessage .= getLangue("message_error_connection",$language);
		$errormessage .= '</div>'."\n";
	}
	else if($error == 2)
	{
		$errormessage = '<div class="errormsg">'."\n";
		$errormessage .= getLangue("message_error_validate_account",$language);
		$errormessage .= '</div>'."\n";
	}
	else if($error == 3)
	{
		$errormessage = '<div class="errormsg">'."\n";
		$errormessage .= getLangue("message_error_ban_account",$language);
		$errormessage .= '</div>';
	}
	else
	{
		$errormessage = "";
	}
}
else
{
	$errormessage = "";
}

$class_templateloader->loadTemplate("tpl/login.tpl");

$social = "";

if(getConfig("google_connect_activate") == 'yes' || getConfig("facebook_connect_activate") == 'yes')
{
	/*$social = '<div class="englobe-line-separator">'."\n";
	$social .= '<div class="line-left">'."\n";
	$social .= '</div>'."\n";
	$social .= '<div class="or-connect">'."\n";
	$social .= 'ou'."\n";
	$social .= '</div>'."\n";
	$social .= '<div class="line-left">'."\n";
	$social .= '</div>'."\n";
	$social .= '</div>'."\n";*/
	$social .= '<div class="englobe-line-separator ctCenter">'."\n";
}

if(getConfig("google_connect_activate") == 'yes')
{
	$social .= '<button class="btnConfirm" onclick="location.href=\''.$class_google_connect->getURL().'\'"><img src="'.$url_script.'/images/google-sign-in-icon.png"> Se connecter avec Google</button>'."\n";
}

if(getConfig("facebook_connect_activate") == 'yes')
{
	$class_facebook_connect->setAppId(getConfig("facebook_connect_app_id"));
	$social .= $class_facebook_connect->getDataScript();
	$social .= '<button class="btnConfirm" onclick="connectFB();"><img src="'.$url_script.'/images/facebook-connect-icon.png"> Se connecter avec Facebook</button>'."\n";
}

if(getConfig("google_connect_activate") == 'yes' || getConfig("facebook_connect_activate") == 'yes')
{
	$social .= '</div>'."\n";
}

$class_templateloader->assign("{social_connect}",$social);

$class_templateloader->assign("{error_message}",$errormessage);
$class_templateloader->show();

include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>