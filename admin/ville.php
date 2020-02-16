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
		$page = AntiInjectionSQL($_REQUEST['page']);
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		if(!$demo)
		{
			$SQL = "DELETE FROM pas_ville WHERE id = $id";
			$pdo->query($SQL);
			
			header("Location: ville.php?page=$page");
			exit;
		}
		else
		{
			?>
			<script>
			alert('La fonction supprimer est désactiver dans la demonstration');
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
	<?php
	
	if(isset($_REQUEST['search']))
	{
		$search = AntiInjectionSQL($_REQUEST['search']);
		$type_recherche = $_REQUEST['type_recherche'];
		
		if($type_recherche == 'ville')
		{
			$searchadd = "WHERE nom like '%$search%'";
		}
		else if($type_recherche == 'codepostal')
		{
			$searchadd = "WHERE codepostal like '%$search%'";
		}
	}
	else
	{
		$searchadd = "";
		$search = "";
		$type_recherche = "ville";
	}
	
	$SQL = "SELECT COUNT(*) FROM pas_ville";
	$r = $pdo->query($SQL);
	$rr = $r->fetch();
	$countville = $rr[0];
	
	?>
	<div class="container">
		<H1><div class="round-count"><?php echo $countville; ?></div> Ville</H1>
		<div class="info">
		Ajouter / Modifier et Supprimer des villes.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addville.php" class="btn blue">Ajouter une ville</a>
		</div>
		<div class="searchbox">
			<form>
				<select name="type_recherche" class="searchtype">
					<?php
					if($type_recherche == 'ville')
					{
						?>
						<option value="ville" selected>Recherche par nom de ville</option>
						<option value="codepostal">Recherche par code postal</option>
						<?php
					}
					else
					{
						?>
						<option value="ville">Recherche par nom de ville</option>
						<option value="codepostal" selected>Recherche par code postal</option>
						<?php
					}
					?>
				</select>
				<input type="text" class="search" name="search" value="<?php echo $search; ?>" placeholder="Votre recherche">
				<input type="submit" value="Rechercher" class="btn blue">
			</form>
		</div>
		<table>
			<tr>
				<th>Département</th>
				<th>Région</th>
				<th>Nom</th>
				<th>Codepostal</th>
				<th>Option</th>
			</tr>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM pas_ville $searchadd";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count = $req[0];
			$number = 100;
			
			if(isset($_REQUEST['page']))
			{
				$page = $_REQUEST['page'];
			}
			else
			{
				$page = 0;
			}
			
			$show = $page * $number;
						
			$SQL = "SELECT * FROM pas_ville $searchadd ORDER BY id ASC LIMIT $show,$number";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<tr>
					<td><?php echo $req['departement']; ?></td>
					<td>
					<?php
					
					$idregion = $req['idregion'];
					echo $idregion;
					
					?>
					</td>
					<td><?php echo $req['nom']; ?></td>
					<td><?php echo $req['codepostal']; ?></td>
					<td>
						<a href="editville.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a>
						<a href="ville.php?page=<?php echo $page; ?>&id=<?php echo $req['id']; ?>&action=1" class="btn red">Supprimer</a>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
		<div style="text-align:center;margin-top:20px;">
		<?php
		
		$numberpage = ceil($count / $number);
		
		
		for($x=0;$x<$numberpage;$x++)
		{
			?>
			<div style="float:left;margin-bottom:20px;margin-right:5px;">
				<a href="ville.php?page=<?php echo $x; ?>" class="btn blue"><?php echo $x+1; ?></a>
			</div>
			<?php
		}
		
		?>
		</div>
	</div>
</body>
</html>