<?php

include "../config.php";
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
		$identifiant = $_REQUEST['identifiant'];
		$code = $_REQUEST['code'];
		$code = str_replace("'","''",$code);
		$id = $_REQUEST['id'];
		
		$SQL = "UPDATE pas_publicite SET identifiant = '$identifiant' WHERE id = $id";
		$pdo->query($SQL);
		
		$SQL = "UPDATE pas_publicite SET code = '$code' WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: publicite.php");
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
	
	$id = $_REQUEST['id'];
	
	$SQL = "SELECT * FROM pas_publicite WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	?>
	<div class="container">
		<H1>Edition du bloc publicitaire "{<?php echo $req['identifiant']; ?>}"</H1>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="publicite.php" class="btn blue">Retour</a>
		</div>
		<form method="POST">
			<label>Identifiant :</label>
			<input type="text" name="identifiant" class="inputbox" placeholder="Identifiant du bloc publicitaire" value="<?php echo $req['identifiant']; ?>">
			<label>Code :</label>
			<textarea name="code" class="textareabox" placeholder="Code de la bannière"><?php echo $req['code']; ?></textarea>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>