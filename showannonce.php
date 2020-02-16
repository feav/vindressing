<?php

include "main.php";

$critere = NULL;

$md5 = AntiInjectionSQL($_REQUEST['md5']);
if(isset($_REQUEST['slug']))
{
	$slug = AntiInjectionSQL($_REQUEST['slug']);
}
else
{
	$slug = NULL;
}

if($md5 != '')
{
	$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req[0] == 0)
	{
		header("Location: 404.php");
		exit;
	}

	// On check si valider
	$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req['valider'] == 'no')
	{
		header("Location: 404.php");
		exit;
	}
	if($req['status'] == 'NOVISIBLE')
	{
		header("Location: 404.php");
		exit;
	}
}
else
{
	$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE slug = '$slug'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req[0] == 0)
	{
		header("Location: 404.php");
		exit;
	}
	if($req['status'] == 'NOVISIBLE')
	{
		header("Location: 404.php");
		exit;
	}
	
	// On check si valider
	$SQL = "SELECT * FROM pas_annonce WHERE slug = '$slug'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req['valider'] == 'no')
	{
		header("Location: 404.php");
		exit;
	}
	
	$md5 = $req['md5'];
}

$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

// On ajoute une visite
$SQL = "UPDATE pas_annonce SET nbr_vue = nbr_vue + 1 WHERE md5 = '$md5'";
$pdo->query($SQL);

$titre = $req['titre'];
$date_soumission = $req['date_soumission'];
$prix = $req['prix'];
$prix_nosigle = $req['prix'];
$codepostal = $req['codepostal'];
$cp = $codepostal;
$description = $req['texte'];
$idcategorie = $req['idcategorie'];
$iduser = $req['iduser'];
$nbr_vue = $req['nbr_vue'];
$telephone = $req['telephone'];
$ville = $req['ville'];
$idregion = $req['idregion'];
$slug = $req['slug'];
$urgente = $req['urgente'];
$pro = $req['pro'];
$offre_ou_demande = $req['offre_demande'];

// On recupere le nom de la categorie
$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$namecategorie = $req['titre'];

// Recupere le pseudo de l'utilisateur
$SQL = "SELECT * FROM pas_user WHERE id = $iduser";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$username = $req['username'];
$md5user = $req['md5'];
$emailuser = $req['email'];

// On recupere les images
$arrayImage = NULL;
$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
$reponse = $pdo->query($SQL);
while($req = $reponse->fetch())
{
	$arrayImage[count($arrayImage)] = $req['image'];
}

if(count($arrayImage) == 0)
{
	$bigimg = 'noimage.jpg';
}
else
{
	$bigimg = "annonce/".$arrayImage[0];
}

$titleSEO = getTitleSEO('page_annonce');
$descriptionSEO = getDescriptionSEO('page_annonce');

$departement = substr($codepostal,0,2);
			
$SQL = "SELECT * FROM pas_departement WHERE departement_code = '$departement'";
$r = $pdo->query($SQL);
$_req = $r->fetch();
$depname = $_req['departement_nom'];
$dep = '<a href="offre.php?departement='.$departement.'">'.$depname.'</a> >';

$titleSEO = str_replace("%title%",$titre,$titleSEO);
$titleSEO = str_replace("%region%",$depname,$titleSEO);
$titleSEO = str_replace("%ville%",$ville,$titleSEO);
$descriptionSEO = str_replace("%title%",$titre,$descriptionSEO);
$descriptionSEO = str_replace("%region%",$depname,$descriptionSEO);
$descriptionSEO = str_replace("%ville%",$ville,$descriptionSEO);

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
	<meta property="og:url" content="<?php echo $url_script; ?>/showannonce.php?md5=<?php echo $md5; ?>" />
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

if($pro == 'yes')
{
	$class_templateloader->loadTemplate("tpl/showannonce_pro.tpl");
}
else
{
	$class_templateloader->loadTemplate("tpl/showannonce.tpl");
}

$url_rewriting = getConfig("url_rewriting");
if($url_rewriting == 'yes')
{
	$url_annonce = $url_script.'/'.$md5.'/annonce-'.$slug.'.html';
}
else
{
	$url_annonce = $url_script.'/showannonce.php?md5='.$md5;
}

/* Navigation Bar */

$nav_separator = ' > ';
$champs_pays = getConfig("champs_pays");

/* On recuperer le type de recherche employée par le script */
$recherche = getConfig("barre_cp_ou_dep");
if($recherche == 'departement')
{
	/* Recherche par département (Appliquer uniquement en France) */
	$departement = substr($codepostal,0,2);
	
	$SQL = "SELECT * FROM pas_departement WHERE departement_code = '$departement'";
	$r = $pdo->query($SQL);
	$_req = $r->fetch();
	
	$region_title = $_req['departement_nom'];
	$region_request = '?departement='.$departement;
}
else if($recherche == 'codepostal')
{
	/* Recherche par Code Postal (Dans ce cas on affiche la region) */
	
	$SQL = "SELECT * FROM pas_region WHERE id = $idregion";
	$r = $pdo->query($SQL);
	$_req = $r->fetch();
	
	$region_title = $_req['titre'];
	$region_request = '?region='.$_req['idmap'];
}
else if($recherche == 'region')
{
	/* Recherche par Code Postal (Dans ce cas on affiche la region) */
	
	$SQL = "SELECT * FROM pas_region WHERE id = $idregion";
	$r = $pdo->query($SQL);
	$_req = $r->fetch();
	
	$region_title = $_req['titre'];
	$region_request = '?region='.$_req['idmap'];
}


if($offre_ou_demande == 'offre')
{
	/* Navigation Bar Offre */
	$navigation = '<a href="'.$url_script.'">Accueil</a>';
	$navigation .= $nav_separator;
	$navigation .= '<a href="'.$url_script.'/offre.php">Offres</a>';
	$navigation .= $nav_separator;
	if($champs_pays != '')
	{
		$navigation .= '<a href="'.$url_script.'/offre.php">'.$champs_pays.'</a>';
		$navigation .= $nav_separator;
	}
	$navigation .= '<a href="'.$url_script.'/offre.php'.$region_request.'">'.$region_title.'</a>';
	$navigation .= $nav_separator;
	$navigation .= '<a href="'.$url_script.'/offre.php?categorie='.$idcategorie.'">'.$namecategorie.'</a>';
	$navigation .= $nav_separator;
	$navigation .= '<a href="'.$url_annonce.'">'.$titre.'</a>';
}
else
{
	/* Navigation Bar Demande */
	$navigation = '<a href="'.$url_script.'">Accueil</a>';
	$navigation .= $nav_separator;
	$navigation .= '<a href="'.$url_script.'/demande.php">Demandes</a>';
	$navigation .= $nav_separator;
	if($champs_pays != '')
	{
		$navigation .= '<a href="'.$url_script.'/demande.php">'.$champs_pays.'</a>';
		$navigation .= $nav_separator;
	}
	$navigation .= '<a href="'.$url_script.'/demande.php'.$region_request.'">'.$region_title.'</a>';
	$navigation .= $nav_separator;
	$navigation .= '<a href="'.$url_script.'/demande.php?categorie='.$idcategorie.'">'.$namecategorie.'</a>';
	$navigation .= $nav_separator;
	$navigation .= '<a href="'.$url_annonce.'">'.$titre.'</a>';
}

$class_templateloader->assign("{navigation}",$navigation);
$class_templateloader->assign("{titre}",$titre);
$class_templateloader->assign("{bigimage}",$url_script."/images/".$bigimg);
$class_templateloader->assign("{url_script}",$url_script);

if($urgente == 2)
{
	$class_templateloader->assign("{urgente}",'<div class="vignette-urgente"><img src="'.$url_script.'/images/vignette-big-urgent.png"></div>');
}
else
{
	$class_templateloader->assign("{urgente}","");
}

$allimage = "";
if(count($arrayImage) > 4)
{
	$nbrimage = count($arrayImage);
	$allimage .= '<style>';
	$allimage .= '.miniImage';
	$allimage .= '{';
	$allimage .= 'width:'.((100/$nbrimage)-1).'%;';
	$allimage .= '}';
	$allimage .= '</style>';
}

if(count($arrayImage) > 1)
{
	for($x=0;$x<count($arrayImage);$x++)
	{
		if($x==0)
		{
			$bigImage = $arrayImage[$x];
			$thumb = explode(".",$bigImage);
			$thumb = $thumb[0]."-thumb.".$thumb[1];
			$allimage .= '<div class="miniImage" id="id-'.$x.'" onclick="showBigImage(\''.$url_script.'/images/annonce/'.$bigImage.'\',\''.$x.'\');" style="border: 2px solid rgb(255, 135, 0);">'."\n";
			$allimage .= '<img src="'.$url_script.'/images/annonce/'.$thumb.'">';
			$allimage .= '</div>';
		}
		else
		{
			$bigImage = $arrayImage[$x];
			$thumb = explode(".",$bigImage);
			$thumb = $thumb[0]."-thumb.".$thumb[1];
			$allimage .= '<div class="miniImage" id="id-'.$x.'" onclick="showBigImage(\''.$url_script.'/images/annonce/'.$bigImage.'\',\''.$x.'\');">'."\n";
			$allimage .= '<img src="'.$url_script.'/images/annonce/'.$thumb.'">';
			$allimage .= '</div>';
		}
	}
}

$currencysigle = $class_monetaire->getSigle();

$misenligne = getLangue("mise_en_ligne",getConfig("langue_principal"))." ".$class_fuseau_horaire->convertTimezone($date_soumission,getConfig("format_date"),getConfig("fuseau")); 

$class_templateloader->assign("{all_image}",$allimage);
$class_templateloader->assign("{date_mise_en_ligne}",$misenligne);
$class_templateloader->assign("{nombre_de_vue}",$nbr_vue);
if($pro == 'yes')
{
	$class_templateloader->assign("{utilisateur}",'<div class="pro-title"><a href="'.$url_script.'/showboutique.php?md5='.$md5user.'">'.$username.'</a></div>');
	
	$SQL = "SELECT * FROM pas_compte_pro WHERE md5 = '$md5user'";
	$r = $pdo->query($SQL);
	$rr = $r->fetch();
	
	$class_templateloader->assign("{adresse}",'<div class="pro-adresse"><i class="fas fa-map-marker-alt"></i> '.$rr['adresse'].'</div>');
	$class_templateloader->assign("{description_pro}",'<div class="pro-description">'.$rr['description'].'</div>');
	$class_templateloader->assign("{site_internet}",'<div class="pro-website"><a href="'.$rr['site_internet'].'" target="newpage"><i class="fas fa-globe-europe"></i> '.$rr['site_internet'].'</a></div>');
	if($rr['logo'] != '')
	{
		$class_templateloader->assign("{logo}",'<img src="'.$url_script.'/images/logo/'.$rr['logo'].'">');
	}
	else
	{
		$class_templateloader->assign("{logo}",'');
	}
}
else
{
	if(getConfig("parcours_user") == 'yes')
	{
		$class_templateloader->assign("{utilisateur}",'<a href="'.$url_script.'/user.php?user='.$username.'">'.$username.'</a>');
	}
	else
	{
		$class_templateloader->assign("{utilisateur}",$username);
	}
}

if($prix == '')
{
	$prix = getLangue("free",getConfig("langue_principal"));
}
else if($prix == 0)
{
	$prix = getLangue("free",getConfig("langue_principal"));
}
else
{
	$prix = $class_monetaire->getReturnPrice($prix);
}

if(getConfig("module_filtre_immo") == 'true')
{
	$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
	$r = $pdo->query($SQL);
	$rr = $r->fetch();
	
	if($rr['meta_title'] == 'module_filtre_immo')
	{
		$SQL = "SELECT * FROM pas_filtre_immo WHERE md5 = '$md5'";
		$r = $pdo->query($SQL);
		$rr = $r->fetch();
		
		$idtype_de_bien = $rr['type_de_bien'];
		
		$SQL = "SELECT * FROM pas_immo_type_bien WHERE id = $idtype_de_bien";
		$u = $pdo->query($SQL);
		$uu = $u->fetch();
		
		$type_de_bien = $uu['titre'];
		
		$ges = $rr['ges'];
		
		$SQL = "SELECT * FROM pas_immo_ges WHERE id = $ges";
		$f = $pdo->query($SQL);
		$ff = $f->fetch();
		
		$ges = $ff['classe'];
		
		$critere = '<div class="block-description">';
		$critere .= '<b>Critères :</b><br><br>';
		$critere .= '<table>';
		$critere .= '<tr>';
		$critere .= '<td><b>Type de bien</td><td><b>Pièces</b><td><td><b>Surface</b><td>';
		$critere .= '</tr>';
		$critere .= '<tr>';
		$critere .= '<td>'.$type_de_bien.'<td>'.$rr['pieces'].'<td><td>'.$rr['surface'].' m²<td>';
		$critere .= '</tr>';
		$critere .= '<tr>';
		$critere .= '<td><b>GES</b></td><td><b>Classe énergie</b></td><td></td>';
		$critere .= '</tr>';
		$critere .= '<tr>';
		$critere .= '<td>'.$class_immo_graphique->showGraphiqueGES($ges).'</td><td>'.$class_immo_graphique->showGraphiqueEnergie('a').'</td><td></td>';
		$critere .= '</tr>';
		$critere .= '</table>';
		$critere .= '</div>';
		$critere .= '<hr>';
	}
}
else
{
	$critere = '';
}

$class_templateloader->assign("{prix}",$prix);
$class_templateloader->assign("{critere}",$critere);

/* **TODO** Modification à réaliser ici (CP + VILLE ou VILLE Uniquement) uniquement sur la balise {VILLE} */
$class_templateloader->assign("{codepostal}",$cp);
$class_templateloader->assign("{ville}",ucfirst(strtolower($ville)));

$class_templateloader->assign("{description}",nl2br($description));
$class_templateloader->assign("{signaler_link}",$url_script."/signaler-l-annonce.php?md5=".$md5);
$class_templateloader->assign("{sharing_social}",$class_social->getSocialSharing($md5,$titre,"Pas Script"));

if($telephone != '')
{
	$phone_block = '<div class="phone-number-block">'."\n";
	$phone_block .= '<a href="javascript:void(0);" id="btnPhone" onclick="showNumberPhone();" class="orangBtn phone-icon block-full-btn">'.getLangue("voir_le_numero",getConfig("langue_principal")).'</a>'."\n";
	$phone_block .= '<div id="phone-number" style="display:none;">'."\n";
	$phone_block .= '<a href="tel:'.$telephone.'">'.$telephone.'</a>'."\n";
	$phone_block .= '</div>'."\n";
	$phone_block .= '</div>'."\n";
}
else
{
	$phone_block = '<br>';
}

/* Obvy */
if(getConfig("obvy_activate") == 'yes')
{
	if(getConfig("currency") == 'EUR')
	{
		$phone_block .= '<div class="send-message-block">';
		$phone_block .= '<form action="https://apipartner.obvy-app.com/api/payment?api_key='.getConfig("obvy_api_key").'" method="post" accept-charset="UTF-8" target="_blank">';
		$phone_block .= '<input type="hidden" name="amount" value="'.$prix_nosigle.'">';
		$phone_block .= '<input type="hidden" name="item_name" value="'.$titre.'">';
		$phone_block .= '<input type="hidden" name="currency_code" value="EUR">';
		$phone_block .= '<input type="hidden" name="locale" value="FR">';
		$phone_block .= '<input type="hidden" name="seller_email" value="'.$emailuser.'">';
		$phone_block .= '<input type="hidden" name="url_return" value="'.$url_script.'/showannonce.php?md5='.$md5.'&valid=1">';
		$phone_block .= '<input type="hidden" name="url_cancel" value="'.$url_script.'/showannonce.php?md5='.$md5.'&error=1">';
		$phone_block .= '<button type="submit" class="blueBtn obvy-icon" style="width:100%;"> Paiement sécurisé</button>';
		$phone_block .= '</form>';
		$phone_block .= '</div>';
	}	
}

$class_templateloader->assign("{telephone}",$phone_block);
$class_templateloader->assign("{send_message_link}",$url_script."/envoyez-un-message.php?md5=".$md5);
$class_publicite->updatePublicite($class_templateloader);

$access_website = getConfig("access_website");
if($access_website == 'full' || $access_website == '')
{
	$class_templateloader->show();
}
else
{
	if($isConnected)
	{
		$class_templateloader->show();
	}
	else
	{
		$class_templateloader->loadTemplate("tpl/limited.tpl");
		$class_templateloader->show();
	}
}
include "footer.php";
	
$class_templateloader->closeBody();
$class_templateloader->closeHTML();

?>