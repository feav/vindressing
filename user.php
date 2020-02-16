<?php

include "main.php";

if(isset($_REQUEST['user']))
{
	$user = AntiInjectionSQL($_REQUEST['user']);
}
else
{
	header("Location: 404.php");
	exit;
}

$titleAnnonce = "Utilisateur \"$user\"";

$class_templateloader->showHead('offres');
$class_templateloader->openBody();

include "header.php";

$publicite_top = $class_publicite->getPublicite("publicite_top");

$count = $class_search->setSearchUserCount($user);
$totalPage = $class_search->getTotalPage($count);
$currencysigle = $class_monetaire->getSigle();

if($count == 0)
{
	$block_resultat = '<div class="unknow-annonce">'."\n";
	$block_resultat .= '<H2>Aucune annonce pour cette utilisateur pour le moment</H2>'."\n";
	$block_resultat .= '</div>'."\n";
}
else
{
	$array = $class_search->setSearchUser($page,$user);
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
	$class_templateloader->loadTemplate("tpl/user.tpl");
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
		$paging .= '<a href="user.php?page=0" class="paging-blue"><</a>';
	}
	else
	{
		$paging .= '<a href="user.php?page='.($page-1).'" class="paging-blue"><</a>';
	}
	for($x=$page;$x<($page+5);$x++)
	{
		if($x < $totalPage)
		{
			if($page == $x)
			{
				$paging .= '<a href="user.php?page='.$x.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
			}
			else
			{
				$paging .= '<a href="user.php?page='.$x.'" class="paging-blue">'.($x+1).'</a>'."\n";
			}
		}
	}
	if($page == $totalPage)
	{
		$paging .= '<a href="user.php?page='.($totalPage-1).'" class="paging-blue">></a>';
	}
	else
	{
		$paging .= '<a href="user.php?page='.($page+1).'" class="paging-blue">></a>';
	}
	$paging .= '</div>'."\n";

	$class_templateloader->assign("{paging}",$paging);

	$class_templateloader->show();

}
else
{
	if($isConnected)
	{
		$class_templateloader->loadTemplate("template/tpl/user.tpl");
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
			$paging .= '<a href="user.php?page=0" class="paging-blue"><</a>';
		}
		else
		{
			$paging .= '<a href="user.php?page='.($page-1).'" class="paging-blue"><</a>';
		}
		for($x=$page;$x<($page+5);$x++)
		{
			if($x < $totalPage)
			{
				if($page == $x)
				{
					$paging .= '<a href="user.php?page='.$x.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
				}
				else
				{
					$paging .= '<a href="user.php?page='.$x.'" class="paging-blue">'.($x+1).'</a>'."\n";
				}
			}
		}
		if($page == $totalPage)
		{
			$paging .= '<a href="user.php?page='.($totalPage-1).'" class="paging-blue">></a>';
		}
		else
		{
			$paging .= '<a href="user.php?page='.($page+1).'" class="paging-blue">></a>';
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