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
		$SQL = "DELETE FROM pas_footer WHERE id = $id";
		$pdo->query($SQL);
		
		$SQL = "DELETE FROM pas_footer_colonne WHERE idfooter = $id";
		$pdo->query($SQL);
		
		header("Location: footer.php");
		exit;
	}
	if($action == 2)
	{
		$copyright = AntiInjectionSQL($_REQUEST['copyright']);
		$SQL = "UPDATE pas_copyright SET text = '$copyright' WHERE id = 1";
		$pdo->query($SQL);
		
		header("Location: footer.php");
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
	.col4
	{
		width:25%;
		float:left;
		height:auto;
		background-color:#ff0000;
	}
	
	.footerfix
	{
		overflow:auto;
	}
	</style>
	<div class="container">
		<H1>Footer</H1>
		<div class="info">
		Ajouter et supprimer des pages du footer de votre site internet.
		</div>
		<?php
		
		$SQL = "SELECT COUNT(*) FROM pas_footer";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$count = $req[0];
		
		if($count < 4)
		{
		?>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addfootercolonne.php" class="btn blue">Ajouter une colonne</a>
		</div>
		<?php
		}
		?>
		<table>
			<tr>
				<th>Colonne n°</th>
				<th>Titre</th>
				<th>Option</th>
			</tr>
			<?php
			
			$x = 0;
			
			$SQL = "SELECT * FROM pas_footer";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$x++;
				?>
				<tr>
					<td><?php echo $x; ?></td>
					<td><?php echo $req['titre']; ?></td>
					<td>
						<a href="editfooter.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a>
						<a href="footer.php?action=1&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
		<H1>Copyright</H1>
		<?php
		
		$SQL = "SELECT * FROM pas_copyright WHERE id = 1";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		?>
		<form>
			<input type="text" name="copyright" value="<?php echo $req['text']; ?>" class="inputbox">
			<input type="hidden" name="action" value="2">
			<div style="margin-top:20px;margin-bottom:20px;">
				<input type="submit" value="Modifier" class="btn blue">
			</div>
		</form>
	</div>
</body>
</html>