<?php

class Module_filtre_auto
{
	function __construct()
	{
	}
	
	function showSearch()
	{
		global $pdo;
		
		?>
		<style>
		.title-prix-entre
		{
			float: left;
			width: 12.5%;
			margin-top: 17px;
			font-weight:bold;
		}
		
		.first-prix-entre
		{
			float: left;
			width: 12.5%;
		}
		
		.title-et-entre
		{
			float: left;
			margin-top: 17px;
			margin-left: 1%;
			margin-right: 1%;
			font-weight: bold;
		}
		
		.w125
		{
			width: 100%;
			height: 35px;
			margin-top: 10px;
			border: 1px solid #ddd;
		}
		
		.spacetitle
		{
			margin-left: 1%;
		}
		
		.englobe-search-filtre-voiture
		{
			overflow:auto;
		}
		</style>
		<div class="englobe-search-filtre-voiture">
			<div class="title-prix-entre">
			Prix entre 
			</div>
			<div class="first-prix-entre">
				<select name="prixA" class="w125">
					<option value="0">Prix mini</option>
					<option value="250">250</option>
					<option value="500">500</option>
					<option value="750">750</option>
					<option value="1000">1000</option>
					<option value="1500">1500</option>
					<option value="2000">2000</option>
					<option value="2500">2500</option>
					<option value="3000">3000</option>
					<option value="3500">3500</option>
					<option value="4000">4000</option>
					<option value="4500">4500</option>
					<option value="5000">5000</option>
					<option value="5500">5500</option>
					<option value="6000">6000</option>
					<option value="6500">6500</option>
					<option value="7000">7000</option>
					<option value="7500">7500</option>
					<option value="8000">8000</option>
					<option value="8500">8500</option>
					<option value="9000">9000</option>
					<option value="9500">9500</option>
					<option value="10000">10000</option>
					<option value="11000">11000</option>
					<option value="12000">12000</option>
					<option value="13000">13000</option>
					<option value="14000">14000</option>
					<option value="15000">15000</option>
					<option value="17500">17500</option>
					<option value="20000">20000</option>
				</select>
			</div>
			<div class="title-et-entre">
			et
			</div>
			<div class="first-prix-entre">
				<select name="prixB" class="w125">
					<option value="0">Prix maxi</option>
					<option value="250">250</option>
					<option value="500">500</option>
					<option value="750">750</option>
					<option value="1000">1000</option>
					<option value="1500">1500</option>
					<option value="2000">2000</option>
					<option value="2500">2500</option>
					<option value="3000">3000</option>
					<option value="3500">3500</option>
					<option value="4000">4000</option>
					<option value="4500">4500</option>
					<option value="5000">5000</option>
					<option value="5500">5500</option>
					<option value="6000">6000</option>
					<option value="6500">6500</option>
					<option value="7000">7000</option>
					<option value="7500">7500</option>
					<option value="8000">8000</option>
					<option value="8500">8500</option>
					<option value="9000">9000</option>
					<option value="9500">9500</option>
					<option value="10000">10000</option>
					<option value="11000">11000</option>
					<option value="12000">12000</option>
					<option value="13000">13000</option>
					<option value="14000">14000</option>
					<option value="15000">15000</option>
					<option value="17500">17500</option>
					<option value="20000">20000</option>
				</select>
			</div>
			<div class="title-prix-entre spacetitle">
				Année entre
			</div>
			<div class="first-prix-entre">
				<select name="anneeA" class="w125">
					<option value="0">Année min</option>
					<?php
					for($x=2018;$x>1959;$x--)
					{
						?>
						<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="title-et-entre">
				et
			</div>
			<div class="first-prix-entre">
				<select name="anneeB" class="w125">
					<option value="0">Année max</option>
					<?php
					for($x=2018;$x>1959;$x--)
					{
						?>
						<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<div class="englobe-search-filtre-voiture">
			<div class="title-prix-entre">
				Kilométrage entre
			</div>
			<div class="first-prix-entre">
				<select name="kilometrageA" class="w125">
					<option value="0">Kilométrage min</option>
					<?php
					for($x=1;$x<20;$x++)
					{
						?>
						<option value="<?php echo $x*10000; ?>"><?php echo number_format($x*10000,0,'',' '); ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="title-et-entre">
				et
			</div>
			<div class="first-prix-entre">
				<select name="kilometrageB" class="w125">
					<option value="0">Kilométrage max</option>
					<?php
					for($x=1;$x<20;$x++)
					{
						?>
						<option value="<?php echo $x*10000; ?>"><?php echo number_format($x*10000,0,'',' '); ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="first-prix-entre spacetitle">
				<select name="marque" id="marque" class="w125" onchange="updateModele();">
					<option value="0">Marques</option>
					<?php
				
					$SQL = "SELECT * FROM pas_filtre_auto_marque";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						?>
						<option value="<?php echo $req['marque']; ?>"><?php echo $req['marque']; ?></option>
						<?php
					}
					
					?>
				</select>
			</div>
			<div class="first-prix-entre spacetitle">
				<select name="modele" id="modele" class="w125" disabled>
					<option value="">Modèle</option>
				</select>
			</div>
			<div class="first-prix-entre spacetitle">
				<select name="energie" class="w125">
					<option value="0">Energie</option>
					<?php
				
					$SQL = "SELECT * FROM pas_filtre_auto_carburant";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						?>
						<option value="<?php echo $req['carburant']; ?>"><?php echo $req['carburant']; ?></option>
						<?php
					}
					
					?>
				</select>
			</div>
			<div class="first-prix-entre spacetitle">
				<select name="boite_vitesse" class="w125">
					<option value="0">Boite de vitesse</option>
					<option value="manuelle">Manuelle</option>
					<option value="automatique">Automatique</option>
				</select>
			</div>
		</div>
		<script>
		function updateModele()
		{
			var marque = $('#marque').val();
			$('#modele').prop('disabled',false);
			$('#modele').load('module/reloadmodele.php?marque='+marque);
		}
		</script>
		<?php
	}
	
	/* Recherche specifique pour les automobile */
	/* Renvoie le nombre d'annonce disponible dans une recherche */
	function setSearchCount($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal,$energie,$marque)
	{
		global $pdo;
		
		if($categorie != '')
		{
			$addcategorie = "AND pas_annonce.idcategorie = $categorie";
		}
		if($categorie == 0)
		{
			$addcategorie = "";
		}
		
		if($energie != '')
		{
			if($energie == 0)
			{
				$addEnergie = '';
			}
			else
			{
				$addEnergie = "AND pas_filtre_auto.carburant = '$energie'";
			}
		}
		else
		{
			$addEnergie = "";
		}
		
		if($marque != '')
		{
			if($marque == 0)
			{
				$addMarque = "";
			}
			else
			{
				$addMarque = "AND pas_filtre_auto.marque = '$marque'";
			}
		}
		else
		{
			$addMarque = "";
		}
		
		if($departement != '')
		{
			$adddepartement = "AND pas_annonce.codepostal like '$departement%'";
		}
		
		if($searchtext != '')
		{
			$searchtext = str_replace("'","''",$searchtext);
			$addsearchtext = "AND pas_annonce.titre like '%$searchtext%'";
		}
		
		if($region != '')
		{
			$SQL = "SELECT * FROM pas_region WHERE idmap = '$region'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$idregion = $req['id'];
			$addregion = "AND pas_annonce.idregion = $idregion";
		}
		
		if($tri == 'prix_croissant')
		{
			$tri = "ORDER BY pas_annonce.prix ASC";
		}
		else if($tri == 'prix_decroissant')
		{
			$tri = "ORDER BY pas_annonce.prix DESC";
		}
		else if($tri == 'plus_recente')
		{
			$tri = "ORDER BY pas_annonce.date_soumission DESC";
		}
		else if($tri == 'plus_ancienne')
		{
			$tri = "ORDER BY pas_annonce.date_soumission ASC";
		}
		else
		{
			$tri = "ORDER BY pas_annonce.date_soumission DESC";
		}
		
		if($codepostal != '')
		{
			$addcodepostal = "AND pas_annonce.codepostal like '%$codepostal%'";
		}
		
		$x = 0;
		
		$SQLCount = "SELECT COUNT(*) FROM pas_annonce,pas_filtre_auto WHERE pas_annonce.valider = 'yes' AND pas_annonce.md5 = pas_filtre_auto.md5 $addcategorie $addsearchtext $addregion $adddepartement $addcodepostal $addEnergie $addMarque $tri";
		$reponse = $pdo->query($SQLCount);
		$req = $reponse->fetch();
		
		//echo "Count = ".$req[0]."<br>";
		
		return $req[0];		
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
	
	function setSearch($page,$searchtext,$categorie,$type,$region,$departement,$tri,$codepostal,$energie,$marque)
	{
		global $pdo;
		
		$nbrAnnonceParPage = $this->getConfig("nbr_annonce_page");
		
		if($categorie != '')
		{
			$addcategorie = "AND pas_annonce.idcategorie = $categorie";
		}
		if($categorie == 0)
		{
			$addcategorie = "";
		}
		
		if($energie != '')
		{
			if($energie == '0')
			{
				$addEnergie = '';
			}
			else
			{
				$addEnergie = "AND pas_filtre_auto.carburant = '$energie'";
			}
		}
		else
		{
			$addEnergie = "";
		}
		
		if($marque != '')
		{
			if($marque == '0')
			{
				$addMarque = "";
			}
			else
			{
				$addMarque = "AND pas_filtre_auto.marque = '$marque'";
			}
		}
		else
		{
			$addMarque = "";
		}
		
		if($departement != '')
		{
			$adddepartement = "AND pas_annonce.codepostal like '$departement%'";
		}
		
		if($searchtext != '')
		{
			$addsearchtext = "AND pas_annonce.titre like '%$searchtext%'";
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
			$addcodepostal = "AND pas_annonce.codepostal like '%$codepostal%'";
		}
		
		$x = 0;
		
		if($tri == 'prix_croissant')
		{
			$tri = "ORDER BY pas_annonce.prix ASC";
		}
		else if($tri == 'prix_decroissant')
		{
			$tri = "ORDER BY pas_annonce.prix DESC";
		}
		else if($tri == 'plus_recente')
		{
			$tri = "ORDER BY pas_annonce.date_soumission DESC";
		}
		else if($tri == 'plus_ancienne')
		{
			$tri = "ORDER BY pas_annonce.date_soumission ASC";
		}
		else
		{
			$tri = "ORDER BY pas_annonce.date_soumission DESC";
		}
		
		$SQL = "SELECT * FROM pas_annonce,pas_filtre_auto WHERE pas_annonce.valider = 'yes' AND pas_annonce.md5 = pas_filtre_auto.md5 AND pas_annonce.offre_demande = '$type' $addcategorie $addsearchtext $addregion $adddepartement $addcodepostal $addEnergie $addMarque $tri LIMIT ".($page*$nbrAnnonceParPage).",$nbrAnnonceParPage";
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
	
	function saveFiltre($md5,$marque,$modele,$annee,$kilometrage,$carburant,$boite_de_vitesse)
	{
		global $pdo;
		
		$SQL = "INSERT INTO pas_filtre_auto (md5,marque,modele,annee,kilometrage,carburant,boite_de_vitesse) VALUES ('$md5','$marque','$modele','$annee','$kilometrage','$carburant','$boite_de_vitesse')";
		$pdo->query($SQL);
	}
	
	function showFormOption()
	{
		global $pdo;
		
		?>
		<label>Marques :</label>
		<select name="marque" id="marque" class="inputbox" onchange="updateModele();">
		<?php
		$SQL = "SELECT * FROM pas_filtre_auto_marque";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			?>
			<option value="<?php echo $req['marque']; ?>"><?php echo $req['marque']; ?></option>
			<?php
		}
		?>
		</select>
		<label>Modèle :</label>
		<select name="modele" class="inputbox" id="modele">
		<?php
		$SQL = "SELECT * FROM pas_filtre_auto_modele WHERE marque = 'Audi'";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			?>
			<option value="<?php echo $req['modele']; ?>"><?php echo $req['modele']; ?></option>
			<?php
		}
		?>
		</select>
		<label>Année modèle :</label>
		<select name="annee_modele" class="inputbox">
		<?php
		for($x=2018;$x>1959;$x--)
		{
			?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
			<?php
		}
		?>
		</select>
		<label>Kilométrage :</label>
		<input type="text" name="kilometrage" value="" class="inputbox">
		<label>Carburant :</label>
		<select name="carburant" class="inputbox">
		<?php
		$SQL = "SELECT * FROM pas_filtre_auto_carburant";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			?>
			<option value="<?php echo $req['carburant']; ?>"><?php echo $req['carburant']; ?></option>
			<?php
		}
		?>
		</select>
		<label>Boîte de vitesse :</label>
		<select name="boite_de_vitesse" class="inputbox">
			<option value="manuelle">Manuelle</option>
			<option value="automatique">Automatique</option>
		</select>
		<script>
		function updateModele()
		{
			var marque = $('#marque').val();
			$('#modele').load('module/reloadmodele.php?marque='+marque);
		}
		</script>
		<?php
		
	}
}

?>