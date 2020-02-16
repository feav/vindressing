<?php

include "../main.php";
include "version.php";

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
	if($action == 1)
	{
		$titre = AntiInjectionSQL($_REQUEST['titre']);
		$id = AntiInjectionSQL($_REQUEST['id']);
		$parent_categorie = AntiInjectionSQL($_REQUEST['parent_categorie']);
		$subcategorie = AntiInjectionSQL($_REQUEST['subcategorie']);
		$filtre = AntiInjectionSQL($_REQUEST['filtre']);
		
		if(getConfig("pictogram_categorie") == 'true')
		{
			$pictogramme = $_REQUEST['pictogramme'];
			$SQL = "UPDATE pas_categorie SET meta_description = '$pictogramme' WHERE id = $id";
			$pdo->query($SQL);
		}
		
		if($titre != '')
		{		
			$SQL = "UPDATE pas_categorie SET titre = '$titre' WHERE id = $id";
			$pdo->query($SQL);
			
			if($subcategorie != 0)
			{
				$SQL = "UPDATE pas_categorie SET subcategorie = $parent_categorie WHERE id = $id";
				$pdo->query($SQL);
				
				$SQL = "UPDATE pas_categorie SET meta_title = '$filtre' WHERE id = $id";
				$pdo->query($SQL);
			}
			
			header("Location: categorie.php");
			exit;
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v.<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	$id = AntiInjectionSQL($_REQUEST['id']);
	$SQL = "SELECT * FROM pas_categorie WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$titre = $req['titre'];
	$subcategorie = $req['subcategorie'];
	$filtre = $req['meta_title'];
	
	?>
	<div class="container">
		<a href="categorie.php" class="btn blue">Retour</a>
		<H1>Edition de la Catégorie "<?php echo $titre; ?>"</H1>
		<form method="POST">
			<label>Type de catégorie :</label>
			<select id="categorie-select" name="type_categorie" onchange="selectCat();" class="inputbox" disabled>
			<?php
			if($req['subcategorie'] == 0)
			{
			?>
				<option value="1" selected>Principale</option>
				<option value="2">Sous-Catégorie</option>
			<?php
			}
			else
			{
			?>
				<option value="1">Principale</option>
				<option value="2" selected>Sous-Catégorie</option>
			<?php
			}
			?>
			</select>
			<?php
			if($req['subcategorie'] == 0)
			{
				$css = 'display:none;';
			}
			else
			{
				$css = 'display:block;';
			}
			?>
			<?php
			
			if(getConfig("pictogram_categorie") == 'true')
			{
				if($req['subcategorie'] == 0)
				{
				?>
				<label>Pictogramme :</label>
				<input type="text" name="pictogramme" value="<?php echo htmlentities($req['meta_description']); ?>" class="inputbox">
				<?php
				}
			}
			
			?>
			<div id="display_parent" style="<?php echo $css; ?>">
			<label>Parent de la Catégorie :</label>
			<select name="parent_categorie" class="inputbox">
			<?php
			
			$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				if($subcategorie == $req['id'])
				{
				?>
				<option value="<?php echo $req['id']; ?>" selected><?php echo strtoupper($req['titre']); ?></option>
				<?php	
				}
				else
				{
				?>
				<option value="<?php echo $req['id']; ?>"><?php echo strtoupper($req['titre']); ?></option>
				<?php
				}
			}
			
			?>
			</select>
			<label>Filtre :</label>
			<select name="filtre" class="inputbox">
				<?php
				if($filtre == '')
				{
					?>
					<option value="" selected>Aucun</option>
					<?php
				}
				else
				{
					?>
					<option value="">Aucun</option>
					<?php
				}
				?>
				<?php
				if(getConfig("module_filtre_adulte") == 'true')
				{
					if($filtre == 'module_filtre_adulte')
					{
						?>
						<option value="module_filtre_adulte" selected>Module Filtre Popup Adulte</option>
						<?php
					}
					else
					{
						?>
						<option value="module_filtre_adulte">Module Filtre Popup Adulte</option>
						<?php
					}
				}
				if(getConfig("module_filtre_auto") == 'true')
				{
					if($filtre == 'module_filtre_auto')
					{
						?>
						<option value="module_filtre_auto" selected>Module Filtre Automobile</option>
						<?php
					}
					else
					{
						?>
						<option value="module_filtre_auto">Module Filtre Automobile</option>
						<?php
					}
				}
				if(getConfig("module_filtre_immo") == 'true')
				{
					if($filtre == 'module_filtre_immo')
					{
						?>
						<option value="module_filtre_immo" selected>Module Filtre Immobilier</option>
						<?php
					}
					else
					{
						?>
						<option value="module_filtre_immo">Module Filtre Immobilier</option>
						<?php
					}
				}
				?>
			</select>
			</div>
			<label>Titre de la Catégorie :</label>
			<input type="text" name="titre" value="<?php echo $titre; ?>" placeholder="Titre de la Catégorie" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="subcategorie" value="<?php echo $subcategorie; ?>">
			<input type="submit" value="Modifier" class="btn orange">
		</form>
		<script>
		function selectCat()
		{
			var cat = $('#categorie-select').val();
			if(cat == 1)
			{
				$('#display_parent').css('display','none');
			}
			else
			{
				$('#display_parent').css('display','block');
			}
		}
		</script>
	</div>
</body>
</html>