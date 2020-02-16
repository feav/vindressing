<?php

global $class_horaire_ouverture;

$class_templateloader->loadTemplate("tpl/header.tpl");

$phone_hotline = getConfig("phone_hotline");
$message_hotline = getConfig("message_hotline");
$hotline_open_message = getConfig("hotline_open_message");
$hotline_activate = getConfig("hotline_activate");
if($hotline_activate == 'yes')
{
	$isOpen = $class_horaire_ouverture->isOpen();
	if($hotline_open_message == 'yes')
	{
		if($isOpen)
		{
			$open = '<font color=#00ff00><b>(Ouvert)</b></font>';
		}
		else
		{
			$open = '<font color=red><b>(Fermer)</b></font>';
		}
	}
	else
	{
		$open = '';
	}
	$hotline = '<div class="hotline"><div class="hotline-icon"><img src="'.$url_script.'/images/phone-hotline-icon.png"></div> <div class="hotline-phone">'.$phone_hotline.'</div> <div class="hotline-message">'.$message_hotline.'</div> '.$open.'</div>';
}
else
{
	$hotline = '';
}

$class_templateloader->assign("{hotline}",$hotline);
$class_templateloader->assign("{social_top}",$class_social->getSocialBox());
$class_templateloader->assign('{url_script}',$url_script);
$class_templateloader->assign('{alt_logo}',getConfig("alt_logo"));
$class_templateloader->assign('{logo}',$class_logo->showLogo());
$class_templateloader->assign('{usermenu}',$class_user->getMenuUser());
$class_templateloader->assign('{menu}',$class_menu->getMenuComputer());
$class_templateloader->assign('{menumobile}',$class_menu->getMenuMobile());
$class_templateloader->assign('{colordesign}',$class_colordesign->showColorDesign());
$class_templateloader->show();

/* Barre social flotante */
if(getConfig("visibilite_float_left") == 'yes')
{
	?>
	<div class="float-bar-social">
	<?php
	echo $class_social->getSocialBoxNormal();
	?>
	</div>
	<?php
}

?>