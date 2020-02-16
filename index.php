<?php

include "main.php";

$class_templateloader->showHead('accueil');
	
$class_templateloader->openBody();

include "header.php";

$class_templateloader->loadTemplate("tpl/index.tpl");

if(getConfig("habillage_actif") == 'yes')
{
	if(getConfig("habillage_image") != '')
	{
		$habillage = new TemplateLoader();
		$habillage->loadTemplate("tpl/habillage.tpl");
		$habillage->assign("{url_habillage}","images/".getConfig("habillage_image"));

		$class_templateloader->assign("{habillage}",$habillage->getData());
	}
	else
	{
		$class_templateloader->assign("{habillage}","");
	}
}
else
{
	$class_templateloader->assign("{habillage}","");
}

$class_map->loadMap("map_france_light");
$map = $class_map->showMap();
$region = $class_map->showRegionMap();
$categorie_list = $class_map->showCategorie();
$all_categorie = $class_map->getAllCategorieOption();
$all_region = $class_map->getAllRegionOption("selectClass");

$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();
			
$count_annonce = $req[0];

$class_publicite->updatePublicite($class_templateloader);

$class_templateloader->assign("{map}",$map);
$class_templateloader->assign("{region}",$region);
$class_templateloader->assign("{count_annonce}",$count_annonce);
$class_templateloader->assign("{categorie}",$categorie_list);
$class_templateloader->assign("{publicite_top}",$publicite_top);
$class_templateloader->assign("{all_categorie}",$all_categorie);
$class_templateloader->assign("{all_departement}",$all_region);

$last_annonce = $class_search->getLastAnnonce(9);
$itemOffre = new TemplateLoader();
$itemOffre->loadTemplate("tpl/blockannonce.tpl");

$array = $last_annonce;
$currencysigle = $class_monetaire->getSigle();

$block_resultat = NULL;

for($x=0;$x<count($array);$x++)
{
	$itemOffre->reload();
	$md5 = $array[$x]['md5'];
	$titre = $array[$x]['titre'];
	$titreCategorie = $array[$x]['titreCategorie'];
	$countphoto = $array[$x]['countphoto'];
	$img = $array[$x]['image'];
	$prix = $array[$x]['prix'];
	$linkAnnonce = $array[$x]['link'];
	$date_annonce = $array[$x]['date'];
	$urgente = $array[$x]['urgente'];
	$titreRegion = $array[$x]['titreRegion'];
	
	$itemOffre->assign("{link}",$linkAnnonce);
	$itemOffre->assign("{image}","images/$img");
	$itemOffre->assign("{nbr_image}",$countphoto);
	$itemOffre->assign("{titre}",$titre);
	$itemOffre->assign("{categorie}",utf8_encode($titreCategorie));
	$itemOffre->assign("{region}",$titreRegion);
	
	if($prix == '')
	{
		$prix = '';
	}
	else if($prix == 0)
	{
		$prix = '';
	}
	else
	{
		$prix = $prix." ".$currencysigle;
	}
	
	$itemOffre->assign("{prix}",$prix);
	$itemOffre->assign("{date}",$date_annonce);
	if($urgente == 2)
	{
		$urgente = '<div class="vignette-urgente">'."\n";
		$urgente .= '<img src="images/vignette-urgente.png" alt="Annonce Urgente">'."\n";
		$urgente .= '</div>';
	}
	else
	{
		$urgente = '';
	}
	$itemOffre->assign("{urgente}",$urgente);
	$block_resultat .= $itemOffre->getData();
}

$class_templateloader->assign("{last_annonces}",$block_resultat);

$data = $class_plugin->useTemplate($class_templateloader->getData());
$class_templateloader->setData($data);

$class_templateloader->show();
	
include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
	
?>
