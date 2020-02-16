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
		$SQL = "SELECT * FROM pas_page WHERE id = $id";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$slug = $req['slug'];
		
		if(!$demo)
		{
			$SQL = "DELETE FROM pas_page WHERE id = $id";
			$pdo->query($SQL);
		
			$SQL = "DELETE FROM pas_seo WHERE page = '$slug'";
			$pdo->query($SQL);
			
					
			header("Location: page.php");
			exit;
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
	
	/* Désactiver une boutique */
	if($action == 2)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "UPDATE pas_compte_pro SET visible = 'no' WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: gestionboutique.php");
		exit;
	}
	
	/* Activer une boutique */
	if($action == 3)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "UPDATE pas_compte_pro SET visible = 'yes' WHERE id = $id";
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
	
	?>
	<div class="container">
		<H1><img src="<?php echo $url_script; ?>/admin/images/boutique-big-icon.png" width=30> Gestion des boutiques</H1>
		<div class="info">
		Depuis cette page vous aurez la possibilités de gérer les boutiques de votre site internet. Les Boutiques sont principalement utiliser par les utilisateurs Professionelle.
		</div>
		<table>
			<tr>
				<th>Email de l'utilisateur</th>
				<th>Nom de la boutique</th>
				<th>Etat</th>
				<th>Option</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_compte_pro ORDER BY id DESC";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$md5 = $req['md5'];
				$visible = $req['visible'];
				
				$SQL = "SELECT * FROM pas_user WHERE md5 = '$md5'";
				$r = $pdo->query($SQL);
				$rr = $r->fetch();
				
				$email = $rr['email'];
				$iduser = $rr['id'];
				$titre = $rr['username'];
				
				?>
				<tr>
					<td><?php echo $email; ?></td>
					<td><?php echo $titre; ?></td>
					<td>
					<?php
					if($visible == 'yes')
					{
						?>
						<img src="<?php echo $url_script; ?>/admin/images/valid-icon.png" title="Boutique active" width=20>
						<?php
					}
					else
					{
						?>
						<img src="<?php echo $url_script; ?>/admin/images/invalid-icon.png" title="Boutique désactiver" width=20>
						<?php
					}
					?>
					</td>
					<td>
						<a href="editboutique.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a>
						<?php
						if($visible == 'yes')
						{
							?>
							<a href="<?php echo $url_script; ?>/admin/gestionboutique.php?action=2&id=<?php echo $req['id']; ?>" class="btn red">Désactiver la boutique</a>
							<?php
						}
						else
						{
							?>
							<a href="<?php echo $url_script; ?>/admin/gestionboutique.php?action=3&id=<?php echo $req['id']; ?>" class="btn green">Activer la boutique</a>
							<?php
						}
						?>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
	</div>
</body>
</html>