<?php

include "main.php";

$template = getConfig("template");

$class_templateloader->showHead('mon_compte');
$class_templateloader->openBody();

include "header.php";

$class_templateloader->loadTemplate("tpl/favoris.tpl");

$array = $class_search->getFavoris($class_favoris->getFavoris());

$block_resultat = "";
$itemOffre = new TemplateLoader();
$itemOffre->loadTemplate("tpl/blockannonce.tpl");

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
	$pro = $array[$x]['pro'];
	
	if($pro == 'yes')
	{
		$titreCategorie = "<b>(PRO)</b> ".$titreCategorie;
	}
	
	/* Si titre trop grand */
	if(strlen($titre) > 103)
	{
		$titre = substr($titre,0,103)." ...";
	}
	
	/* On verifie les Favoris et on les affiches le cas écheant */
	$fav_exist = $class_favoris->isFavorisExist($md5);
	if($fav_exist)
	{
		$itemOffre->assign("{favoris}",'<img src="'.$url_script.'/images/coeur-plein.png" id="favoris-'.$md5.'" title="Retirer des favoris" onclick="removeFavoris(\''.$md5.'\');">');
	}
	else
	{
		$itemOffre->assign("{favoris}",'<img src="'.$url_script.'/images/coeur-vide.png" id="favoris-'.$md5.'" title="Mettre en favoris" onclick="addFavoris(\''.$md5.'\');">');
	}
	
	$itemOffre->assign("{link}",$linkAnnonce);
	$itemOffre->assign("{image}","images/$img");
	$itemOffre->assign("{nbr_image}",$countphoto);
	$itemOffre->assign("{titre}",$titre);		
	$itemOffre->assign("{categorie}",$titreCategorie);
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
	
	$itemOffre->assign("{prix}",$class_monetaire->getReturnPrice($prix));
	//$class_fuseau_horaire->convertTimezone($date_annonce,getConfig("format_date"),getConfig("fuseau"));
	$itemOffre->assign("{date}",$class_fuseau_horaire->convertTimezone($date_annonce,getConfig("format_date"),getConfig("fuseau")));
	if($urgente == 2)
	{
		$urgente = '<div class="vignette-urgente-mini">'."\n";
		$urgente .= '<img src="images/vignette-urgente.png" alt="'.$vignette_urgente.'">'."\n";
		$urgente .= '</div>';
	}
	else
	{
		$urgente = '';
	}
	$itemOffre->assign("{urgente}",$urgente);
	$block_resultat .= $itemOffre->getData();
}

$class_templateloader->assign("{favoris_item}",$block_resultat);

$class_templateloader->show();

include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>