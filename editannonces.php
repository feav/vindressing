<?php

include "main.php";

$md5 = $_REQUEST['md5'];

$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$count = $req[0];

if($count == 0)
{
	header("Location: 404.php");
	exit;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$md5 = $_REQUEST['md5'];
		$categorie = $_REQUEST['categorie'];
		$type_annonce = $_REQUEST['type_annonce'];
		$titre = $_REQUEST['titre'];
		$texte = $_REQUEST['texte'];
		$prix = $_REQUEST['prix'];
		$telephone = $_REQUEST['telephone'];
		$codepostal = $_REQUEST['codepostal'];
		$ville = $_REQUEST['ville'];
		
		$telephone = str_replace(" ","",$telephone);
		$telephone = str_replace(".","",$telephone);
		
		$texte = nl2br($texte);
		
		$titre = strip_tags($titre);
		$texte = strip_tags($texte);
		$titre = str_replace("'","''",$titre);
		$texte = str_replace("'","''",$texte);
		
		$SQL = "UPDATE pas_annonce SET titre = '$titre' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET texte = '$texte' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET offre_demande = '$type_annonce' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET idcategorie = $categorie WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET prix = '$prix' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET telephone = '$telephone' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET codepostal = '$codepostal' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_annonce SET ville = '$ville' WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		/* On check l'etat de l'annonce si expired pas de devalidation */
		$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		if($req['valider'] == 'expired')
		{
			/* Rien */	
		}
		else
		{
			$SQL = "UPDATE pas_annonce SET valider = 'no' WHERE md5 = '$md5'";
			$pdo->query($SQL);
		}
		
		$SQL = "UPDATE pas_annonce SET date_soumission = NOW() WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		header("Location: mesannonces.php");
		exit;
	}
}

$titleSEO = getTitleSEO('mes_annonces');
$descriptionSEO = getDescriptionSEO('mes_annonces');

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
	<link rel="stylesheet" type="text/css" href="template/<?php echo $template; ?>/css/main.css">
	<link href="css/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	
	$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	?>
	<div class="container topMargin" style="margin-bottom: 90px;">
		<a href="mesannonces.php" class="btnConfirm">Retour à Mes Annonces</a>
		<H1>Modification de l'annonces "<?php echo $req['titre']; ?>"</H1>
		<form method="POST" onsubmit="return checkForm();" enctype="multipart/form-data">
		<label>Categorie :</label>
		<select name="categorie" class="selectbox">
		<?php
		
		echo $class_map->getAllCategorieOptionSelected($req['idcategorie']);
		
		?>
		</select>
		<label>Type d'annonce :</label><br><br>
		<?php
		if($req['offre_demande'] == 'offre')
		{
			?>
			<input type="radio" name="type_annonce" value="offre" checked> Offres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="type_annonce" value="demande"> Demandes<br><br>
			<?php
		}
		else
		{
			?>
			<input type="radio" name="type_annonce" value="offre"> Offres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="type_annonce" value="demande" checked> Demandes<br><br>
			<?php
		}
		?>
		<label>Titre de l'annonce :</label>
		<input type="text" id="titre" name="titre" value="<?php echo $req['titre']; ?>" class="inputbox">
		<label>Texte de l'annonce :</label>
		<textarea id="texte" name="texte" class="textbox"><?php echo $req['texte']; ?></textarea>
		<label>Prix :</label>
		<?php
					
		$currencysigle = $class_monetaire->getSigle();
					
		?>
		<div class="info">
		Le prix doit contenir un nombre entier pas de virgule, ni de points.
		</div>
		<input type="text" id="prix" name="prix" value="<?php echo $req['prix']; ?>" class="inputbox price"> <b><?php echo $currencysigle; ?></b><br>
		<label>Numéro de téléphone :</label>
		<div id="errorphone"></div>
		<input type="text" name="telephone" id="telephone" value="<?php echo $req['telephone']; ?>" class="inputbox">
		<b>Photos :</b><br><br>
		<div style="overflow:auto;margin-bottom:10px;overflow-x: hidden;overflow-y: hidden;">
		<?php
					
		$md5 = $req['md5'];
		$class_image_uploader->showUploader(getConfig("nbr_max_photo"),192,149,$md5);
		
		?>
		</div>
		<input type="hidden" name="action" value="1">
		<label>Ville ou code postal</label>
		<input type="text" id="codepostal" autocomplete="off" onkeyup="changeCodePostal();" value="<?php echo $req['codepostal']." - ".$req['ville']; ?>" class="inputbox">
		<div id="codepostalshow" class="codepostalshow">
			<center><img src="images/loader.gif"></center>
		</div>
		<input type="hidden" name="md5" value="<?php echo $md5; ?>">
		<input type="hidden" name="ville" id="ville">
		<input type="hidden" name="codepostal" id="new_codepostal">
		<input type="hidden" name="idregion" id="idregion">
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="submit" value="Modifier" class="btnConfirm">
		</div>
	</form>
	<script>
	function isNumeric(n) 
	{
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
	
	function changeCodePostal()
	{
		var code = $('#codepostal').val();
		if(code.length > 1)
		{
			$('#codepostalshow').css('display','block');
			$('#codepostalshow').html('<center><img src="<?php echo $url_script; ?>/images/loader.gif"></center>');
			if(isNumeric(code))
			{
				$('#codepostalshow').load('<?php echo $url_script; ?>/updateCP.php?code='+code);
			}
		}
	}
	
	function updateText(cp,ville,idregion)
	{
		<?php
		$listing_type_recherche = getConfig("listing_type_recherche");
		if($listing_type_recherche == 'ville')
		{
			?>
			$('#codepostal').val(ville);
			<?php
		}
		else if($listing_type_recherche == 'codepostal')
		{
			?>
			$('#codepostal').val(cp);
			<?php
		}
		else if($listing_type_recherche == 'codepostalville')
		{
			?>
			$('#codepostal').val(cp+' - '+ville);
			<?php
		}
		?>
		$('#ville').val(ville);
		$('#idregion').val(idregion);
		$('#new_codepostal').val(cp);
		$('#codepostalshow').css('display','none');
	}
	
	function checkForm()
	{
		var error = 0;
		var titre = $('#titre').val();
		var texte = $('#texte').val();
		var prix = $('#prix').val();
		var codepostal = $('#tags').val();
		var telephone = $('#telephone').val();
		
		$('#titre').css('border','1px solid #dddddd');
		$('#texte').css('border','1px solid #dddddd');
		$('#prix').css('border','1px solid #dddddd');
		$('#tags').css('border','1px solid #dddddd');
		$('#telephone').css('border','1px solid #dddddd');
		$('#errorphone').html('');
		
		if(titre == '')
		{
			error = 1;
			$('#titre').css('border','1px solid #ff0000');
		}
		
		if(texte == '')
		{
			error = 1;
			$('#texte').css('border','1px solid #ff0000');
		}
		
		if(prix == '')
		{
			error = 1;
			$('#prix').css('border','1px solid #ff0000');
		}
		
		if(!isNumeric(prix))
		{
			error = 1;
			$('#prix').css('border','1px solid #ff0000');
		}
		
		if(prix.indexOf(".") !=-1) 
		{
			error = 1;
			$('#prix').css('border','1px solid #ff0000');
		}
		
		if(prix.indexOf(",") !=-1) 
		{
			error = 1;
			$('#prix').css('border','1px solid #ff0000');
		}
		
		if(codepostal == '')
		{
			error = 1;
			$('#tags').css('border','1px solid #ff0000');
		}
		
		<?php
					
		$nbr_chiffre_numero = getConfig("nbr_numero");
					
		?>
		if(telephone != '')
		{
			if(!isNumeric(telephone))
			{
				error = 1;
				$('#telephone').css('border','1px solid #ff0000');
				$('#errorphone').html('<font color=red><b>Le numéro de téléphone ne doit comporter que des chiffres</b></font>');
			}
			
			<?php
			if(getConfig("activate_check_telephone") == 'yes')
			{
			?>
			if(telephone.length < <?php echo $nbr_chiffre_numero; ?>)
			{
				error = 1;
				$('#telephone').css('border','1px solid #ff0000');
				$('#errorphone').html('<font color=red><b><?php echo str_replace("'","\'",$class_menu->getLangue("erreur_nombre_numero",$language)); ?></b></font>');
			}
			
			if(telephone.length > <?php echo $nbr_chiffre_numero; ?>)
			{
				error = 1;
				$('#telephone').css('border','1px solid #ff0000');
				$('#errorphone').html('<font color=red><b><?php echo str_replace("'","\'",$class_menu->getLangue("erreur_nombre_numero",$language)); ?></b></font>');
			}
			<?php
			}
			?>
		}
		
		if(error == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	</script>	
	</div>
	<?php
	
	include "footer.php";
	
	?>
</body>
</html>