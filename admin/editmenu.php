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
		$title = $_REQUEST['title'];		
		$language = AntiInjectionSQL($_REQUEST['language']);		
		$url = AntiInjectionSQL($_REQUEST['url']);		
		$url_rewriting = AntiInjectionSQL($_REQUEST['url_rewriting']);
		$method = AntiInjectionSQL($_REQUEST['method']);
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		$SQL = "UPDATE pas_menu SET title = '$title' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_menu SET language = '$language' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_menu SET url = '$url' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_menu SET url_rewriting = '$url_rewriting' WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "UPDATE pas_menu SET method = '$method' WHERE id = $id";
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
	
	$id = AntiInjectionSQL($_REQUEST['id']);
	$SQL = "SELECT * FROM pas_menu WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	?>
	<div class="container">
		<H1>Edition de l'onglet Menu "<?php echo $req['title']; ?>"</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="menu.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label>Titre :</label>
			<input type="text" name="title" value="<?php echo htmlentities($req['title']); ?>" placeholder="Titre de l'onglet" class="inputbox">
			<label>Langue :</label>
			<select name="language" class="inputbox">
			<?php
			
			$SQL = "SELECT * FROM pas_langue_add";
			$r = $pdo->query($SQL);
			while($rr = $r->fetch())
			{
				if($rr['language'] == $req['language'])
				{
				?>
				<option value="<?php echo $rr['language']; ?>" selected><?php echo utf8_encode($rr['language_texte']); ?></option>
				<?php	
				}
				else
				{
				?>
				<option value="<?php echo $rr['language']; ?>"><?php echo utf8_encode($rr['language_texte']); ?></option>
				<?php
				}
			}
			
			?>
			</select>
			<label>URL :</label>
			<input type="text" name="url" value="<?php echo $req['url']; ?>" placeholder="URL normal" class="inputbox">
			<label>URL Rewriting :</label>
			<input type="text" name="url_rewriting" value="<?php echo $req['url_rewriting']; ?>" placeholder="URL Rewriting" class="inputbox">
			<label>Method :</label>
			<select name="method" class="inputbox">
				<?php
				if($req['method'] == '')
				{
					?>
					<option value="" selected>Aucunes</option>
					<?php
				}
				else
				{
					?>
					<option value="">Aucunes</option>
					<?php
				}
				if($req['method'] == 'inmenu')
				{
					?>
					<option value="inmenu" selected>Connexion utilisateur integrer dans le menu</option>
					<?php
				}
				else
				{
					?>
					<option value="inmenu" >Connexion utilisateur integrer dans le menu</option>
					<?php
				}
				if($req['method'] == 'inscriptionmobile')
				{
					?>
					<option value="inscriptionmobile" selected>Page inscription visible uniquement dans le menu mobile</option>
					<?php
				}
				else
				{
					?>
					<option value="inscriptionmobile">Page inscription visible uniquement dans le menu mobile</option>
					<?php
				}
				if($req['method'] == 'connexionmobile')
				{
					?>
					<option value="connexionmobile" selected>Page de connexion visible uniquement dans le menu mobile</option>
					<?php
				}
				else
				{
					?>
					<option value="connexionmobile">Page de connexion visible uniquement dans le menu mobile</option>
					<?php
				}
				if($req['method'] == 'logoutmobile')
				{
					?>
					<option value="logoutmobile" selected>Page de deconnexion visible uniquement dans le menu mobile</option>
					<?php
				}
				else
				{
					?>
					<option value="logoutmobile">Page de deconnexion visible uniquement dans le menu mobile</option>
					<?php
				}
				if($req['method'] == 'onlymobile')
				{
					?>
					<option value="onlymobile" selected>Page visible uniquement sur mobile</option>
					<?php
				}
				else
				{
					?>
					<option value="onlymobile">Page visible uniquement sur mobile</option>
					<?php
				}
				if($req['method'] == 'moncomptemobile')
				{
					?>
					<option value="moncomptemobile" selected>Page mon compte visible uniquement dans le menu mobile</option>
					<?php
				}
				else
				{
					?>
					<option value="moncomptemobile">Page mon compte visible uniquement dans le menu mobile</option>
					<?php
				}
				if($req['method'] == 'moncompte')
				{
					?>
					<option value="moncompte" selected>Page mon compte une fois connecter</option>
					<?php
				}
				else
				{
					?>
					<option value="moncompte">Page mon compte une fois connecter</option>
					<?php
				}
				if($req['method'] == 'logout')
				{
					?>
					<option value="logout" selected>Page de déconnexion</option>
					<?php
				}
				else
				{
					?>
					<option value="logout">Page de déconnexion</option>
					<?php
				}
				?>
			</select>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div style="margin-top:10px;margin-bottom:20px;">
				<input type="submit" value="Modifier" class="btn blue">
			</div>
		</form>
	</div>
</body>
</html>