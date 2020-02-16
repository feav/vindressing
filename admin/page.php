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
		<H1>Page(s)</H1>
		<div class="info">
		Depuis cette interface vous avez la possibilité d'ajouter des pages supplémentaire à votre site internet, comme vos conditions général de vente et toute page utile pour votre site internet.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addpage.php" class="btn blue">Ajouter une page</a>
		</div>
		<table>
			<tr>
				<th>Nom de la page</th>
				<th>URL</th>
				<th>Option</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_page";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<tr>
					<td><?php echo $req['titre']; ?></td>
					<td><input type="text" value="<?php echo $url_script; ?>/page.php?slug=<?php echo $req['slug']; ?>" class="inputbox" style="width: 85%;"> <a href="javascript:void(0);" onclick="copy('<?php echo $url_script; ?>/page.php?slug=<?php echo $req['slug']; ?>');"><img src="images/copy-icon.png" title="Copier l'URL"></a></td>
					<td>
						<a href="editpage.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a>
						<a href="page.php?action=1&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
	</div>
	<script>
	function copy(c)
	{
		navigator.clipboard.writeText(c);
		Swal.fire(
		  'URL Copier dans le presse-papier',
		  '',
		  'success'
		);
	}
	</script>
</body>
</html>