<?php

include "../main.php";

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
		if(!$demo)
		{
			$id = AntiInjectionSQL($_REQUEST['id']);
			$username = AntiInjectionSQL($_REQUEST['username']);
			$password = AntiInjectionSQL($_REQUEST['password']);
			$SQL = "UPDATE pas_admin_user SET username = '$username' WHERE id = $id";
			$pdo->query($SQL);
			$SQL = "UPDATE pas_admin_user SET password = '$password' WHERE id = $id";
			$pdo->query($SQL);
			header("Location: administrateur.php");
			exit;
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
			header("Location: editadministrateur.php");
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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	$id = AntiInjectionSQL($_REQUEST['id']);
	
	$SQL = "SELECT * FROM pas_admin_user WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	?>
	<div class="container">
		<a href="administrateur.php" class="btn blue">Retour</a>
		<H1>Edition du compte administrateur "<?php echo $req['username']; ?>"</H1>
		<form method="POST">
			<label>Nom d'utilisateur :</label>
			<input type="text" name="username" value="<?php echo $req['username']; ?>" placeholder="Nom d'utilisateur" class="inputbox">
			<label>Mot de passe :</label>
			<input type="password" name="password" value="<?php echo $req['password']; ?>" placeholder="Votre mot de passe" class="inputbox">
			<input type="hidden" name="id" value="<?php echo $req['id']; ?>">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>