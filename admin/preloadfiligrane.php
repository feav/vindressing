<?php

include "../config.php";
include "../engine/class.imagehelper.php";

function AntiInjectionSQL($value)
{
	$value = strip_tags($value);
	
    $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

    return str_replace($search, $replace, $value);
}

$filigrane = getConfig("filigrane");

if(isset($_REQUEST['pourcent']))
{
	$pourcent = AntiInjectionSQL($_REQUEST['pourcent']);
	updateConfig("pourcent_filigrane",$pourcent);
}

if(isset($_REQUEST['position']))
{
	$position = AntiInjectionSQL($_REQUEST['position']);
	updateConfig("position_filigrane",$position);
}

if(isset($_REQUEST['activer']))
{
	$activer = AntiInjectionSQL($_REQUEST['activer']);
	updateConfig("activer_filigrane",$activer);
}

if($pourcent == '')
{
	$pourcent = getConfig("pourcent_filigrane");
}
if($position == '')
{
	$position = getConfig("position_filigrane");
}

$class_image_helper = new ImageHelper();
$class_image_helper->addFiligrane('images/model.jpg','../images/'.$filigrane,$pourcent,$position,true);

?>