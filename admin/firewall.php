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

function br2nl($string)
{
	$string = str_replace("<br />","",$string);
	return $string;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	
	/* Suppression d'une entrée dans le Firewall */
	if($action == 1)
	{
		$id = $_REQUEST['id'];
		$SQL = "DELETE FROM pas_firewall WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: firewall.php");
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
	<div class="container">
		<H1><?php echo $title_firewall; ?></H1>
		<div class="info">
		<?php echo $title_firewall_description; ?>
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addfirewall.php" class="btn blue">Ajouter une entrer dans le firewall</a>
		</div>
		<table>
			<tr>
				<th>Adresse IP</th>
				<th>Type</th>
				<th>Pays</th>
				<th>Action</th>
			</tr>
		<?php
		
		$SQL = "SELECT COUNT(*) FROM pas_firewall";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		if($req[0] == 0)
		{
			?>
			</table>
			<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
				<h1><?php echo $title_firewall_unknow_enter; ?></h1>
			</div>
			<?php
		}
		else
		{
			$SQL = "SELECT * FROM pas_firewall";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<tr>
					<td><?php echo $req['ip']; ?></td>
					<td><?php echo $req['type']; ?></td>
					<td>
						<img src="images/flag/<?php echo strtolower($req['pays']); ?>.png" width=20>
					</td>
					<td>
						<a href="firewall.php?action=1&id=<?php echo $req['id']; ?>" class="btn red"><?php echo $btn_delete; ?></a>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
			<?php
		}
		?>
	</div>
</body>
</html>