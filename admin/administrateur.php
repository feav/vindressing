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
		if(!$demo)
		{
			$id = AntiInjectionSQL($_REQUEST['id']);
			$SQL = "DELETE FROM pas_admin_user WHERE id = $id";
			$pdo->query($SQL);
			header("Location: administrateur.php");
			exit;
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
			header("Location: administrateur.php");
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
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE type_compte = 'admin'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$count = $req[0];
	
	?>
	<div class="container">
		<H1><div class="round-count"><?php echo $count; ?></div> Gestion des Administrateurs</H1>
		<div class="info">
		Gérer vos comptes Administrateur depuis cette page, ajoutez de nouveaux utilisateurs qui pourront avoir le droit d'utiliser ou non l'administration et changer vos mots de passe.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
		<a href="addadmin.php" class="btn blue">Ajouter un compte administrateur</a>
		</div>
		<table>
			<tr>
				<th>Compte</th>
				<th>Action</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_admin_user WHERE type_compte = 'admin'";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
			?>
			<tr>
				<td><?php echo $req['username']; ?></td>
				<td>
					<a href="administrateur.php?action=1&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
					<a href="editadministrateur.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier le compte</a>
				</td>
			</tr>
			<?php
			}
			
			?>
		</table>
	</div>
</body>
</html>