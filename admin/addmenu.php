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
	
	/* Ajoute un nouvelle onglet au menu */
	if($action == 1)
	{
		$title = AntiInjectionSQL($_REQUEST['title']);		
		$language = AntiInjectionSQL($_REQUEST['language']);		
		$url = AntiInjectionSQL($_REQUEST['url']);		
		$url_rewriting = AntiInjectionSQL($_REQUEST['url_rewriting']);
		
		$SQL = "INSERT INTO pas_menu (title,language,url,url_rewriting,method) VALUES ('$title','$language','$url','$url_rewriting','')";
		$pdo->query($SQL);
		
		header("Location: menu.php");
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
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Ajouter un nouvelle onglet au Menu</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="menu.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label>Titre :</label>
			<input type="text" name="title" placeholder="Titre de l'onglet" class="inputbox">
			<label>Langue :</label>
			<select name="language" class="inputbox">
			<?php
			
			$SQL = "SELECT * FROM pas_langue_add";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<option value="<?php echo $req['language']; ?>"><?php echo utf8_encode($req['language_texte']); ?></option>
				<?php
			}
			
			?>
			</select>
			<label>URL :</label>
			<input type="text" name="url" placeholder="URL normal" class="inputbox">
			<label>URL Rewriting :</label>
			<input type="text" name="url_rewriting" placeholder="URL Rewriting" class="inputbox">
			<label>Method :</label>
			<select name="method" class="inputbox">
				<option value="">Aucunes</option>
				<option value="inmenu">Connexion utilisateur integrer dans le menu</option>
				<option value="inscriptionmobile">Page inscription visible uniquement dans le menu mobile</option>
				<option value="connexionmobile">Page de connexion visible uniquement dans le menu mobile</option>
				<option value="logoutmobile">Page de deconnexion visible uniquement dans le menu mobile</option>
				<option value="onlymobile">Page visible uniquement sur mobile</option>
				<option value="moncomptemobile">Page mon compte visible uniquement dans le menu mobile</option>
				<option value="moncompte">Page mon compte une fois connecter</option>
				<option value="logout">Page de déconnexion</option>
			</select>
			<input type="hidden" name="action" value="1">
			<div style="margin-top:10px;margin-bottom:20px;">
				<input type="submit" value="Modifier" class="btn blue">
			</div>
		</form>
	</div>
</body>
</html>