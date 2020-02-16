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
	
	/* Supprimer l'annonce */
	if($action == 1)
	{
		$md5 = AntiInjectionSQL($_REQUEST['md5']);
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		// On supprime l'annonce
		if(!$demo)
		{
			$SQL = "DELETE FROM pas_annonce WHERE md5 = '$md5'";
			$pdo->query($SQL);
			
			$SQL = "DELETE FROM pas_signaler WHERE id = $id";
			$pdo->query($SQL);
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
		}
		
		header("Location: signalement.php");
	}
	
	/* Supprimer le signalement */
	if($action == 2)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		if(!$demo)
		{
			$SQL = "DELETE FROM pas_signaler WHERE id = $id";
			$pdo->query($SQL);
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
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
	
	?>
	<style>
	td
	{
		font-size:11px;
	}
	
	.bButton
	{
		height: 38px;
		margin-top: 5px;
		margin-bottom: 5px;
	}
	</style>
	<div class="container">
		<H1>Signalement d'annonces</H1>
		<div class="info">
		Retrouvez ici tous les signalements d'annonces de vos utilisateurs.
		</div>
		<table>
			<tr>
				<th>Date</th>
				<th>Nom</th>
				<th>Email</th>
				<th>Type de Fraude</th>
				<th>Message</th>
				<th style="width: 180px;">Action</th>
			</tr>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM pas_signaler";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] == 0)
			{
				?>
				</table>
				<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
				<H1>Aucun signalement pour le moment</H1>
				</div>
				<?php
			}
			else
			{
			
				$SQL = "SELECT * FROM pas_signaler";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<tr>
						<td><?php echo $req['date_post']; ?></td>
						<td><?php echo $req['nom']; ?></td>
						<td><?php echo $req['email']; ?></td>
						<td><?php echo $req['motif']; ?></td>
						<td><?php echo $req['message']; ?></td>
						<td>
							<div class="bButton"><a href="signalement.php?md5=<?php echo $req['annonce_md5']; ?>&id=<?php echo $req['id']; ?>&action=1" class="btn red">Supprimer l'annonce</a></div>
							<div class="bButton"><a href="signalement.php?id=<?php echo $req['id']; ?>&id=<?php echo $req['id']; ?>&action=2" class="btn red">Supprimer le signalement</a></div>
							<div class="bButton"><a href="../showannonce.php?md5=<?php echo $req['annonce_md5']; ?>" target="newpage" class="btn blue">Voir l'annonce concernée</a></div>
						</td>
					</tr>
					<?php
				}
				
				?>
				</table>
			<?php
			}
			?>
	</div>
</body>
</html>