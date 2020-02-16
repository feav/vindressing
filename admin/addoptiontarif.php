<?php
include "../main.php";
//include "../engine/class.map.php";
include "version.php";

//$class_map = new Map();
@session_start();
$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{	
	header("Location: index.php");
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	/* Ajout d'option tarifaire */
	if($action == 1)
	{
		$nom = AntiInjectionSQL($_REQUEST['nom']);
		$description = AntiInjectionSQL($_REQUEST['description']);
		$emplacement = AntiInjectionSQL($_REQUEST['emplacement']);
		$categorie = AntiInjectionSQL($_REQUEST['categorie']);
		$prix = AntiInjectionSQL($_REQUEST['prix']);
		$duree = AntiInjectionSQL($_REQUEST['duree']);
		$remonter_x_jour = AntiInjectionSQL($_REQUEST['remonter_x_jour']);
		$bandeau = AntiInjectionSQL($_REQUEST['bandeau']);
		
		/* Prix normal d'une annonce */
		if($emplacement == 'classic')
		{
			$SQL = "INSERT INTO pas_grille_tarif (nom,description,language,categorie,bandeau,duree,remonter,remonter_x_jour,emplacement,prix) VALUES ('$nom','$description','fr','$categorie','','','','','classic','$prix')";
			$pdo->query($SQL);
			
			header("Location: grilletarifaire.php");
			exit;
		}
		
		/* Prix d'une remonter d'annonce */
		if($emplacement == 'remonter')
		{
			$SQL = "INSERT INTO pas_grille_tarif (nom,description,language,categorie,bandeau,duree,remonter,remonter_x_jour,emplacement,prix) VALUES ('$nom','$description','fr','$categorie','','$duree','yes','$remonter_x_jour','remonter','$prix')";
			$pdo->query($SQL);
			
			header("Location: grilletarifaire.php");
			exit;
		}
		
		/* Prix annonce signaletique */
		if($emplacement == 'b\andeau')
		{
			$SQL = "INSERT INTO pas_grille_tarif (nom,description,language,categorie,bandeau,duree,remonter,remonter_x_jour,emplacement,prix) VALUES ('$nom','$description','fr','$categorie','$bandeau','','','','bandeau','$prix')";
			$pdo->query($SQL);
			
			header("Location: grilletarifaire.php");
			exit;
		}
		
		/* Prix annonce photo */
		if($emplacement == 'photo')
		{
			$SQL = "INSERT INTO pas_grille_tarif (nom,description,language,categorie,bandeau,duree,remonter,remonter_x_jour,emplacement,prix) VALUES ('$nom','$description','fr','$categorie','','','','','photo','$prix')";
			$pdo->query($SQL);
			
			header("Location: grilletarifaire.php");
			exit;
		}
		
		/* Prix annonce à la une */
		if($emplacement == 'alaune')
		{
			$SQL = "INSERT INTO pas_grille_tarif (nom,description,language,categorie,bandeau,duree,remonter,remonter_x_jour,emplacement,prix) VALUES ('$nom','$description','fr','$categorie','','','','','alaune','$prix')";
			$pdo->query($SQL);
			
			header("Location: grilletarifaire.php");
			exit;
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
</head>
<body>
<?php	

include "header.php";
include "sidebar.php";

?>
<div class="container">
	<H1>Ajout d'une nouvelle option tarifaire</H1>
	<div style="margin-bottom:20px;">
		<a href="<?php echo $url_script; ?>/admin/grilletarifaire.php" class="btn blue">Retour</a>
	</div>
	<form method="POST">
		<label><b>Titre de l'option :</b></label>
		<input type="text" name="nom" placeholder="Titre de l'option visible lors la phase d'achat" class="inputbox">
		<label><b>Description de l'option :</b></label>
		<textarea name="description" placeholder="Description de l'option visible lors de la phase d'achat" class="textareabox"></textarea>
		<label><b>Type d'option :</b></label>
		<select name="emplacement" id="emplacement" class="inputbox" onchange="updateOption();">
			<option value="classic">Sans option prix de l'annonce principal</option>
			<option value="remonter">Option avec remonter d'annonce</option>
			<option value="bandeau">Option avec bandeau ou signalitique</option>
			<option value="photo">Option avec photo supplémentaire</option>
			<option value="alaune">Option à la une</option>
		</select>
		<label><b>Catégorie touché par l'option :</b></label>
		<select name="categorie" class="inputbox">
			<?php
			
			echo $class_map->getAllCategorieOption();
			
			?>
		</select>
		<label><b>Prix de l'option :</b></label>
		<input type="text" name="prix" class="inputbox">
		<div id="option-remonter" style="display:none;">
			<H2>Option de remonter d'annonce</H2>
			<label><b>Durée de l'option en jour :</b></label>
			<select name="duree" class="inputbox">
				<?php
				
				for($x=1;$x<366;$x++)
				{
					?>
					<option value="<?php echo $x; ?>"><?php echo $x; ?> jour(s)</option>
					<?php
				}
				
				?>
			</select>
			<label><b>Remonter de l'annonce en haut de la liste tous les X jours :</b></label>
			<select name="remonter_x_jour" class="inputbox">
				<?php
				
				for($x=1;$x<366;$x++)
				{
					?>
					<option value="<?php echo $x; ?>"><?php echo $x; ?> jour(s)</option>
					<?php
				}
				
				?>
			</select>
		</div>
		<div id="option-signaletique" style="display:none;">
			<H2>Signaletique</H2>
			<label><b>Type de bandeau :</b></label>
			<select name="bandeau" class="inputbox">
				<option value="vignette-urgente.png">Urgente</option>
				<option value="vignette-premium.png">Premium</option>
			</select>
		</div>
		<input type="hidden" name="action" value="1">
		<div style="margin-top:10px;">
		<button type="submit" class="btn blue">Ajouter</button>
		</div>
	</form>
	<script>
	function updateOption()
	{
		var emplacement = $('#emplacement').val();
		if(emplacement == 'classic')
		{
			$('#option-remonter').css('display','none');
			$('#option-signaletique').css('display','none');
		}
		if(emplacement == 'remonter')
		{
			$('#option-remonter').css('display','block');
			$('#option-signaletique').css('display','none');
		}
		if(emplacement == 'bandeau')
		{
			$('#option-remonter').css('display','none');
			$('#option-signaletique').css('display','block');
		}
		if(emplacement == 'photo')
		{
			$('#option-remonter').css('display','none');
			$('#option-signaletique').css('display','none');
		}
		if(emplacement == 'alaune')
		{
			$('#option-remonter').css('display','none');
			$('#option-signaletique').css('display','none');
		}
	}
	</script>
</div>
</body>
</html>