<?php

include "config.php";
include "checkuserconnected.php";
/* En prévision de la revision des Langues du script update TODO à faire */
include "lang/fr.php";
include "engine/engine.php";

/* Langue principale du site internet */
$language = getConfig("langue_principal");

/* Fix problème avec OR exemple @\orange.fr */
function AntiInjectionSQL($string) 
{
	$string = str_replace("'","\'",$string);
	$string = str_replace('"','\"',$string);
	$string = str_replace(";","\;",$string);
	$string = str_replace("`","\`",$string);
	$string = str_replace("&","\&",$string);
	$string = str_replace(",","\,",$string);
	$string = str_replace("/*","\/\*",$string);
	$string = str_replace("--","\-\-",$string);
	$string = str_replace("#","\#",$string);
	
	return $string;
}

?>