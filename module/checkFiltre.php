<?php

include "../config.php";

$categorie = $_REQUEST['categorie'];

/* On check quel filtre et selectionner sur cette categorie */
$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$filtre = $req['meta_title'];

if($filtre == 'module_filtre_auto')
{
	if(getConfig("module_filtre_auto") == 'true')
	{
		include "class.module_filtre_auto.php";
		$class_filtre_auto = new Module_filtre_auto();
		$class_filtre_auto->showFormOption();
		exit;
	}	
}
else if($filtre == 'module_filtre_immo')
{
	if(getConfig("module_filtre_immo") == 'true')
	{
		include "class.module_filtre_immo.php";
		$class_filtre_immo = new Module_filtre_immo();
		$class_filtre_immo->showFormOption();
		exit;
	}
}

?>