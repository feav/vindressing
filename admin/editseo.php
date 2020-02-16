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

$id = AntiInjectionSQL($_REQUEST['id']);

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$title = AntiInjectionSQL($_REQUEST['title']);
		$description = AntiInjectionSQL($_REQUEST['description']);
		$seo_titre_social = AntiInjectionSQL($_REQUEST['seo_titre_social']);
		$seo_description_social = AntiInjectionSQL($_REQUEST['seo_description_social']);
		
		$SQL = "UPDATE pas_seo SET title = '$title' WHERE page = '$id'";
		$pdo->query($SQL);
			
		$SQL = "UPDATE pas_seo SET description = '$description' WHERE page = '$id'";
		$pdo->query($SQL);

		$SQL = "UPDATE pas_seo SET title_social = '$seo_titre_social' WHERE page = '$id'";
		$pdo->query($SQL);	
		
		$SQL = "UPDATE pas_seo SET description_social = '$seo_description_social' WHERE page = '$id'";
		$pdo->query($SQL);
		
		if($_FILES['seo_image_social']['tmp_name'] != '')
		{
			$chemin = $_SERVER["DOCUMENT_ROOT"].$upload_path."/images/";
			$tmp_file = $_FILES['seo_image_social']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				// Fichier introuvable
				header("Location: editseo.php?msgerror=1");
				exit;
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['seo_image_social']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['seo_image_social']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$extension = strtolower($extension);
			
			$name_file = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file) )
			{
				// Impossible de copier le fichier
				header("Location: editseo.php?msgerror=2");
				exit;
			}
			
			/* Fix Faille Upload */
			if(!getimagesize($chemin.$name_file))
			{
				unlink($chemin.$name_file);
				// Not image
				header("Location: editseo.php?msgerror=3");
				exit;
			}
			
			$SQL = "UPDATE pas_seo SET image_social = '$name_file' WHERE page = '$id'";
			$pdo->query($SQL);
		}
					
		header("Location: seo.php");
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
</head>
<body>
	<style>
	.badge-success
	{
		background-color: #0bba06;
		color: #fff;
		font-size: 12px;
		padding: 4px;
		border-radius: 5px;
		font-weight: bold;
	}
	
	.badge-warning
	{
		background-color: #d89000;
		color: #fff;
		font-size: 12px;
		padding: 4px;
		border-radius: 5px;
		font-weight: bold;
	}
	</style>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	$SQL = "SELECT * FROM pas_seo WHERE page = '$id'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$title = $req['title'];
	$description = $req['description'];
	$title_social = $req['title_social'];
	$description_social = $req['description_social'];
	$image_social = $req['image_social'];
	
	$title_length = strlen($req['title']);
	if($title_length > 65)
	{
		$title_highlight = 'badge-warning';
	}
	else
	{
		$title_highlight = 'badge-success';
	}
	
	$description_length = strlen($req['description']);
	if($description_length > 165)
	{
		$description_highlight = 'badge-warning';
	}
	else
	{
		$description_highlight = 'badge-success';
	}
	
	?>
	<div class="container">
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="seo.php" class="btn blue">Retour</a>
		</div>
		<H1>Edition SEO de la page "<?php echo $id; ?>"</H1>
		<div class="info">
			<span class="badge badge-secondary badge-warning">Warning</span> Cette couleur annonce que votre contenue est trop long par rapport au nombre de caractères maximum<br>
			<span class="badge badge-secondary badge-success">OK</span> Cette couleur annonce que votre contenue est conforme 
		</div>
		<form method="POST" enctype="multipart/form-data">
			<label><span class="badge badge-secondary <?php echo $title_highlight; ?>" id="title-car"><?php echo strlen($req['title']); ?> Caractères</span> <b>Titre de la page :</b></label>
			<div id="errortitre"></div>
			<input type="text" name="title" id="seo_titre" onkeyup="updateTitle();" value="<?php echo $title; ?>" class="inputbox">
			<label><span class="badge badge-secondary <?php echo $description_highlight; ?>" id="description-car"><?php echo strlen($req['description']); ?> Caractères</span> <b>Description de la page :</b></label>
			<input type="text" name="description" id="seo_description" onkeyup="updateDescription();" value="<?php echo $description; ?>" class="inputbox">
			<label><b>Titre partage réseau sociaux :</b></label>
			<input type="text" name="seo_titre_social" value="<?php echo $title_social; ?>" class="inputbox">
			<label><b>Description partage réseau sociaux :</b></label>
			<input type="text" name="seo_description_social" value="<?php echo $description_social; ?>" class="inputbox">
			<label><b>Image réseau social :</b></label>
			<?php
			if($image_social != '')
			{
				?>
				<br><br><img src="<?php echo $url_script; ?>/images/<?php echo $image_social; ?>" width=110><br><br>
				<?php
			}
			?>
			<input type="file" name="seo_image_social" value="" class="inputbox">
			<div style="margin-top:20px;">
				<input type="submit" value="Modifier" class="btn blue">
			</div>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
		</form>
		<H2>Résultat visible sur Google</H2>
		<div class="result-google">
			<div class="result-google-title" id="result-google-titre"><?php echo $title; ?></div>
			<div class="result-google-url"><?php echo $url_script.$req['url']; ?></div>
			<div class="result-google-description" id="result-google-description"><?php echo $description; ?></div>
		</div>
		<script>
		var titreold = '';
		
		function updateTitle()
		{
			var titre = $('#seo_titre').val();
			if(titre.length > 65)
			{
				$('#seo_titre').val(titreold);
				$('#errortitre').html('<font color=red><b>La balise titre ne doit pas dépasser 65 caractères</b></font>');
			}
			else if(titre.length < 65)
			{
				$('#errortitre').html('');
			}
			titreold = titre;
			$('#result-google-titre').html(titre);
		}
		
		function updateDescription()
		{
			var description = $('#seo_description').val();
			$('#result-google-description').html(description);
		}
		</script>
	</div>
</body>
</html>