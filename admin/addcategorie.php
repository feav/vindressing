<?php

include "../main.php";

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
		$titre = $_REQUEST['titre'];
		if($titre != '')
		{
			$type_categorie = AntiInjectionSQL($_REQUEST['type_categorie']);
			$parent_categorie = AntiInjectionSQL($_REQUEST['parent_categorie']);
			$filtre = $_REQUEST['filtre'];
			if($type_categorie == 1)
			{
				// Principal
				$titre = str_replace("'","''",$titre);
				$slug = slugify($titre);
				$SQL = "INSERT INTO pas_categorie (subcategorie,titre,slug,meta_title,meta_description) VALUES (0,'$titre','$slug','','')";
				$pdo->query($SQL);
			}
			else
			{
				// Secondaire
				$titre = str_replace("'","''",$titre);
				$slug = slugify($titre);
				$SQL = "INSERT INTO pas_categorie (subcategorie,titre,slug,meta_title,meta_description) VALUES ($parent_categorie,'$titre','$slug','$filtre','')";
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
	<title>Administration PAS Script v1.0</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<a href="categorie.php" class="btn blue">Retour</a>
		<H1>Ajouter une nouvelle categorie</H1>
		<form method="POST">
			<label>Type de categorie :</label>
			<select id="categorie-select" name="type_categorie" onchange="selectCat();" class="inputbox">
				<option value="1">Principal</option>
				<option value="2">Sous-Categorie</option>
			</select>
			<div id="display_parent" style="display:none;">
			<label>Parent de la Categorie :</label>
			<select name="parent_categorie" class="inputbox">
			<?php
			
			$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<option value="<?php echo $req['id']; ?>"><?php echo strtoupper($req['titre']); ?></option>
				<?php
			}
			
			?>
			</select>
			<label>Filtre :</label>
			<select name="filtre" class="inputbox">
				<option value="">Aucun</option>
				<?php
				if(getConfig("module_filtre_adulte") == 'true')
				{
					?>
					<option value="module_filtre_adulte">Module Filtre Popup Adulte</option>
					<?php
				}
				?>
			</select>
			</div>
			<label>Titre de la Categorie :</label>
			<input type="text" name="titre" value="" placeholder="Titre de la Categorie" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
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