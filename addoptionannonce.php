<?php

include "main.php";

$data = NULL;

$titleSEO = getTitleSEO('deposer_une_annonce');
$descriptionSEO = getDescriptionSEO('deposer_une_annonce');

$template = getConfig("template");

$class_templateloader->showHead('deposer_une_annonce');
$class_templateloader->openBody();

include "header.php";

$class_templateloader->loadTemplate("tpl/add_option_annonces.tpl");

$md5 = AntiInjectionSQL($_REQUEST['md5']);
$x = 0;

/* On recupere certaine information de l'annonce */
$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$categorie_annonce = $req['idcategorie'];

/* On check les options disponible */

/* On check si pour cette catégorie l'annonce pourrait être payante */
$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE categorie = $categorie_annonce AND emplacement = 'classic'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	/* Pas de spécification */
}
else
{
	/* Specifique */
}

$total = 0;

/* On check si pour cette catégorie l'annonce pourrait avoir des remonters spécifique */
$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE categorie = $categorie_annonce AND emplacement = 'remonter'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	/* Pas de spécification */
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'remonter' AND categorie = 0";
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" onclick="updatePrice(\''.$x.'\');" name="option" id="option-'.$x.'" value="'.$req['id'].'"> '.$req['nom'];
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<p>';
		$data .= $req['description'];
		$data .= '</p>';
		
		$x++;
	}
}
else
{
	/* Specifique */
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'remonter' AND categorie = $idcategorie";
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" onclick="updatePrice(\''.$x.'\');" name="option" id="option-'.$x.'" value="'.$req['id'].'"> '.$req['nom'];
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<p>';
		$data .= $req['description'];
		$data .= '</p>';
		
		$x++;
	}
}

/* On check si pour cette catégorie l'annonce pourrait avoir des options signaletique */
$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE categorie = $categorie_annonce AND emplacement = 'bandeau'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	/* Pas de spécification */
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'bandeau' AND categorie = 0";
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" onclick="updatePrice(\''.$x.'\');" name="option" id="option-'.$x.'" value="'.$req['id'].'"> '.$req['nom'];
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<p>';
		$data .= $req['description'];
		$data .= '</p>';
		
		$x++;
	}
}
else
{
	/* Specifique */
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'bandeau' AND categorie = $categorie_annonce";
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
		$data .= '<div style="float:left;font-size:18px;">';
		$data .= '<input type="checkbox" onclick="updatePrice(\''.$x.'\');" name="option" id="option-'.$x.'" value="'.$req['id'].'"> '.$req['nom'];
		$data .= '</div>';
		$data .= '<div style="float:right;font-size:18px;">';
		$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<p>';
		$data .= $req['description'];
		$data .= '</p>';
		
		$x++;
	}
}

/* On check si le pack photo à été activer côté user */
/* Faille possible on prend donc le MD5 et on compte le nombre de photo par rapport au gratuite */

$nbr_photo_gratuite = getConfig("nbr_max_photo");

$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$nbr_photo_annonce = $req[0];

if($nbr_photo_annonce > $nbr_photo_gratuite)
{
	$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE categorie = $categorie_annonce AND emplacement = 'photo'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	if($req[0] == 0)
	{
		/* Pas de spécification */
		$SQL = "SELECT * FROM pas_grille_tarif WHERE categorie = 0 AND emplacement = 'photo'";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
			$data .= '<div style="float:left;font-size:18px;">';
			$data .= '<input type="checkbox" name="option" id="option-'.$x.'" value="'.$req['id'].'" checked disabled> '.$req['nom'];
			$data .= '</div>';
			$data .= '<div style="float:right;font-size:18px;">';
			$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
			$data .= '</div>';
			$data .= '</div>';
			$data .= '<p>';
			$data .= $req['description'];
			$data .= '</p>';
			$data .= '<input type="hidden" name="packphoto" value="'.$req['id'].'">';
			
			$total = $total + $req['prix'];			
			
			$x++;
		}
	}
	else
	{
		/* Specifique */
		$SQL = "SELECT * FROM pas_grille_tarif WHERE categorie = $categorie_annonce AND emplacement = 'photo'";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= '<div style="overflow:auto;margin-top: 20px;margin-bottom: 20px;">';
			$data .= '<div style="float:left;font-size:18px;">';
			$data .= '<input type="checkbox" name="option" id="option-'.$x.'" value="'.$req['id'].'" checked disabled> '.$req['nom'];
			$data .= '</div>';
			$data .= '<div style="float:right;font-size:18px;">';
			$data .= 'Prix : '.$class_monetaire->getReturnPrice($req['prix']);
			$data .= '</div>';
			$data .= '</div>';
			$data .= '<p>';
			$data .= $req['description'];
			$data .= '</p>';
			$data .= '<input type="hidden" name="packphoto" value="'.$req['id'].'">';
			
			$total = $total + $req['prix'];	
			
			$x++;
		}
	}
}


/* On check si un prix spécifique à été donner pour une annonce par categorie */
$SQL = "SELECT COUNT(*) FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = $categorie_annonce";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] != 0)
{
	$SQL = "SELECT * FROM pas_grille_tarif WHERE emplacement = 'classic' AND categorie = $categorie_annonce";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$total = $total + $req['prix'];
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
		
		$total = $total + $req['prix'];
	}
	else
	{
		//
	}
}

$total_code = $total;
$total = $class_monetaire->getReturnPrice($total);

$class_templateloader->assign("{md5}",$md5);
$class_templateloader->assign("{cat_annonce}",$categorie_annonce);
$class_templateloader->assign("{option}",$data);
$class_templateloader->assign("{total}",$total);
$class_templateloader->assign("{total_code}",$total_code);
$class_templateloader->show();

?>