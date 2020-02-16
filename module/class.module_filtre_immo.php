<?php

class Module_filtre_immo
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
				<select name="energie" class="w125">
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
	
	function saveFiltre($md5,$type_de_bien,$surface,$pieces,$classe_energie,$ges)
	{
		global $pdo;
		
		$SQL = "INSERT INTO pas_filtre_immo (md5,type_de_bien,surface,pieces,classe_energie,ges) VALUES ('$md5',$type_de_bien,'$surface','$pieces',$classe_energie,$ges)";
		$pdo->query($SQL);
	}
	
	function showFormOption()
	{
		global $pdo;
		
		?>
		<style>
		.demi-item
		{
			float: left;
			width: 40%;
			margin-right: 10%;
		}
		
		.metre-caree
		{
			float: right;
			margin-top: 18px;
			z-index: 3;
			position: absolute;
			margin-left: -24px;
		}
		</style>
		<label>Type de bien :</label>
		<select name="type_de_bien" id="type_de_bien" class="inputbox">
		<?php
		$SQL = "SELECT * FROM pas_immo_type_bien";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			?>
			<option value="<?php echo $req['id']; ?>"><?php echo $req['titre']; ?></option>
			<?php
		}
		?>
		</select>
		<div class="englobe-demi">
			<div class="demi-item">
				<label>Surface :</label>
				<div class="middle-content">
					<input type="text" name="surface" value="" class="inputbox" required>
					<span class="metre-caree">m²</span>
				</div>
			</div>
			<div class="demi-item">
				<label>Pièces :</label>
				<input type="text" name="pieces" value="" class="inputbox" required>
			</div>
		</div>
		<div class="englobe-demi">
			<div class="demi-item">
				<label>Classe énergie :</label>
				<select name="classe_energie" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_immo_classe_energie";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<option value="<?php echo $req['id']; ?>"><?php echo utf8_encode($req['titre']); ?></option>
					<?php
				}
				
				?>
				</select>
			</div>
			<div class="demi-item">
				<label>GES :</label>
				<select name="ges" class="inputbox">
				<?php
				
				$SQL = "SELECT * FROM pas_immo_ges";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<option value="<?php echo $req['id']; ?>"><?php echo utf8_encode($req['titre']); ?></option>
					<?php
				}
				
				?>
				</select>
			</div>
		</div>
		<?php
		
	}
}

?>