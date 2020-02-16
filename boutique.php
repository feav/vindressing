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
	$titleAnnonce = $title_boutique_professionel;
}
else
{
	$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titleAnnonce = $title_boutique_professionel_seul.' '.$req['titre'];
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

$publicite_top = $class_publicite->getPublicite("publicite_top");
$all_categorie = $class_map->getAllCategorieOption();

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
$count = $class_search->setSearchBoutiqueCount($page,$searchtext,$categorie,$region,$departement,$tri,$codepostal);
$totalPage = $class_search->getTotalPage($count);
$currencysigle = $class_monetaire->getSigle();

if($count == 0)
{
	$block_resultat = '<div class="unknow-annonce">'."\n";
	$block_resultat .= '<H2>'.$title_aucune_boutique.'</H2>'."\n";
	$block_resultat .= '</div>'."\n";
}
else
{
	$array = $class_search->setSearchBoutique($page,$searchtext,$categorie,$region,$departement,$tri,$codepostal);
	$block_resultat = "";
	$itemOffre = new TemplateLoader();
	$itemOffre->loadTemplate("tpl/blockboutique.tpl");
	
	for($x=0;$x<count($array);$x++)
	{
		$itemOffre->reload();
		$md5 = $array[$x]['md5'];
		$titre = $array[$x]['username'];
		$titreCategorie = $array[$x]['titreCategorie'];
		$img = $array[$x]['logo'];
		$count = $array[$x]['count'];
		$categorie = $array[$x]['categorie'];
		$slogan = $array[$x]['slogan'];
		$link = $array[$x]['link'];
		
		$itemOffre->assign("{link}",$link);
		if($img == '')
		{
			$itemOffre->assign("{image}","images/noimage.jpg");
		}
		else
		{
			$itemOffre->assign("{image}","images/logo/$img");
		}
		$itemOffre->assign("{titre}",$titre);		
		$itemOffre->assign("{nbr_annonce}",'<b>'.$count.' '.$title_boutique_nbr_annonce.'</b>');
		$itemOffre->assign("{slogan}",$slogan);
		$itemOffre->assign("{categorie}",$categorie);
		$block_resultat .= $itemOffre->getData();
	}
}

$access_website = getConfig("access_website");
if($access_website == 'full' || $access_website == '')
{

	$class_templateloader->loadTemplate("tpl/boutique.tpl");
	$class_templateloader->assign("{searchtext}",$searchtext);
	$class_templateloader->assign("{block_resultat}",$block_resultat);
	$class_templateloader->assign("{publicite_top}",$publicite_top);
	$class_templateloader->assign("{categorie}",$all_categorie);
	$class_templateloader->assign("{departement}",$all_region);
	$class_templateloader->assign("{prix}",$all_tri);
	$class_templateloader->assign("{titleannonce}",$titleAnnonce);

	/* Paging */
	$paging = '<div class="paging">'."\n";
	if($page == 0)
	{
		$paging .= '<a href="boutique.php?page=0" class="paging-blue"><</a>';
	}
	else
	{
		$paging .= '<a href="boutique.php?page='.($page-1).'" class="paging-blue"><</a>';
	}
	for($x=$page;$x<($page+5);$x++)
	{
		if($x < $totalPage)
		{
			if($page == $x)
			{
				$paging .= '<a href="boutique.php?page='.$x.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
			}
			else
			{
				$paging .= '<a href="boutique.php?page='.$x.'" class="paging-blue">'.($x+1).'</a>'."\n";
			}
		}
	}
	if($page == $totalPage)
	{
		$paging .= '<a href="boutique.php?page='.($totalPage-1).'" class="paging-blue">></a>';
	}
	else
	{
		$paging .= '<a href="boutique.php?page='.($page+1).'" class="paging-blue">></a>';
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

		/* Paging */
		$paging = '<div class="paging">'."\n";
		if($page == 0)
		{
			$paging .= '<a href="boutique.php?page=0" class="paging-blue"><</a>';
		}
		else
		{
			$paging .= '<a href="boutique.php?page='.($page-1).'" class="paging-blue"><</a>';
		}
		for($x=$page;$x<($page+5);$x++)
		{
			if($x < $totalPage)
			{
				if($page == $x)
				{
					$paging .= '<a href="boutique.php?page='.$x.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
				}
				else
				{
					$paging .= '<a href="boutique.php?page='.$x.'" class="paging-blue">'.($x+1).'</a>'."\n";
				}
			}
		}
		if($page == $totalPage)
		{
			$paging .= '<a href="boutique.php?page='.($totalPage-1).'" class="paging-blue">></a>';
		}
		else
		{
			$paging .= '<a href="boutique.php?page='.($page+1).'" class="paging-blue">></a>';
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