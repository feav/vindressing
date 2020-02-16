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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<?php
		
		$page = $_REQUEST['page'];
		$data = file_get_contents("https://www.shua-creation.com/help/pas/".$page.".php");
		if($data == '')
		{
			?>
			<b><font color=red>La page d'aide n'as pas pu être charger, ceci est du à un blockage de votre hebergeur qui empeche le chargement distant de page "allow_url_fopen" non activer</b></font>
			<br><br>
			<p>Vous pouvez consulter cette page à ce lien</p>
			<a href="https://www.shua-creation.com/help/pas/<?php echo $page; ?>.php" target="newpage" class="btn">Consulter la page d'aide</a>
			<?php
		}
		else
		{
			echo $data;
		}
		
		?>
	</div>
</body>
</html>