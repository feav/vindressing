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

$id = AntiInjectionSQL($_REQUEST['id']);

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	/* Mise à jour du titre et du supplément HTML */
	if($action == 1)
	{
		$title_colonne = AntiInjectionSQL($_REQUEST['title_colonne']);
		$SQL = "UPDATE pas_footer set titre = '$title_colonne' WHERE id = $id";
		$pdo->query($SQL);
		
		$contenue = AntiInjectionSQL($_REQUEST['contenue']);
		$SQL = "UPDATE pas_footer set contenue = '$contenue' WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: editfooter.php?id=$id");
		exit;
	}
	/* Insertion d'une page dans le Footer */
	if($action == 2)
	{
		$idpage = AntiInjectionSQL($_REQUEST['page']);
		$SQL = "INSERT INTO pas_footer_colonne (idfooter,idpage) VALUES ($id,$idpage)";
		$pdo->query($SQL);
		
		header("Location: editfooter.php?id=$id");
		exit;
	}
	/* Suppression d'une page dans un Footer */
	if($action == 3)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$idpage = AntiInjectionSQL($_REQUEST['page']);
		$SQL = "DELETE FROM pas_footer_colonne WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: editfooter.php?id=$idpage");
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
	
	$SQL = "SELECT * FROM pas_footer WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	?>
	<div class="container">
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="footer.php" class="btn blue">Retour</a>
		</div>
		<H1>Modification de la colonne "<?php echo $req['titre']; ?>" du footer</H1>
		<div class="info">
		Modifier le titre de la colonne de votre footer et ajouter et supprimer des pages contenue dans la colonne.
		</div>
		<form>
			<label><b>Titre de la colonne :</b></label>
			<input type="text" name="title_colonne" value="<?php echo $req['titre']; ?>" class="inputbox">
			<label><b>Contenue HTML supplémentaire :</b></label>
			<textarea name="contenue" class="textareabox"><?php echo $req['contenue']; ?></textarea>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $req['id']; ?>">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
		<H2>Page de la colonne</H2>
		<table>
			<tr>
				<th>Page</th>
				<th>Option</th>
			</tr>
			<?php
			
			$SQL = "SELECT * FROM pas_footer_colonne WHERE idfooter = $id";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$idpage = $req['idpage'];
				
				$SQL = "SELECT * FROM pas_page WHERE id = $idpage";
				$r = $pdo->query($SQL);
				$rr = $r->fetch();
				
				?>
				<tr>
					<td><?php echo $rr['titre']; ?></td>
					<td>
						<a href="editfooter.php?action=3&id=<?php echo $req['id']; ?>&page=<?php echo $id; ?>" class="btn red">Supprimer</a>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
		<H2>Ajouter une nouvelle page</H2>
		<form>
			<label><b>Page à ajouter :</b></label>
			<select name="page" class="inputbox">
			<?php
			
			$SQL = "SELECT * FROM pas_page";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<option value="<?php echo $req['id']; ?>"><?php echo $req['titre']; ?></option>
				<?php
			}
			
			?>
			</select>
			<input type="hidden" name="action" value="2">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="submit" value="Ajouter" class="btn blue">
		</form>
	</div>
</body>
</html>