<?php

include "../config.php";

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
}

if(isset($_REQUEST['id']))
{
	$id = $_REQUEST['id'];
}
else
{
	header("Location: langue.php");
	exit;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$texte_traduction = $_REQUEST['texte_traduction'];
		$texte_traduction = str_replace("'","''",$texte_traduction);
		
		if($texte_traduction == '')
		{
			$error = 1;
		}
		else
		{
			$SQL = "UPDATE pas_langue SET texte = '$texte_traduction' WHERE id = $id";
			$pdo->query($SQL);
		
			header("Location: langue.php");
			exit;
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v1.0</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	$SQL = "SELECT * FROM pas_langue WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$language = $req['language'];
	
	$SQL = "SELECT * FROM pas_langue_add WHERE language = '$language'";
	$r = $pdo->query($SQL);
	$rr = $r->fetch();
	
	$langue = $rr['language_texte'];
	
	?>
	<div class="container">
		<H1>Modification de la traduction "<?php echo $req['identifiant']; ?>" en <?php echo utf8_encode($langue); ?></H1>
		<?php
		if($error == 1)
		{
			?>
			<div class="msgerror">
			La traduction ne peux pas être vide.
			</div>
			<?php
		}
		?>
		<form method="POST">
			<label>Texte :</label>
			<input type="text" name="texte_traduction" value="<?php echo $req['texte']; ?>" class="inputbox">
			<input type="hidden" name="id" value="<?php echo $id ; ?>">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>