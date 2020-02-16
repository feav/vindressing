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
		$id = AntiInjectionSQL($_REQUEST['id']);
		$slug = AntiInjectionSQL($_REQUEST['slug']);
		$titre = AntiInjectionSQL($_REQUEST['titre']);
		$titre_seo = AntiInjectionSQL($_REQUEST['titre_seo']);
		$meta_description = AntiInjectionSQL($_REQUEST['meta_description']);
		$contenue = $_REQUEST['contenue'];
		$contenue = str_replace("'","''",$contenue);
		
		$SQL = "UPDATE pas_page SET contenue = '$contenue' WHERE id = $id";
		$pdo->query($SQL);
		
		$SQL = "UPDATE pas_seo SET title = '$titre_seo' WHERE slug = '$slug'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE pas_seo SET description = '$meta_description' WHERE slug = '$slug'";
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

	$id = AntiInjectionSQL($_REQUEST['id']);
	$SQL = "SELECT * FROM pas_page WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();

	$slug = $req['slug'];

	$SQL = "SELECT * FROM pas_seo WHERE page = '$slug'";
	$r = $pdo->query($SQL);
	$rr = $r->fetch();

	
	?>
	<div class="container">
		<H1>Edition de la page "<?php echo $req['titre']; ?>"</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="page.php" class="btn blue">Retour</a>
		</div>
		<form method="POST" enctype="multipart/form-data">
			<label>Titre de la page :</label>
			<input type="text" name="titre" class="inputbox" value="<?php echo $req['titre']; ?>" placeholder="Titre interne de la page" disabled>
			<label>Balise titre SEO :</label>
			<input type="text" name="titre_seo" class="inputbox" value="<?php echo $rr['title']; ?>" placeholder="Entrer la balise title SEO">
			<label>Meta Description SEO :</label>
			<input type="text" name="meta_description" class="inputbox" value="<?php echo $rr['description']; ?>" placeholder="Entrer le meta description SEO">
			<label>Contenue de la page :</label>
			<textarea name="contenue" class="ckeditor" id="editor1" placeholder="Contenue de votre page"><?php echo $req['contenue']; ?></textarea>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="slug" value="<?php echo $slug; ?>">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>