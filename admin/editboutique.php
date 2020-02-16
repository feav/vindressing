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
		$description = AntiInjectionSQL($_REQUEST['description']);
		$adresse = AntiInjectionSQL($_REQUEST['adresse']);
		$site_internet = AntiInjectionSQL($_REQUEST['site_internet']);
		$slogan = AntiInjectionSQL($_REQUEST['slogan']);
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		$SQL = "UPDATE pas_compte_pro SET description = '$description' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_compte_pro SET adresse = '$adresse' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_compte_pro SET site_internet = '$site_internet' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_compte_pro SET slogan = '$slogan' WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: gestionboutique.php");
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
	<script src="<?php echo $url_script; ?>/admin/js/sweetalert.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	$id = AntiInjectionSQL($_REQUEST['id']);
	$SQL = "SELECT * FROM pas_compte_pro WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$description = $req['description'];
	$adresse = $req['adresse'];
	$site_internet = $req['site_internet'];
	$categorie = $req['categorie'];
	$slogan = $req['slogan'];
	$md5 = $req['md5'];
	
	$SQL = "SELECT * FROM pas_user WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	?>
	<div class="container">
		<H1><img src="<?php echo $url_script; ?>/admin/images/boutique-big-icon.png" width=30> Edition des informations de la boutique "<?php echo $req['username']; ?>"</H1>
		<div style="margin-bottom:20px;">
			<a href="<?php echo $url_script; ?>/admin/gestionboutique.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label><b>Description :</b></label>
			<textarea name="description" class="textareabox"><?php echo $description; ?></textarea>
			<label><b>Adresse :</b></label>
			<input type="text" name="adresse" value="<?php echo $adresse; ?>" class="inputbox">
			<label><b>Site internet :</b></label>
			<input type="text" name="site_internet" value="<?php echo $site_internet; ?>" class="inputbox">
			<?php
			/*
			<label><b>Catégorie :</b></label>
			<select name="categorie" class="inputbox">
			
			</select>
			*/
			?>
			<label><b>Slogan :</b></label>
			<input type="text" name="slogan" value="<?php echo $slogan; ?>" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="submit" class="btn blue" value="Modifier">
		</form>
	</div>
</body>
</html>