<?php
include "../../../main.php";
include "../version.php";

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
		$SQL = "DELETE FROM emplacement_publicitaire WHERE id = $id";
		$pdo->query($SQL);
		
		$SQL = "DELETE FROM emplacement_publicitaire_prix_duree WHERE idemplacement = $id";
		$pdo->query($SQL);
		
		header("Location: regiepub.php");
		exit;
	}
	
	if($action == 2)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "DELETE FROM emplacement_publicitaire_prix_duree WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: regiepub.php");
		exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="../../css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "../../header.php";
	include "../../sidebar.php";
	
	?>
	<div class="container">
		<H1>Mini Régie Publicitaire</H1>
		<div class="info">
		Depuis cette interface gérer votre régie publicitaire interne automatiser. Vous aurez la possibilité de définir des encart publicitaire ainsi que les prix de ceux-ci.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addregiepub.php" class="btn blue">Ajouter un encart publicitaire</a>
		</div>
		<H2>Campaigne en cours</H2>
		<table>
			<tr>
				<th>Emplacement</th>
				<th>Durée</th>
				<th>Bannière</th>
				<th>Lien</th>
				<th>Action</th>
			</tr>
		</table>
		<H2>Encart publicitaire</H2>
		<table>
			<tr>
				<th>Emplacement</th>
				<th>Code</th>
				<th>Tarif</th>
				<th>Action</th>
			</tr>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM emplacement_publicitaire";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] == 0)
			{
				?>
				</table>
				<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
				<H1>Aucun encart publicitaire</H1>
				</div>
				<?php
			}
			else
			{
				$SQL = "SELECT * FROM emplacement_publicitaire";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<tr>
						<td><?php echo $req['titre']; ?></td>
						<td>{<?php echo $req['code']; ?>}</td>
						<td>
							<?php
							
							$SQL = "SELECT * FROM emplacement_publicitaire_prix_duree WHERE idemplacement = ".$req['id'];
							$r = $pdo->query($SQL);
							while($rr = $r->fetch())
							{
								echo '<b>Durée :</b> '.$rr['duree'].' jours <b>|</b> <b>Prix :</b> '.number_format($rr['prix'],2,',',' ').' € <a href="regiepub.php?id='.$rr['id'].'&action=2" class="">Supprimer ce tarif</a><br>';
							}
							
							?>
						</td>
						<td>
							<a href="regiepub.php?action=1&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
							<a href="addprixpub.php?id=<?php echo $req['id']; ?>" class="btn blue">Ajouter une politique de prix</a>
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