<?php

include "main.php";

$option = $_REQUEST['option'];
if($option != '')
{
	$option = explode(",",$option);
}

$catannonce = AntiInjectionSQL($_REQUEST['catannonce']);

if(isset($_REQUEST['initialprice']))
{
	$prix = $_REQUEST['initialprice'];
}
else
{
	$prix = 0;
}

/* On check si un prix spécifique à été donner pour une annonce par categorie */
$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = $catannonce";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] != 0)
{
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = $catannonce";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$prix = $prix +  $req['prix'];
}
else
{
	/* On check si un prix spécifique à été donner pour une annonce */
	$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = 0";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req[0] != 0)
	{
		$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = 0";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$prix = $prix + $req['prix'];
	}
	else
	{
		//$prix = 0;
	}
}

if($option == '')
{
	/* Rien */
}
else
{
	for($x=0;$x<count($option);$x++)
	{
		$id = AntiInjectionSQL($option[$x]);
		$SQL = "SELECT * FROM pas_grille_tarif WHERE id = $id";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$prix = $prix + $req['prix'];
	}
}

echo "Total : ".$class_monetaire->getReturnPrice($prix);

?>