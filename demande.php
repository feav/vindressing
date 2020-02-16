<?php

include "main.php";

$searchtext = NULL;
$region = NULL;
$codepostal = NULL;
$departement_code = NULL;

if(isset($_REQUEST['categorie']))
{
	$categorie = AntiInjectionSQL($_REQUEST['categorie']);
}
else
{
	$categorie = NULL;
}

if(isset($_REQUEST['slug']))
{
	$slug = AntiInjectionSQL($_REQUEST['slug']);
	
	$SQL = "SELECT * FROM pas_categorie WHERE slug = '$slug'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$categorie = $req['id'];
}

if(isset($_REQUEST['searchtext']))
{
	$searchtext = AntiInjectionSQL($_REQUEST['searchtext']);
}

if(isset($_REQUEST['region']))
{
	$region = AntiInjectionSQL($_REQUEST['region']);
	$SQL = "SELECT * FROM pas_region WHERE idmap = '$region'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$idregion = $req['id'];
	
	$coderegion = " AND idregion = $idregion";
}
else
{
	$coderegion = "";
}

if(isset($_REQUEST['departement']))
{
	$departement = AntiInjectionSQL($_REQUEST['departement']);
}
else
{
	$departement = "";
}

if($categorie == 0)
{
	$titleAnnonce = 'Annonces : Demande';
}
else
{
	$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titleAnnonce = 'Demande de '.$req['titre'];
}

$class_templateloader->showHead('demandes');
$class_templateloader->openBody();

include "header.php";

$publicite_top = $class_publicite->getPublicite("publicite_top");
$all_categorie = $class_map->getAllCategorieOptionSelected($categorie);

if(getConfig("barre_cp_ou_dep") == 'departement')
{
	$all_region = $class_map->getAllRegionOption("categorybox");
}
else
{
	$all_region = $class_map->getCodePostal("categorybox");
}
$all_tri = $class_map->getTri("categorybox");

if(isset($_REQUEST['page']))
{
	$page = AntiInjectionSQL($_REQUEST['page']);
}
else
{
	$page = 0;
}

if(isset($_REQUEST['tri']))
{
	$tri = AntiInjectionSQL($_REQUEST['tri']);
}
else
{
	$tri = "plus_recente";
}

if(isset($_REQUEST['codepostal']))
{
	$codepostal = AntiInjectionSQL($_REQUEST['codepostal']);
}

$type = 'demande';
$array = $class_search->setSearch($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal);
$count = $class_search->setSearchCount($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal);
$totalPage = $class_search->getTotalPage($count);
$currencysigle = $class_monetaire->getSigle();

if($count == 0)
{
	$block_resultat = '<div class="unknow-annonce">'."\n";
	$block_resultat .= '<H2>'.$title_aucun_resultat_annonce.'</H2>'."\n";
	$block_resultat .= '</div>'."\n";
}
else
{
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
		
		$itemOffre->assign("{link}",$linkAnnonce);
		$itemOffre->assign("{image}","images/$img");
		$itemOffre->assign("{nbr_image}",$countphoto);
		$itemOffre->assign("{titre}",$titre);
		$itemOffre->assign("{categorie}",$titreCategorie);
		$itemOffre->assign("{region}",$titreRegion);
		$itemOffre->assign("{prix}",$prix." ".$currencysigle);
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
		
		/* On verifie les Favoris et on les affiches le cas écheant */
		$fav_exist = $class_favoris->isFavorisExist($md5);
		if($fav_exist)
		{
			$itemOffre->assign("{favoris}",'<img src="'.$url_script.'/images/coeur-plein.png" id="favoris-'.$md5.'" title="'.$title_favoris_remove.'" onclick="removeFavoris(\''.$md5.'\');">');
		}
		else
		{
			$itemOffre->assign("{favoris}",'<img src="'.$url_script.'/images/coeur-vide.png" id="favoris-'.$md5.'" title="'.$title_favoris_add.'" onclick="addFavoris(\''.$md5.'\');">');
		}
		
		$itemOffre->assign("{urgente}",$urgente);
		$block_resultat .= $itemOffre->getData();
	}
}

$access_website = getConfig("access_website");
if($access_website == 'full' || $access_website == '')
{

	$class_templateloader->loadTemplate("tpl/demande.tpl");
	$class_templateloader->assign("{searchtext}",$searchtext);
	$class_templateloader->assign("{block_resultat}",$block_resultat);
	$class_templateloader->assign("{publicite_top}",$publicite_top);
	$class_templateloader->assign("{categorie}",$all_categorie);
	$class_templateloader->assign("{departement}",$all_region);
	$class_templateloader->assign("{prix}",$all_tri);
	$class_templateloader->assign("{titleannonce}",$titleAnnonce);
	$class_publicite->updatePublicite($class_templateloader);
	$data = $class_plugin->useTemplate($class_templateloader->getData());
	$class_templateloader->setData($data);

	/* Paging */
	$paging = '<div class="paging">'."\n";
	$paging .= '<a href="demande.php?page=0&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'"" class="paging-blue"><</a>';
	for($x=$page;$x<($page+5);$x++)
	{
		if($x < $totalPage)
		{
			if($page == $x)
			{
				$paging .= '<a href="demande.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
			}
			else
			{
				$paging .= '<a href="demande.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'" class="paging-blue">'.($x+1).'</a>'."\n";
			}
		}
	}
	$paging .= '<a href="demande.php?page='.($totalPage-1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'"" class="paging-blue">></a>';
	$paging .= '</div>'."\n";

	$class_templateloader->assign("{paging}",$paging);

	$class_templateloader->show();

}
else
{
	if($isConnected)
	{
		$class_templateloader->loadTemplate("tpl/demande.tpl");
		$class_templateloader->assign("{searchtext}",$searchtext);
		$class_templateloader->assign("{block_resultat}",$block_resultat);
		$class_templateloader->assign("{publicite_top}",$publicite_top);
		$class_templateloader->assign("{categorie}",$all_categorie);
		$class_templateloader->assign("{departement}",$all_region);
		$class_templateloader->assign("{prix}",$all_tri);
		$class_templateloader->assign("{titleannonce}",$titleAnnonce);
		$class_publicite->updatePublicite($class_templateloader);
		$data = $class_plugin->useTemplate($class_templateloader->getData());
		$class_templateloader->setData($data);

		/* Paging */
		$paging = '<div class="paging">'."\n";
		$paging .= '<a href="demande.php?page=0&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'"" class="paging-blue"><</a>';
		for($x=$page;$x<($page+5);$x++)
		{
			if($x < $totalPage)
			{
				if($page == $x)
				{
					$paging .= '<a href="demande.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
				}
				else
				{
					$paging .= '<a href="demande.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'" class="paging-blue">'.($x+1).'</a>'."\n";
				}
			}
		}
		$paging .= '<a href="demande.php?page='.($totalPage-1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'"" class="paging-blue">></a>';
		$paging .= '</div>'."\n";

		$class_templateloader->assign("{paging}",$paging);

		$class_templateloader->show();
	}
	else
	{
		$class_templateloader->loadTemplate("template/tpl/limited.tpl");
		$class_templateloader->show();
	}
}

include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>