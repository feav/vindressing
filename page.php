<?php

include "main.php";

$slug = AntiInjectionSQL($_REQUEST['slug']);

$SQL = "SELECT COUNT(*) FROM pas_page WHERE slug = '$slug'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$count = $req[0];

if($count == 0)
{
	header("Location: 404.php");
	exit;
}

$SQL = "SELECT * FROM pas_page WHERE slug = '$slug'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$contenue = $req['contenue'];

$class_templateloader->showHead($slug);
$class_templateloader->openBody();
	
include "header.php";
	
$class_templateloader->loadTemplate("tpl/page.tpl");
$class_templateloader->assign("{contenue}",$contenue);
$class_publicite->updatePublicite($class_templateloader);

$data = $class_plugin->useTemplate($class_templateloader->getData());
$class_templateloader->setData($data);

$class_templateloader->show();
		
include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();
	
?>