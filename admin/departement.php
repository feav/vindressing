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
	
	/* Supprimer une ville */
	if($action == 1)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		$SQL = "DELETE FROM pas_departement WHERE departement_id = $id";
		$pdo->query($SQL);
		
		header("Location: departement.php");
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
	<style>
	.searchbox
	{
		width: 100%;
		padding: 10px;
		background-color: #e3e3e3;
		box-sizing: border-box;
		margin-bottom: 20px;
		border-radius: 5px;
	}
	
	.searchtype
	{
		width:33.33%;
		height:35px;
		box-sizing: border-box;
	}
	
	.search
	{
		width:33.33%;
		height:35px;
		box-sizing: border-box;
	}
	</style>
	<div class="container">
		<H1>Département</H1>
		<div class="info">
		Ajouter / Modifier et Supprimer des départements.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="adddepartement.php" class="btn blue">Ajouter un département</a>
		</div>
		<table>
			<tr>
				<th>Nom</th>
				<th>Région associé</th>
				<th>Option</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_departement";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$idregion = $req['idregion'];
				?>
				<tr>
					<td><?php echo $req['departement_nom']; ?></td>
					<td>
					<?php
					
					$SQL = "SELECT * FROM pas_region WHERE id = $idregion";
					$r = $pdo->query($SQL);
					$rr = $r->fetch();
					
					echo $rr['titre'];
						
					?>
					</td>
					<td>
						<a href="editdepartement.php?id=<?php echo $req['departement_id']; ?>" class="btn blue">Modifier</a>
						<a href="departement.php?id=<?php echo $req['departement_id']; ?>&action=1" class="btn red">Supprimer</a>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
	</div>
</body>
</html>