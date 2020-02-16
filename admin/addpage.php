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
		$titre_seo = AntiInjectionSQL($_REQUEST['titre_seo']);
		$meta_description = AntiInjectionSQL($_REQUEST['meta_description']);
		
		$contenue = AntiInjectionSQL($_REQUEST['contenue']);
		
		$slug = slugify($titre);
		
		/* On ajoute la page */
		$SQL = "INSERT INTO pas_page (titre,contenue,slug) VALUES ('$titre','$contenue','$slug')";
		$pdo->query($SQL);
		
		/* On ajoute le SEO */
		$SQL = "INSERT INTO pas_seo (page,title,description) VALUES ('$slug','$titre_seo','$meta_description')";
		$pdo->query($SQL);
		
		header("Location: page.php");
		exit;
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
	<script src="ckeditor/ckeditor.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Ajout d'une nouvelle page</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="page.php" class="btn blue">Retour</a>
		</div>
		<form method="POST" enctype="multipart/form-data">
			<label>Titre de la page :</label>
			<input type="text" name="titre" class="inputbox" placeholder="Titre interne de la page">
			<label>Balise titre SEO :</label>
			<input type="text" name="titre_seo" class="inputbox" placeholder="Entrer la balise title SEO">
			<label>Meta Description SEO :</label>
			<input type="text" name="meta_description" class="inputbox" placeholder="Entrer le meta description SEO">
			<label>Contenue de la page :</label>
			<textarea name="contenue" class="ckeditor" id="editor1" placeholder="Contenue de votre page"></textarea>
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>