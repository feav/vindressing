<?php

/* Classe MAP Shua-Creation.com 2018 */

class Map
{
	var $nameMap;
	
	function __construct()
	{
	}
	
	function loadMap($nameMap)
	{
		$this->nameMap = $nameMap;
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
	
	function getTri($class)
	{
		global $language;
		$data = '<select name="tri" class="'.$class.'">';
		
		if($language == 'fr')
		{
			$data .= '<option value="plus_recente">Tri : Plus récentes</option>';
			$data .= '<option value="plus_ancienne">Tri : Plus ancienne</option>';
			$data .= '<option value="prix_croissant">Tri : Prix croissants</option>';
			$data .= '<option value="prix_decroissant">Tri : Prix décroissants</option>';
		}
		else if($language == 'en')
		{
			$data .= '<option value="plus_recente">Sort: Most recent</option>';
			$data .= '<option value="plus_ancienne">Sort: Older</option>';
			$data .= '<option value="prix_croissant">Sort: Increasing prices</option>';
			$data .= '<option value="prix_decroissant">Sort: Descending prices</option>';
		}
		else if($language == 'it')
		{
			$data .= '<option value="plus_recente">Ordina: Più recente</option>';
			$data .= '<option value="plus_ancienne">Ordina: più vecchio</option>';
			$data .= '<option value="prix_croissant">Ordina: prezzi in aumento</option>';
			$data .= '<option value="prix_decroissant">Ordina: prezzi decrescenti</option>';
		}
		else if($language == 'bg')
		{
			$data .= '<option value="plus_recente">Сортиране: Най-нови</option>';
			$data .= '<option value="plus_ancienne">Сортиране: По-стари</option>';
			$data .= '<option value="prix_croissant">Сортиране: Повишаване на цените</option>';
			$data .= '<option value="prix_decroissant">Сортиране: низходящи цени</option>';
		}
		$data .= '</select>';
		
		return $data;
	}
	
	function getCodePostal($class)
	{
		$data = NULL;
		$data .= '<input type="text" name="codepostal" class="'.$class.'" placeholder="Code postal">';
		return $data;
	}
	
	function getAllRegionOption($class)
	{
		global $pdo;
		
		$type = $this->getConfig("barre_cp_ou_dep");
		$data = NULL;
		
		if($type == 'departement')
		{
			$data = '<select name="departement" class="'.$class.'">';
		}
		else
		{
			$data .= '<select name="region" class="'.$class.'">';
		}
		
		$toute_la_france = $this->getConfig("toute_la_france");
		$data .= '<option value="">'.$toute_la_france.'</option>'."\n";
		
		if($type == 'departement')
		{
			$SQL = "SELECT * FROM pas_departement ORDER BY departement_nom ASC";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$listing_type_recherche = $this->getConfig("listing_type_recherche");
				if($listing_type_recherche == 'ville')
				{
					$data .= '<option value="'.$req['departement_code'].'">'.($req['departement_nom']).'</option>'."\n";
				}
				else if($listing_type_recherche == 'codepostal')
				{
					$data .= '<option value="'.$req['departement_code'].'">'.$req['departement_code'].'</option>'."\n";
				}
				else if($listing_type_recherche == 'codepostalville')
				{
					$data .= '<option value="'.$req['departement_code'].'">'.$req['departement_code'].' - '.($req['departement_nom']).'</option>'."\n";
				}
			}
		}
		else
		{
			$SQL = "SELECT * FROM pas_region ORDER BY titre ASC";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$data .= '<option value="'.$req['idmap'].'">'.($req['titre']).'</option>'."\n";
			}
		}
		
		$data .= '</select>';
		
		return $data;
	}
	
	function getLangue($identifiant,$language)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_langue WHERE identifiant = '$identifiant' AND language = '$language'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['texte'];
	}
	
	function getAllCategorieOptionSelected($categorie)
	{
		global $pdo;
		
		$data = "";
		
		$language = $this->getConfig("langue_principal");
		$toute_les_categories = $this->getLangue("titre_toute_les_categorie",$language);
		
		if($categorie == 0)
		{
			$data .= '<option value="0" selected>'.$toute_les_categories.'</option>'."\n";
		}
		else
		{
			$data .= '<option value="0">'.$toute_les_categories.'</option>'."\n";
		}
		
		$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0 ORDER BY titre ASC";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= '<option value="'.$req['id'].'" disabled style="background-color:#aaaaaa;color:#ffffff;">'.($req['titre']).'</option>'."\n";
			$subcategorie = $req['id'];
			$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = $subcategorie ORDER BY titre ASC";
			$r = $pdo->query($SQL);
			while($rr = $r->fetch())
			{
				if($categorie == $rr['id'])
				{
					$data .= '<option value="'.$rr['id'].'" selected>'.$rr['titre'].'</option>'."\n";
				}
				else
				{
					$data .= '<option value="'.$rr['id'].'">'.$rr['titre'].'</option>'."\n";
				}
			}
		}
		
		return $data;
	}
	
	function getAllCategorieOption()
	{
		global $pdo;
		
		$data = "";
		$categorie = NULL;
		
		$language = $this->getConfig("langue_principal");
		$toute_les_categories = $this->getLangue("titre_toute_les_categorie",$language);
		
		$data .= '<option value="0">'.$toute_les_categories.'</option>'."\n";
		
		$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0 ORDER BY titre ASC";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= '<option value="'.$req['id'].'" disabled style="background-color:#aaaaaa;color:#ffffff;">'.($req['titre']).'</option>'."\n";
			$subcategorie = $req['id'];
			$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = $subcategorie ORDER BY titre ASC";
			$r = $pdo->query($SQL);
			while($rr = $r->fetch())
			{
				if($categorie == $rr['id'])
				{
					$data .= '<option value="'.$rr['id'].'" selected>'.$rr['titre'].'</option>'."\n";
				}
				else
				{
					$data .= '<option value="'.$rr['id'].'">'.$rr['titre'].'</option>'."\n";
				}
			}
		}
		
		return $data;
	}
	
	function showCategorie()
	{
		global $pdo;
		
		$data = "";
		
		$data .= '<div class="container" style="overflow: auto;margin-bottom: 20px;">'."\n";
		
		$cdata = $this->getConfig("nbr_categorie_par_colonne");
		$cdata = explode(",",$cdata);
		
		/* Nombre de categorie par Colonne */
		$arrayShowCat[0] = $cdata[0];
		$arrayShowCat[1] = $cdata[1];
		$arrayShowCat[2] = $cdata[2];
		$arrayShowCat[3] = $cdata[3];
		
		$arrayCategorie = NULL;
		$x = 0;
		$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$count = $x;
			$arrayCategorie[$count]['titre'] = $req['titre'];
			$arrayCategorie[$count]['id'] = $req['id'];
			$arrayCategorie[$count]['pictogram'] = $req['meta_description'];
			$x++;
		}
		
		$nbrCat = 0;
		$nCat = 0;
		for($x=0;$x<count($arrayCategorie);$x++)
		{
			if($nbrCat == 0)
			{
				$data .= '<div class="col4">'."\n";
			}
				$data .= '<div class="col-categorie">'."\n";
				if($this->getConfig("pictogram_categorie") == 'true')
				{
					$data .= '<div class="pictogram-categorie">'.$arrayCategorie[$x]['pictogram'].'</div>'."\n";
				}
				$data .= '<H3>'.$arrayCategorie[$x]['titre'].'</H3>'."\n";
				$data .= '<ul>'."\n";
				
				$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = ".$arrayCategorie[$x]['id'];
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					$url_rewriting = $this->getConfig("url_rewriting");
					if($url_rewriting == 'yes')
					{
						$data .= '<li><a href="categorie-'.$req['slug'].'.html">'.$req['titre'].'</a></li>'."\n";
					}
					else
					{
						$data .= '<li><a href="offre.php?categorie='.$req['id'].'">'.$req['titre'].'</a></li>'."\n";
					}
				}
				
				$data .= '</ul>'."\n";
				$data .= '</div>'."\n";
				$nbrCat++;
			
				if($arrayShowCat[$nCat] == $nbrCat)
				{
					$nbrCat = 0;
					$nCat++;
					$data .= '</div>'."\n";
				}
		}
		
		$data .= '</div>'."\n";
		
		return $data;
	}
	
	/* Affiche la liste des régions en fonction de la carte */
	function showRegionMap()
	{
		global $pdo;
		global $url_script;
		
		$data = "";
		
		$SQL = "SELECT * FROM pas_region ORDER BY titre ASC";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= '<a href="'.$url_script.'/offre.php?region='.$req['idmap'].'" onmouseover="showRegion(\''.$req['idmap'].'\')" onmouseout="downRegion(\''.$req['idmap'].'\');">'.$req['titre'].'</a><br>'."\n";
		}
		
		$data .= '<script>'."\n";
		$data .= 'var oldid;'."\n";
		$data .= 'function showRegion(id)'."\n";
		$data .= '{'."\n";
		$data .= '$("#info-map").hide();'."\n";
		$data .= "$('#'+oldid).css('fill-opacity','1.0');"."\n";
		$data .= "$('#'+id).css('fill-opacity','0.8');"."\n";
		$data .= "oldid = id;"."\n";
		
		$SQL = "SELECT * FROM pas_region";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$idregion = $req['id'];
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE idregion = $idregion AND valider = 'yes'";
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
				
			$count = $rr[0];
			
			$data .= "if(id == '".$req['idmap']."')"."\n";
			$data .= '{'."\n";
			$data .= "title = \"".$req['titre']."<br>".$count." annonce(s)\";"."\n";
			$data .= '}'."\n";
		}
		
		$data .= '$("#info-map").html(title);'."\n";
		$data .= 'var width = $("#info-map").outerWidth();'."\n";
		$data .= '$("#info-map").css({left:$(\'#\'+id).position().left-($(\'#\'+id).width()/2), top:$(\'#\'+id).position().top-($(\'#\'+id).height()/2)});'."\n";
		$data .= '$("#info-map").show();'."\n";
		$data .= '}'."\n";
		$data .= '</script>'."\n";
		
		return $data;
	}
	
	/* Affiche la carte interactive en SVG */
	function showMap()
	{
		global $pdo;
		
		$this->nameMap = $this->getConfig("map_active");
		$color_carte = $this->getConfig("color_carte");
		
		$data = "";
		
		$data .= '<style>'."\n";
		$data .= '#francemap path'."\n";
		$data .= '{'."\n";
		$data .= 'fill: #'.$color_carte.' !important;'."\n";
		$data .= 'fill-opacity: 1.0;'."\n";
		$data .= '}'."\n";
		
		$data .= '#svg8'."\n";
		$data .= '{'."\n";
		$data .= 'width:100%;'."\n";
		$data .= 'height:auto;'."\n";
		$data .= '}'."\n";
		
		$data .= '#francemap path:hover'."\n";
		$data .= '{'."\n";
		$data .= 'fill-opacity: 0.8;'."\n";
		$data .= 'transition: fill 0.5s;'."\n";
		$data .= '}'."\n";
		$data .= '</style>'."\n";
		$data .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>'."\n";
		
		$map = file_get_contents("map/".$this->nameMap);

		$data .= '<div id="francemap" style="width: 100%; height: auto;margin-top: -100px;">'."\n";
		$data .= $map;
		$data .= '<div id="info-map" style="position:absolute;display:none;background-color: rgba(0,0,0,0.8);padding: 5px;border-radius: 5px;color: #ffffff;font-size: 10px;text-align:center;">'."\n";
		$data .= 'info'."\n";
		$data .= '</div>'."\n";
		$data .= '</div>'."\n";
		$data .= '<script>'."\n";
		$data .= '$("#francemap path").on("click touchstart",function()'."\n";
		$data .= '{'."\n";
		$data .= "var idregion = $(this).attr('id');"."\n";
		$data .= "if(idregion != 'LAC')"."\n";
		$data .= "{"."\n";
		$data .= "document.location.href = 'offre.php?region='+idregion;"."\n";
		$data .= '}'."\n";
		$data .= '});'."\n";
		$data .= '</script>'."\n";
		$data .= '<script>'."\n";
		$data .= '$("#francemap path").mousemove(function(e)'."\n";
		$data .= '{'."\n";
		$data .= 'var $id = $(this).attr(\'id\');'."\n";
		$data .= 'var $title = \'Non configurer\';'."\n";
		
		$SQL = "SELECT * FROM pas_region";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$idregion = $req['id'];
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE idregion = $idregion AND valider = 'yes'";
			$r = $pdo->query($SQL);
			$rr = $r->fetch();
				
			$count = $rr[0];
			
			$data .= 'if($id == \''.$req['idmap'].'\')'."\n";
			$data .= '{'."\n";
			$data .= '$title = "'.ucfirst($req['titre']).'<br>'.$count.' annonce(s)";'."\n";
			$data .= '}'."\n";
		}
			
		$data .= '$("#info-map").html($title);'."\n";
		$data .= 'var width = $("#info-map").outerWidth();'."\n";
		$data .= '$("#info-map").css({left:e.pageX-(width/2) , top:e.pageY-50});'."\n";
		$data .= '$("#info-map").show();'."\n";
		$data .= '});'."\n";
				
		$data .= '$("#francemap path").mouseout(function(e)'."\n";
		$data .= '{'."\n";
		$data .= '$("#info-map").hide();'."\n";
		$data .= '});'."\n";
		$data .= '</script>'."\n";
		
		return $data;
	}
}

?>