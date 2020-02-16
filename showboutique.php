<?php

include "main.php";

$md5boutique = AntiInjectionSQL($_REQUEST['md5']);

$SQL = "SELECT COUNT(*) FROM pas_compte_pro WHERE md5 = '$md5boutique'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: 404.php");
	exit;
}

$SQL = "SELECT * FROM pas_compte_pro WHERE md5 = '$md5boutique'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$logo = $req['logo'];
$adresse = $req['adresse'];
$description = $req['description'];
$site_internet = $req['site_internet'];
$categorie = $req['categorie'];

$SQL = "SELECT * FROM pas_categorie WHERE id = $categorie";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$titreCategorie = $req['titre'];

$SQL = "SELECT * FROM pas_user WHERE md5 = '$md5boutique'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$titre = $req['username'];
$iduser = $req['id'];

$titleSEO = "Boutique de $titre";
$descriptionSEO = $req['description'];

$template = getConfig("template");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $titleSEO; ?></title>
	<meta name="Description" content="<?php echo $descriptionSEO; ?>">
    <meta name="Keywords" content="">
    <meta name="Author" content="">
    <meta name="Identifier-URL" content="">
    <meta name="Revisit-after" content="3 days">
    <meta name="Robots" content="all">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo $template; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $url_script; ?>/template/<?php echo $template; ?>/css/responsive.css">
	<meta property="og:url" content="<?php echo $url_script; ?>/showboutique.php?md5=<?php echo $md5; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="<?php echo $titre; ?>" />
	<meta property="og:description" content="<?php echo substr($description,0,255); ?>" />
	<meta property="og:image" content="<?php echo $url_script."/images/".$bigimg; ?>" />
	<link href="css/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<?php

$class_templateloader->openBody();

include "header.php";

$class_templateloader->loadTemplate("tpl/showboutique.tpl");

$nav = '<a href="'.$url_script.'/boutique.php">Boutiques</a> > <a href="'.$url_script.'/boutique.php?categorie='.$categorie.'">'.$titreCategorie.'</a>';

$class_templateloader->assign("{navigation}",$nav);
$class_templateloader->assign("{titre}",$titre);
if($logo == '')
{
	$class_templateloader->assign("{logo}",$url_script.'\images\nologo.jpg');
}
else
{
	$class_templateloader->assign("{logo}",$url_script.'\images\logo\/'.$logo);
}
$class_templateloader->assign("{adresse}",$adresse);
$class_templateloader->assign("{description}",substr($description,0,300));
if($site_internet != '')
{
	/* On check si l'url du site est bien en HTTP ou HTTPS */
	$site_internet = strtolower($site_internet);
	$pos = stripos($site_internet,"http://");
	$pos2 = stripos($site_internet,"https://");
	if($pos !== FALSE)
	{
		$class_templateloader->assign("{website}",'<a href="'.$site_internet.'" target="newpage" class="blueBtn"><i class="fas fa-globe-europe"></i> Visiter notre site internet</a>');
	}
	else if($pos2 !== FALSE)
	{
		$class_templateloader->assign("{website}",'<a href="'.$site_internet.'" target="newpage" class="blueBtn"><i class="fas fa-globe-europe"></i> Visiter notre site internet</a>');
	}
	else
	{
		$class_templateloader->assign("{website}",'');
	}
}
else
{
	$class_templateloader->assign("{website}",'');
}

if(isset($_REQUEST['page']))
{
	$page = $_REQUEST['page'];
	$page = AntiInjectionSQL($page);
}
else
{
	$page = 0;
}

/* On s'occupe des blocks */
$array = $class_search->setSearchBoutiqueOnly($iduser,$page,$search);
$count = $class_search->setSearchBoutiqueOnlyCount($iduser,$search);
$totalPage = $class_search->getTotalPage($count);

$block_resultat = "";
$itemOffre = new TemplateLoader();
$itemOffre->loadTemplate("tpl/blockannonce.tpl");

if($count == 0)
{
	$block_resultat = '<div class="unknow-annonce">'."\n";
	$block_resultat .= '<H2>Aucune annonces dans cette boutique pour le moment</H2>'."\n";
	$block_resultat .= '</div>'."\n";
}
else
{
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
		
		$itemOffre->assign("{link}",$linkAnnonce);
		$itemOffre->assign("{image}","images/$img");
		$itemOffre->assign("{nbr_image}",$countphoto);
		$itemOffre->assign("{titre}",$titre);		
		$itemOffre->assign("{categorie}",$titreCategorie);
		$itemOffre->assign("{region}",$titreRegion);
		
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

$class_templateloader->assign("{block_offre}",$block_resultat);

/* Paging */
$paging = '<div class="paging">'."\n";
if($page == 0)
{
	$paging .= '<a href="showboutique.php?md5='.$md5boutique.'&page='.$page.'" class="paging-blue"><</a>';
}
else
{
	$paging .= '<a href="showboutique.php?md5='.$md5boutique.'&page='.($page-1).'" class="paging-blue"><</a>';
}
for($x=$page;$x<($page+5);$x++)
{
	if($x < $totalPage)
	{
		if($page == $x)
		{
			$paging .= '<a href="showboutique.php?md5='.$md5boutique.'&page='.$x.'" class="paging-blue pageselected">'.($x+1).'</a>'."\n";
		}
		else
		{
			$paging .= '<a href="showboutique.php?md5='.$md5boutique.'&page='.$x.'" class="paging-blue">'.($x+1).'</a>'."\n";
		}
	}
}
if($page == $totalPage)
{
	$paging .= '<a href="showboutique.php?md5='.$md5boutique.'&page='.($totalPage-1).'" class="paging-blue">></a>';
}
else
{
	$paging .= '<a href="showboutique.php?md5='.$md5boutique.'&page='.($page+1).'" class="paging-blue">></a>';
}
$paging .= '</div>'."\n";
$class_templateloader->assign("{paging}",$paging);
$class_templateloader->show();

include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>