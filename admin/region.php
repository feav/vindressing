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
		
		if(!$demo)
		{
			$SQL = "DELETE FROM pas_region WHERE id = $id";
			$pdo->query($SQL);
			
					
			header("Location: region.php");
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
	<div class="container">
		<H1>Région</H1>
		<div class="info">
		Ajouter / Modifier et Supprimer des régions.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addregion.php" class="btn blue">Ajouter une région</a>
		</div>
		<table>
			<tr>
				<th>Région</th>
				<th>ID d'association à la carte</th>
				<th>Option</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_region";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<tr>
					<td><?php echo $req['titre']; ?></td>
					<td><?php echo $req['idmap']; ?></td>
					<td>
						<a href="editregion.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a>
						<a href="region.php?action=1&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
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