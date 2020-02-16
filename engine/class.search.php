<?php

/* Classe de recherche PAS Script Shua-Creation.com 2018 */

class Search
{
	function __construct()
	{
	}
	
	/* Fonction globale de renvoie de config */
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	/* Renvoie le nombre d'annonce par page */
	function getTotalPage($count)
	{
		$nbrAnnonceParPage = $this->getConfig("nbr_annonce_page");
		$totalPage = ceil($count / $nbrAnnonceParPage);
		return $totalPage;
	}
	
	/* Dernière annonces */
	function getLastAnnonce($number)
	{
		global $pdo;
		
		$x = 0;
		
		$SQL = "SELECT * FROM pas_annonce WHERE valider = 'yes' ORDER BY id DESC LIMIT $number";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$md5 = $req['md5'];
			
			$data[$x]['iduser'] = $req['iduser'];
			$data[$x]['idregion'] = $req['idregion'];
			$data[$x]['md5'] = $md5;
			$data[$x]['titre'] = $req['titre'];
			$data[$x]['prix'] = $req['prix'];
			$data[$x]['codepostal'] = $req['codepostal'];
			$data[$x]['urgente'] = $req['urgente'];
			
			/* Titre de la région */
			$SQL = "SELECT * FROM pas_region WHERE id = ".$req['idregion'];
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$data[$x]['titreRegion'] = $rr['titre'];
			
			$idcategorie = $req['idcategorie'];
			// Recupere le titre de la categorie
			$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
		
			$titrecat = $_req['titre'];
			$data[$x]['titreCategorie'] = $titrecat;
			
			// On check si des images existe
			$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
			$countphoto = $_req[0];
			
			$data[$x]['countphoto'] = $countphoto;
			
			if($_req[0] != 0)
			{
				$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
				$r = $pdo->query($SQL);
				$_req = $r->fetch();
				
				$image = $_req['image'];
				$extension = explode(".",$image);
				$extension = $extension[count($extension)-1];
				$imagethumbpath = str_replace(".".$extension,"",$image);
				$imagethumbpath = $imagethumbpath."-thumb.".$extension;
				
				$img = 'annonce/'.$imagethumbpath;
			}
			else
			{
				$img = 'noimage.jpg';
			}
			
			$data[$x]['image'] = $img;
			
			$url_rewriting = $this->getConfig("url_rewriting");
			if($url_rewriting == 'yes')
			{
				$linkAnnonce = "/".$req['md5']."/annonce-".$req['slug'].".html";
			}
			else
			{
				$linkAnnonce = "showannonce.php?md5=".$req['md5'];
			}
			
			$data[$x]['link'] = $linkAnnonce;
			
			$date_soumission = $req['date_soumission'];
			$date_soumission = explode(" ",$date_soumission);
			$date_de_soumission = $date_soumission[0];
			$heure_de_soumission = $date_soumission[1];
			
			$date = explode("-",$date_de_soumission);
			$annee_soumission = $date[0];
			$mois_soumission = $date[1];
			$jour_soumission = $date[2];
			
			/* Si il s'agit de la date du jour */
			$now   = time();
			$date2 = strtotime($req['date_soumission']);
			$date = dateDiff($now, $date2);
			
			if($date['day'] == 0)
			{
				if($date['hour'] == 0)
				{
					$date_annonce = "Il y'a ".$date['minute']." min";
				}
				else
				{
					$date_annonce = "Il y'a ".$date['hour']."h ".$date['minute']." min";
				}
			}
			else
			{
				$date_annonce = "$jour_soumission/$mois_soumission/$annee_soumission à $heure_de_soumission";
			}
			
			$data[$x]['date'] = $date_annonce;
			
			$x++;
		}		
		return $data;
	}
	
	function setSearchBoutiqueOnlyCount($iduser,$searchtext)
	{
		global $pdo;
		
		$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes' AND iduser = $iduser";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req[0];
	}
	
	function setSearchBoutiqueOnly($iduser,$page,$searchtext)
	{
		global $pdo;
		
		$nbrAnnonceParPage = $this->getConfig("nbr_annonce_page");
		
		$data = NULL;
		$x = 0;
		
		$SQL = "SELECT * FROM pas_annonce WHERE valider = 'yes' AND iduser = $iduser LIMIT ".$page*$nbrAnnonceParPage.",$nbrAnnonceParPage";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$md5 = $req['md5'];
			
			$data[$x]['iduser'] = $req['iduser'];
			$data[$x]['idregion'] = $req['idregion'];
			$data[$x]['md5'] = $md5;
			$data[$x]['titre'] = $req['titre'];
			$data[$x]['prix'] = $req['prix'];
			$data[$x]['codepostal'] = $req['codepostal'];
			$data[$x]['urgente'] = $req['urgente'];
			$data[$x]['pro'] = $req['pro'];
			
			/* Titre de la région */
			$SQL = "SELECT * FROM pas_region WHERE id = ".$req['idregion'];
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$data[$x]['titreRegion'] = $rr['titre'];
			
			$idcategorie = $req['idcategorie'];
			// Recupere le titre de la categorie
			$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
		
			$titrecat = $_req['titre'];
			$data[$x]['titreCategorie'] = $titrecat;
			
			// On check si des images existe
			$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
			$countphoto = $_req[0];
			
			$data[$x]['countphoto'] = $countphoto;
			
			if($_req[0] != 0)
			{
				$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
				$r = $pdo->query($SQL);
				$_req = $r->fetch();
				
				$image = $_req['image'];
				$extension = explode(".",$image);
				$extension = $extension[count($extension)-1];
				$imagethumbpath = str_replace(".".$extension,"",$image);
				$imagethumbpath = $imagethumbpath."-thumb.".$extension;
				
				$img = 'annonce/'.$imagethumbpath;
			}
			else
			{
				$img = 'noimage.jpg';
			}
			
			$data[$x]['image'] = $img;
			
			$url_rewriting = $this->getConfig("url_rewriting");
			if($url_rewriting == 'yes')
			{
				$linkAnnonce = "/".$req['md5']."/annonce-".$req['slug'].".html";
			}
			else
			{
				$linkAnnonce = "showannonce.php?md5=".$req['md5'];
			}
			
			$data[$x]['link'] = $linkAnnonce;
			
			$date_soumission = $req['date_soumission'];
			/*$date_soumission = explode(" ",$date_soumission);
			$date_de_soumission = $date_soumission[0];
			$heure_de_soumission = $date_soumission[1];
			
			$date = explode("-",$date_de_soumission);
			$annee_soumission = $date[0];
			$mois_soumission = $date[1];
			$jour_soumission = $date[2];*/
			
			/* Si il s'agit de la date du jour */
			/*$now   = time();
			$date2 = strtotime($req['date_soumission']);
			$date = dateDiff($now, $date2);
			
			if($date['day'] == 0)
			{
				if($date['hour'] == 0)
				{
					$date_annonce = "Il y'a ".$date['minute']." min";
				}
				else
				{
					$date_annonce = "Il y'a ".$date['hour']."h ".$date['minute']." min";
				}
			}
			else
			{
				$date_annonce = "$jour_soumission/$mois_soumission/$annee_soumission à $heure_de_soumission";
			}*/
			
			$data[$x]['date'] = $date_soumission;
			
			$x++;
		}		
		return $data;
		
	}
	
	/* Renvoie les favoris de l'utilisateur */
	function getFavoris($array)
	{
		global $pdo;
		
		$x = 0;
		for($y=0;$y<count($array);$y++)
		{		
			$md5 = $array[$y];
			$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{			
				$md5 = $req['md5'];
				
				$data[$x]['iduser'] = $req['iduser'];
				$data[$x]['idregion'] = $req['idregion'];
				$data[$x]['md5'] = $md5;
				$data[$x]['titre'] = $req['titre'];
				$data[$x]['prix'] = $req['prix'];
				$data[$x]['codepostal'] = $req['codepostal'];
				$data[$x]['urgente'] = $req['urgente'];
				$data[$x]['pro'] = $req['pro'];
				
				/* Titre de la région */
				$SQL = "SELECT * FROM pas_region WHERE id = ".$req['idregion'];
				$r = $pdo->query($SQL);
				$rr = $r->fetch();
				
				$data[$x]['titreRegion'] = $rr['titre'];
				
				$idcategorie = $req['idcategorie'];
				// Recupere le titre de la categorie
				$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
				$r = $pdo->query($SQL);
				$_req = $r->fetch();
			
				$titrecat = $_req['titre'];
				$data[$x]['titreCategorie'] = $titrecat;
				
				// On check si des images existe
				$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
				$r = $pdo->query($SQL);
				$_req = $r->fetch();
				$countphoto = $_req[0];
				
				$data[$x]['countphoto'] = $countphoto;
				
				if($_req[0] != 0)
				{
					$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
					$r = $pdo->query($SQL);
					$_req = $r->fetch();
					
					$image = $_req['image'];
					$extension = explode(".",$image);
					$extension = $extension[count($extension)-1];
					$imagethumbpath = str_replace(".".$extension,"",$image);
					$imagethumbpath = $imagethumbpath."-thumb.".$extension;
					
					$img = 'annonce/'.$imagethumbpath;
				}
				else
				{
					$img = 'noimage.jpg';
				}
				
				$data[$x]['image'] = $img;
				
				$url_rewriting = $this->getConfig("url_rewriting");
				if($url_rewriting == 'yes')
				{
					$linkAnnonce = "/".$req['md5']."/annonce-".$req['slug'].".html";
				}
				else
				{
					$linkAnnonce = "showannonce.php?md5=".$req['md5'];
				}
				
				$data[$x]['link'] = $linkAnnonce;			
				$date_soumission = $req['date_soumission'];			
				$data[$x]['date'] = $date_soumission;
				
				$x++;
			}
		
		}
		
		return $data;
	}
	
	/* Renvoie le nombre d'annonce valider d'un utilisateur */
	function setSearchUserCount($user)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_user WHERE username = '$user'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$iduser = $req['id'];
		
		$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE iduser = $iduser AND valider = 'yes'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req[0];
	}
	
	/* Recherche des annonces d'un utilisateur */
	function setSearchUser($page,$user)
	{
		global $pdo;
		global $url_script;
		
		$x = 0;
		$nbrAnnonceParPage = $this->getConfig("nbr_annonce_page");
		
		$SQL = "SELECT * FROM pas_user WHERE username = '$user'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$iduser = $req['id'];
		
		$SQL = "SELECT * FROM pas_annonce WHERE iduser = $iduser AND valider = 'yes' ORDER BY id DESC";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{			
			$md5 = $req['md5'];
			
			$data[$x]['iduser'] = $req['iduser'];
			$data[$x]['idregion'] = $req['idregion'];
			$data[$x]['md5'] = $md5;
			$data[$x]['titre'] = $req['titre'];
			$data[$x]['prix'] = $req['prix'];
			$data[$x]['codepostal'] = $req['codepostal'];
			$data[$x]['urgente'] = $req['urgente'];
			$data[$x]['pro'] = $req['pro'];
			
			/* Titre de la région */
			$SQL = "SELECT * FROM pas_region WHERE id = ".$req['idregion'];
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$data[$x]['titreRegion'] = $rr['titre'];
			
			$idcategorie = $req['idcategorie'];
			// Recupere le titre de la categorie
			$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
		
			$titrecat = $_req['titre'];
			$data[$x]['titreCategorie'] = $titrecat;
			
			// On check si des images existe
			$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
			$countphoto = $_req[0];
			
			$data[$x]['countphoto'] = $countphoto;
			
			if($_req[0] != 0)
			{
				$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
				$r = $pdo->query($SQL);
				$_req = $r->fetch();
				
				$image = $_req['image'];
				$extension = explode(".",$image);
				$extension = $extension[count($extension)-1];
				$imagethumbpath = str_replace(".".$extension,"",$image);
				$imagethumbpath = $imagethumbpath."-thumb.".$extension;
				
				$img = 'annonce/'.$imagethumbpath;
			}
			else
			{
				$img = 'noimage.jpg';
			}
			
			$data[$x]['image'] = $img;
			
			$url_rewriting = $this->getConfig("url_rewriting");
			if($url_rewriting == 'yes')
			{
				$linkAnnonce = "/".$req['md5']."/annonce-".$req['slug'].".html";
			}
			else
			{
				$linkAnnonce = "showannonce.php?md5=".$req['md5'];
			}
			
			$data[$x]['link'] = $linkAnnonce;			
			$date_soumission = $req['date_soumission'];			
			$data[$x]['date'] = $date_soumission;
			
			$x++;
		}
		
		return $data;
	}
	
	/* Count des boutiques */
	function setSearchBoutiqueCount($page,$searchtext,$categorie,$region,$departement,$tri,$codepostal)
	{
		global $pdo;
		
		if($categorie != '')
		{
			$addcategorie = "AND pas_compte_pro.categorie = $categorie";
		}
		if($categorie == 0)
		{
			$addcategorie = "";
		}
		
		if($searchtext != '')
		{
			$addsearchtext = "AND pas_user.username like '%$searchtext%'";
		}
		
		$SQL = "SELECT COUNT(*) FROM pas_compte_pro INNER JOIN pas_user ON pas_compte_pro.md5 = pas_user.md5 AND pas_compte_pro.visible = 'yes' $addcategorie $addsearchtext";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req[0];
	}
	
	/* Recherche d'une boutique */
	function setSearchBoutique($page,$searchtext,$categorie,$region,$departement,$tri,$codepostal)
	{
		global $pdo;
		global $url_script;
		
		$x = 0;
		$data = NULL;
		
		$nbrAnnonceParPage = $this->getConfig("nbr_annonce_page");
		
		if($categorie != '')
		{
			$addcategorie = "AND pas_compte_pro.categorie = $categorie";
		}
		if($categorie == 0)
		{
			$addcategorie = "";
		}
		
		if($searchtext != '')
		{
			$addsearchtext = "AND pas_user.username like '%$searchtext%'";
		}
		
		$SQL = "SELECT * FROM pas_compte_pro INNER JOIN pas_user ON pas_compte_pro.md5 = pas_user.md5 AND pas_compte_pro.visible = 'yes' $addcategorie $addsearchtext LIMIT ".($page*$nbrAnnonceParPage).",$nbrAnnonceParPage";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes' AND iduser = ".$req['id'];
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$data[$x]['count'] = $rr[0];
			$data[$x]['link'] = $url_script.'/showboutique.php?md5='.$req['md5'];
			$data[$x]['username'] = $req['username'];
			$data[$x]['adresse'] = $req['adresse'];
			$data[$x]['logo'] = $req['logo'];
			$data[$x]['slogan'] = $req['slogan'];
			
			$SQL = "SELECT * FROM pas_categorie WHERE id = ".$req['categorie'];
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$data[$x]['categorie'] = $rr['titre'];
			$x++;
		}
		
		return $data;
	}
	
	/* Renvoie le nombre d'annonce disponible dans une recherche */
	function setSearchCount($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal)
	{
		global $pdo;
		
		$addsearchtext = NULL;
		$addregion = NULL;
		$adddepartement = NULL;
		$addcodepostal = NULL;
		
		if($categorie != '')
		{
			$addcategorie = "AND idcategorie = $categorie";
		}
		if($categorie == 0)
		{
			$addcategorie = "";
		}
		
		if($departement != '')
		{
			$adddepartement = "AND codepostal like '$departement%'";
		}
		
		if($searchtext != '')
		{
			$addsearchtext = "AND titre like '%$searchtext%'";
		}
		
		if($region != '')
		{
			$SQL = "SELECT * FROM pas_region WHERE idmap = '$region'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$idregion = $req['id'];
			$addregion = "AND idregion = $idregion";
		}
		
		if($tri == 'prix_croissant')
		{
			$tri = "ORDER BY prix ASC";
		}
		else if($tri == 'prix_decroissant')
		{
			$tri = "ORDER BY prix DESC";
		}
		else if($tri == 'plus_recente')
		{
			$tri = "ORDER BY date_soumission DESC";
		}
		else if($tri == 'plus_ancienne')
		{
			$tri = "ORDER BY date_soumission ASC";
		}
		else
		{
			$tri = "ORDER BY date_soumission DESC";
		}
		
		if($codepostal != '')
		{
			$addcodepostal = "AND codepostal like '%$codepostal%'";
		}
		
		$x = 0;
		
		$SQLCount = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes' AND status != 'NOVISIBLE' AND offre_demande = '$type' $addcategorie $addsearchtext $addregion $adddepartement $addcodepostal $tri";
		$reponse = $pdo->query($SQLCount);
		$req = $reponse->fetch();
		
		return $req[0];		
	}
	
	function setSearch($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal)
	{
		global $pdo;
		
		$nbrAnnonceParPage = $this->getConfig("nbr_annonce_page");
		$addsearchtext = NULL;
		$addregion = NULL;
		$adddepartement = NULL;
		$addcodepostal = NULL;
		$data = NULL;
		
		if($categorie != '')
		{
			$addcategorie = "AND idcategorie = $categorie";
		}
		if($categorie == 0)
		{
			$addcategorie = "";
		}
		
		if($departement != '')
		{
			$adddepartement = "AND codepostal like '$departement%'";
		}
		
		if($searchtext != '')
		{
			$addsearchtext = "AND titre like '%$searchtext%'";
		}
		
		if($region != '')
		{
			$SQL = "SELECT * FROM pas_region WHERE idmap = '$region'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$idregion = $req['id'];
			$addregion = "AND idregion = $idregion";
		}
		
		if($codepostal != '')
		{
			$addcodepostal = "AND codepostal like '%$codepostal%'";
		}
		
		$x = 0;
		
		if($tri == 'prix_croissant')
		{
			$tri = "ORDER BY prix ASC";
		}
		else if($tri == 'prix_decroissant')
		{
			$tri = "ORDER BY prix DESC";
		}
		else if($tri == 'plus_recente')
		{
			$tri = "ORDER BY date_soumission DESC";
		}
		else if($tri == 'plus_ancienne')
		{
			$tri = "ORDER BY date_soumission ASC";
		}
		else
		{
			$tri = "ORDER BY date_soumission DESC";
		}
		
		$SQL = "SELECT * FROM pas_annonce WHERE valider = 'yes' AND status != 'NOVISIBLE' AND offre_demande = '$type' $addcategorie $addsearchtext $addregion $adddepartement $addcodepostal $tri LIMIT ".($page*$nbrAnnonceParPage).",$nbrAnnonceParPage";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$md5 = $req['md5'];
			
			$data[$x]['iduser'] = $req['iduser'];
			$data[$x]['idregion'] = $req['idregion'];
			$data[$x]['md5'] = $md5;
			$data[$x]['titre'] = $req['titre'];
			$data[$x]['prix'] = $req['prix'];
			$data[$x]['codepostal'] = $req['codepostal'];
			$data[$x]['urgente'] = $req['urgente'];
			$data[$x]['pro'] = $req['pro'];
			
			/* Titre de la région */
			$SQL = "SELECT * FROM pas_region WHERE id = ".$req['idregion'];
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
			
			$data[$x]['titreRegion'] = $rr['titre'];
			
			$idcategorie = $req['idcategorie'];
			// Recupere le titre de la categorie
			$SQL = "SELECT * FROM pas_categorie WHERE id = $idcategorie";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
		
			$titrecat = $_req['titre'];
			$data[$x]['titreCategorie'] = $titrecat;
			
			// On check si des images existe
			$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
			$r = $pdo->query($SQL);
			$_req = $r->fetch();
			$countphoto = $_req[0];
			
			$data[$x]['countphoto'] = $countphoto;
			
			if($_req[0] != 0)
			{
				$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
				$r = $pdo->query($SQL);
				$_req = $r->fetch();
				
				$image = $_req['image'];
				$extension = explode(".",$image);
				$extension = $extension[count($extension)-1];
				$imagethumbpath = str_replace(".".$extension,"",$image);
				$imagethumbpath = $imagethumbpath."-thumb.".$extension;
				
				$img = 'annonce/'.$imagethumbpath;
			}
			else
			{
				$img = 'noimage.jpg';
			}
			
			$data[$x]['image'] = $img;
			
			$url_rewriting = $this->getConfig("url_rewriting");
			if($url_rewriting == 'yes')
			{
				$linkAnnonce = "/".$req['md5']."/annonce-".$req['slug'].".html";
			}
			else
			{
				$linkAnnonce = "showannonce.php?md5=".$req['md5'];
			}
			
			$data[$x]['link'] = $linkAnnonce;
			
			$date_soumission = $req['date_soumission'];
			/*$date_soumission = explode(" ",$date_soumission);
			$date_de_soumission = $date_soumission[0];
			$heure_de_soumission = $date_soumission[1];
			
			$date = explode("-",$date_de_soumission);
			$annee_soumission = $date[0];
			$mois_soumission = $date[1];
			$jour_soumission = $date[2];
			*/
			/* Si il s'agit de la date du jour */
			/*$now   = time();
			$date2 = strtotime($req['date_soumission']);
			$date = dateDiff($now, $date2);
			
			if($date['day'] == 0)
			{
				if($date['hour'] == 0)
				{
					$date_annonce = "Il y'a ".$date['minute']." min";
				}
				else
				{
					$date_annonce = "Il y'a ".$date['hour']."h ".$date['minute']." min";
				}
			}
			else
			{
				$date_annonce = "$jour_soumission/$mois_soumission/$annee_soumission à $heure_de_soumission";
			}*/
			
			$data[$x]['date'] = $date_soumission;
			
			$x++;
		}		
		return $data;
	}
}

?>