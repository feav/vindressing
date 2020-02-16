<?php

include "main.php";

$searchtext = NULL;
$region = NULL;
$codepostal = NULL;
$departement_code = NULL;
$categorie = NULL;

if(isset($_REQUEST['categorie']))
{
	$categorie = AntiInjectionSQL($_REQUEST['categorie']);
}

if(isset($_REQUEST['slug']))
{
	$slug = $_REQUEST['slug'];
	$slug = AntiInjectionSQL($slug);
	
	$SQL = "SELECT * FROM pas_categorie WHERE slug = '$slug'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$categorie = $req['id'];
}

if(isset($_REQUEST['searchtext']))
{
	$searchtext = $_REQUEST['searchtext'];
	$searchtext = AntiInjectionSQL($searchtext);
}

if(isset($_REQUEST['region']))
{
	$region = $_REQUEST['region'];
	$region = AntiInjectionSQL($region);
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
	$departement = $_REQUEST['departement'];
	$departement = AntiInjectionSQL($departement);
}
else
{
	$departement = "";
}

if($categorie == 0)
{
	$titleAnnonce = 'Annonces : Offres';
}
else
{
	$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titleAnnonce = 'Offres de '.$req['titre'];
}

/* Si le module filtre adulte est actif */
if(getConfig("module_filtre_adulte") == 'true')
{
	if($categorie != 0)
	{
		$filtre = $req['meta_title'];
		if($filtre == 'module_filtre_adulte')
		{
			include "module/class.module_filtre_adulte.php";
			$filtre = new Module_filtre_Adulte();
		}
	}
}

$class_templateloader->showHead('offres');
$class_templateloader->openBody();

include "header.php";

if(getConfig("module_filtre_adulte") == 'true')
{
	if($categorie != 0)
	{
		$f = $req['meta_title'];
		if($f == 'module_filtre_adulte')
		{
			$filtre->show();
		}
	}
}

$all_categorie = $class_map->getAllCategorieOptionSelected($categorie);

if(getConfig("barre_cp_ou_dep") == 'departement')
{
	$all_region = $class_map->getAllRegionOption("categorybox");
}
else if(getConfig("barre_cp_ou_dep") == 'codepostal')
{
	$all_region = $class_map->getCodePostal("categorybox");
}
else
{
	$all_region = $class_map->getAllRegionOption("categorybox");
}
$all_tri = $class_map->getTri("categorybox");

if(isset($_REQUEST['page']))
{
	$page = $_REQUEST['page'];
	$page = AntiInjectionSQL($page);
}
else
{
	$page = 0;
}

if(isset($_REQUEST['tri']))
{
	$tri = $_REQUEST['tri'];
	$tri = AntiInjectionSQL($tri);
}
else
{
	$tri = "plus_recente";
}

if(isset($_REQUEST['codepostal']))
{
	$codepostal = $_REQUEST['codepostal'];
	$codepostal = AntiInjectionSQL($codepostal);
}

$type = 'offre';
$array = $class_search->setSearch($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal);
$count = $class_search->setSearchCount($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal);
$totalPage = $class_search->getTotalPage($count);
$currencysigle = $class_monetaire->getSigle();

if($count == 0)
{
	$block_resultat = '<div class="unknow-annonce">'."\n";
	$block_resultat .= '<H2>Aucune annonces dans cette catégorie pour le moment</H2>'."\n";
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
}

$access_website = getConfig("access_website");
if($access_website == 'full' || $access_website == '')
{

	$class_templateloader->loadTemplate("tpl/offre.tpl");
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
	if($page == 0)
	{
		$paging .= '<a href="offre.php?page=0&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue"><</a>';
	}
	else
	{
		$paging .= '<a href="offre.php?page='.($page-1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue"><</a>';
	}
	for($x=$page;$x<($page+5);$x++)
	{
		if($x < $totalPage)
		{
			if($page == $x)
			{
				$paging .= '<a href="offre.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
			}
			else
			{
				$paging .= '<a href="offre.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue">'.($x+1).'</a>'."\n";
			}
		}
	}
	if($page == $totalPage)
	{
		$paging .= '<a href="offre.php?page='.($totalPage-1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue">></a>';
	}
	else
	{
		$paging .= '<a href="offre.php?page='.($page+1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue">></a>';
	}
	$paging .= '</div>'."\n";

	$class_templateloader->assign("{paging}",$paging);

	$class_templateloader->show();

}
else
{
	if($isConnected)
	{
		$class_templateloader->loadTemplate("template/tpl/offre.tpl");
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
		if($page == 0)
		{
			$paging .= '<a href="offre.php?page=0&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue"><</a>';
		}
		else
		{
			$paging .= '<a href="offre.php?page='.($page-1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue"><</a>';
		}
		for($x=$page;$x<($page+5);$x++)
		{
			if($x < $totalPage)
			{
				if($page == $x)
				{
					$paging .= '<a href="offre.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
				}
				else
				{
					$paging .= '<a href="offre.php?page='.$x.'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue">'.($x+1).'</a>'."\n";
				}
			}
		}
		if($page == $totalPage)
		{
			$paging .= '<a href="offre.php?page='.($totalPage-1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue">></a>';
		}
		else
		{
			$paging .= '<a href="offre.php?page='.($page+1).'&categorie='.$categorie.'&searchtext='.$searchtext.'&departement='.$departement_code.'&tri='.$tri.'&codepostal='.$codepostal.'&region='.$region.'" class="paging-blue">></a>';
		}
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