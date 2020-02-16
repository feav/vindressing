<?php
include "../main.php";
include "version.php";

$sigle = $class_monetaire->getSigle();
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
	/* Supprimer une regle */
	if($action == 1)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "DELETE FROM pas_grille_tarif WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: grilletarifaire.php");
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
</head>
<body>
<?php	

include "header.php";
include "sidebar.php";

?>
<style>	
td
{
	font-size:14px;
	height:30px;
}
.bButton
{
	height: 38px;
	margin-top: 5px;
	margin-bottom: 5px;
}
.boxChiffre
{
	float:left;
	width: 30%;
	padding: 20px;
	box-sizing: border-box;
	background-color: #00aeff;
	border-radius: 5px;
	margin-bottom: 20px;
	color: #ffffff;
	margin-right:3.33%;
}
.ChiffreAffaire
{
	font-size: 48px;
	color: #ffffff;
}
.boxStatistique
{
	overflow:auto;
}
.greenvente
{
	background-color:#049b10;
}
.orangevente
{
	background-color:#ffb300;
}
</style>
<div class="container">
	<H1><i class="fas fa-money-bill-wave"></i> Grille Tarifaire</H1>
	<div class="info">
	Créer vos tarifs et vos options comme bon vous semble pour votre site internet.
	</div>
	<div style="margin-bottom:20px;">
		<a href="<?php echo $url_script; ?>/admin/addoptiontarif.php" class="btn blue"><i class="fas fa-plus-circle"></i> Ajouter un nouvelle option</a>
	</div>
	<table>
		<tr>
			<th>Nom</th>
			<th>Type d'option de paiement</th>
			<th>Catégorie</th>
			<th>Durée</th>
			<th>Remonter</th>
			<th>Signaletique</th>
			<th>Prix</th>
			<th>Action</th>
		</tr>
		<?php
		
		$SQL = "SELECT * FROM pas_grille_tarif";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			?>
			<tr>
				<td><?php echo $req['nom']; ?></td>
				<td>
				<?php
				if($req['emplacement'] == 'classic')
				{
					?>
					<i class="fas fa-money-bill-wave"></i> Option prix principal de l'annonce
					<?php
				}
				if($req['emplacement'] == 'remonter')
				{
					?>
					<i class="fas fa-arrow-alt-circle-up"></i> Option avec remonter d'annonce en Topliste
					<?php
				}
				if($req['emplacement'] == 'bandeau')
				{
					?>
					<i class="fas fa-exclamation-triangle"></i> Option avec signaletique
					<?php
				}
				if($req['emplacement'] == 'photo')
				{
					?>
					<i class="fas fa-camera"></i> Option annonce avec Photo supplémentaire
					<?php
				}
				if($req['emplacement'] == 'alaune')
				{
					?>
					<i class="fas fa-sort-numeric-up"></i> Option annonce à la Une
					<?php
				}
				?>
				</td>
				<td>
				<?php
				if($req['categorie'] == '0')
				{
					?>
					<i class="fas fa-infinity"></i> Toutes les catégories
					<?php
				}
				else
				{
					$cat = $req['categorie'];
					$SQL = "SELECT * FROM pas_categorie WHERE id = $cat";
					$r = $pdo->query($SQL);
					$rr = $r->fetch();
					echo '<b>'.$rr['titre'].'</b>';
				}
				?>
				</td>
				<td>
				<?php
				if($req['emplacement'] == 'remonter')
				{
					echo '<i class="far fa-clock"></i> '.$req['duree'].' jour(s)';
				}
				else
				{
					?>
					<i class="fas fa-times"></i>
					<?php
				}
				?>
				</td>
				<td>
				<?php
				if($req['emplacement'] == 'remonter')
				{
					echo '<i class="far fa-clock"></i> Remonter au en topliste tous les '.$req['remonter_x_jour'].' jour(s)';
				}
				else
				{
					?>
					<i class="fas fa-times"></i>
					<?php
				}
				?>
				</td>
				<td>
				<?php
				if($req['emplacement'] == 'bandeau')
				{
					?>
					<img src="<?php echo $url_script; ?>/images/<?php echo $req['bandeau']; ?>">
					<?php
				}
				else
				{
					?>
					<i class="fas fa-times"></i>
					<?php
				}
				?>
				</td>
				<td>
					<?php 
					if($req['prix'] == 0)
					{
						echo '<b>Gratuit</b>';
					}
					else
					{
						echo number_format($req['prix'],2).' '.$sigle;
					}
					?>
				</td>
				<td>
					<a href="<?php echo $url_script; ?>/admin/grilletarifaire.php?action=1&id=<?php echo $req['id']; ?>" class="btn red"><i class="fas fa-trash-alt"></i> Supprimer</a>
				</td>
			</tr>
			<?php
		}
		
		?>
	</table>
</div>
</body>
</html>